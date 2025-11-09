<?php

namespace DevJK\SLR\Setup;

use DevJK\SLR\Enums\Pages;
use DevJK\SLR\Main;
use DevJK\SLR\Models\Logon;
use DevJK\SLR\Models\Settings;

class Shortcode {

	/**
	 * Error message from action
	 *
	 * @var string
	 */
	private $error_message;

	/**
	 * Post or get data
	 *
	 * @var array
	 */
	public static $input = array();

	public function __construct() {
		add_shortcode( Pages::LOGIN->value, array( $this, 'login' ) );
		add_shortcode( Pages::REGISTRATION->value, array( $this, 'registration' ) );
		add_shortcode( Pages::RECOVER_PASSWORD->value, array( $this, 'recoverPassword' ) );
		add_action( 'init', array( $this, 'processSubmit' ) );
	}

	public function login():string {
		return $this->render( Pages::LOGIN );
	}

	public function registration():string {
		return $this->render( Pages::REGISTRATION );
	}

	public function recoverPassword():string {
		return $this->render( Pages::RECOVER_PASSWORD );
	}

	public function processSubmit() {

		if ( ! isset( $_POST['slr_form_submit'], $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ) ) {
			return;
		}

		$_fields = Logon::getFields();
		foreach ( $_fields as $form => $fields ) {
			foreach ( $fields['fields'] as $name => $field ) {
				self::$input[ $name ] = ( $field['type'] ?? '' ) === 'password' ? ( $_POST[ $name ] ?? '' ) : sanitize_text_field( wp_unslash( $_POST[ $name ] ?? $_GET[ $name ] ?? '' ) );
			}
		}

		$current_form = Pages::from( sanitize_text_field( wp_unslash( $_POST['slr_form_submit'] ) ) );
		$current_form = ( $current_form === Pages::RECOVER_PASSWORD && ! empty( self::$input['recovery_email'] ?? '' ) ) ? Pages::RESET_PASSWORD : $current_form;
		$atts         = Settings::getShortcodeAtts( $current_form );
		$resp         = Logon::applyAction( $current_form, $atts );

		// Empty means
		if ( $resp['type'] === 'error' ) {
			$this->error_message = ! empty( $resp['message'] ) ? $resp['message'] : __( 'Something went wrong!', 'simple-login-registration' );

		} elseif ( $resp['type'] === 'redirect' ) {
			$to = ! empty( $resp['to'] ) ? $resp['to'] : ( ! empty( self::$input['redirect_to'] ) ? sanitize_text_field( self::$input['redirect_to'] ) : get_home_url() );
			wp_safe_redirect( $to );
			exit;
		}
	}

	private function render( Pages $form ):string {

		// Prepare variables for template html file

		$error_message = $this->error_message;
		$current_page  = $form->value;
		$input_data    = self::$input;
		$current_page  = ( $form === Pages::RECOVER_PASSWORD && ! empty( sanitize_text_field( self::$input['recovery_email'] ?? '' ) ) ) ? Pages::RESET_PASSWORD->value : $current_page;
		$field_data    = Logon::getFields( str_replace( 'slr_', '', $current_page ) );
		$fields        = $field_data['fields'] ?? array();
		$submit        = $field_data['submit'] ?? array();
		$reg_enabled   = ! empty( get_option( 'users_can_register' ) );

		ob_start();
		include Main::$configs->dir . 'templates/logon-form.php';
		return ob_get_clean();
	}
}
