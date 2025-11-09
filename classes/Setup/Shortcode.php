<?php

namespace DevJK\SLR\Setup;

use DevJK\SLR\Enums\Pages;
use DevJK\SLR\Main;
use DevJK\SLR\Models\Logon;
use DevJK\SLR\Models\Settings;
use DevJK\WPToolkit\_Array;

class Shortcode {

	private $error_message;
	private $current_form;

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
		
		if ( empty( sanitize_text_field( $_POST['slr_form_submit'] ?? '' ) ) || ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ) ) ) {
			return;
		}

		$current_form = Pages::from( $_POST['slr_form_submit'] );
		$current_form = ( $current_form === Pages::RECOVER_PASSWORD && ! empty( sanitize_text_field( $_GET['recovery_email'] ?? '' ) ) ) ? Pages::RESET_PASSWORD : $current_form;
		$atts         = Settings::getShortcodeAtts(  $current_form );
		$resp         = Logon::applyAction( $current_form, $atts );

		$this->current_form = $current_form;

		error_log( var_export( $resp, true ) );
		
		// Empty means
		if ( $resp['type'] === 'error' ) {
			$this->error_message = ! empty( $resp['message'] ) ? $resp['message'] : __( 'Something went wrong!', 'slr' );

		} elseif ( $resp['type'] === 'redirect' ) {
			$to = ! empty( $resp['to'] ) ? $resp['to'] : ( ! empty( $_GET['redirect_to'] ) ? sanitize_text_field( $_GET['redirect_to'] ) : get_home_url() );
			wp_safe_redirect( $to );
			exit;
		}
	}

	private function render( Pages $form ):string {

		$error_message = $this->error_message;
		$current_page = $form->value;
		$current_page = ( $form === Pages::RECOVER_PASSWORD && ! empty( sanitize_text_field( $_GET['recovery_email'] ?? '' ) ) ) ? Pages::RESET_PASSWORD->value : $current_page;

		ob_start();
		include Main::$configs->dir . 'templates/logon-form.php';
		return ob_get_clean();
	}
}
