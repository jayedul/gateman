<?php

namespace DevJK\Gateman\Setup;

use DevJK\Gateman\Main;
use DevJK\Gateman\Models\Settings;

class SettingsPage {


	public function __construct() {
		add_action( 'admin_menu', array( $this, 'addPage' ) );
		add_action( 'admin_init', array( $this, 'regSettings' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( Main::$configs->file ), array( $this, 'addSettingsLink' ) );
	}

	/**
	 * Add settings menu in plugins page
	 *
	 * @param array $links
	 * @return array
	 */
	public function addSettingsLink( $links ) {
		$settings_link = '<a href="options-general.php?page=simple-login">Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Add settings page under Settings root menu
	 */
	public function addPage() {
		add_options_page(
			__( 'Simple Login', 'gateman' ),
			__( 'Simple Login', 'gateman' ),
			'manage_options',
			'simple-login',
			array( $this, 'renderPage' )
		);
	}

	/**
	 * Register settings and fields
	 */
	public function regSettings() {
		register_setting(
			'simple_login_group',
			Settings::OPTION_NAME,
			array( 'sanitize_callback' => array( $this, 'sanitize' ) )
		);

		add_settings_section(
			'simple_login_section',
			__( 'Simple Login Settings', 'gateman' ),
			'__return_false',
			'simple-login'
		);

		add_settings_field(
			'replace_wp_login',
			'Replace WP Login Page',
			array( $this, 'field_replace_wp_login' ),
			'simple-login',
			'simple_login_section'
		);

		add_settings_field(
			'use_gateman_css',
			'Use Gateman css',
			array( $this, 'field_use_gateman_css' ),
			'simple-login',
			'simple_login_section'
		);

		/* add_settings_field(
			'agreement_page_ids',
			__( 'Agreement Page IDs (comma-separated)', 'gateman' ),
			array( $this, 'field_agreement_page_ids' ),
			'simple-login',
			'simple_login_section'
		); */
	}

	/**
	 * Sanitize and validate input
	 */
	public function sanitize( $input ) {
		$output = array();

		$output['replace_wp_login'] = ! empty( $input['replace_wp_login'] ) ? 1 : 0;
		$output['use_gateman_css']      = ! empty( $input['use_gateman_css'] ) ? 1 : 0;

		if ( ! empty( $input['agreement_page_ids'] ) ) {
			$ids                          = array_filter( array_map( 'intval', explode( ',', $input['agreement_page_ids'] ) ) );
			$output['agreement_page_ids'] = implode( ',', $ids );
		} else {
			$output['agreement_page_ids'] = '';
		}

		return $output;
	}

	/**
	 * Render checkbox field
	 */
	public function field_replace_wp_login() {
		echo '<label>
			<input 
				type="checkbox" 
				name="' . esc_attr( Settings::OPTION_NAME ) . '[replace_wp_login]" value="1" 
				' . checked( true, ! empty( Settings::getOption( 'replace_wp_login' ) ), false ) . '
			> Enable
		</label>';
	}

	/**
	 * Render checkbox field
	 */
	public function field_use_gateman_css() {
		echo '<label>
			<input 
				type="checkbox" 
				name="' . esc_attr( Settings::OPTION_NAME ) . '[use_gateman_css]" value="1" 
				' . checked( true, Settings::getOption( 'use_gateman_css', true ), false ) . '
			> Enable
		</label>';
	}

	/**
	 * Render text field for page IDs
	 */
	public function field_agreement_page_ids() {
		echo '<input type="text" name="' . esc_attr( Settings::OPTION_NAME ) . '[agreement_page_ids]" value="' . esc_attr( Settings::getOption( 'agreement_page_ids', '' ) ) . '" class="regular-text">';
	}

	/**
	 * Render the settings page
	 */
	public function renderPage() {
		?>
		<div class="wrap">
			<h1>Simple Login Settings</h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'simple_login_group' );
				do_settings_sections( 'simple-login' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Get setting value helper
	 */
	public static function get_option( $key = null, $default = false ) {
		$options = get_option( 'simple_login_settings', array() );
		if ( $key === null ) {
			return $options;
		}
		return isset( $options[ $key ] ) ? $options[ $key ] : $default;
	}

	/**
	 * Update setting value helper
	 */
	public static function update_option( $key, $value ) {
		$options         = get_option( 'simple_login_settings', array() );
		$options[ $key ] = $value;
		update_option( 'simple_login_settings', $options );
	}
}
