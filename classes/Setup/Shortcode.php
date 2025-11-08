<?php

namespace DevJK\SLR\Setup;

use DevJK\SLR\Main;
use DevJK\SLR\Models\Logon;
use DevJK\WPToolkit\_Array;

class Shortcode {

	const LOGIN = 'slr_login';
	const REGISTRATION = 'slr_registration';
	const RECOVER_PASSWORD = 'slr_recover_password';
	const RESET_PASSWORD = 'slr_reset_password';

	public function __construct() {
		add_shortcode( self::LOGIN, array( $this, 'login' ) );
		add_shortcode( self::REGISTRATION, array( $this, 'registration' ) );
		add_shortcode( self::RECOVER_PASSWORD, array( $this, 'recoverPassword' ) );
		add_shortcode( self::RESET_PASSWORD, array( $this, 'resetPassword' ) );
	}

	public function login():string {
		return $this->render( self::LOGIN );
	}
	
	public function registration( $attrs = null ):string {
		return $this->render( self::REGISTRATION, $attrs );
	}
	
	public function recoverPassword():string {
		return $this->render( self::RECOVER_PASSWORD );
	}
	
	public function resetPassword():string {
		return $this->render( self::RESET_PASSWORD );
	}

	private function render( string $current_form, $args = null ):string {

		$atts = _Array::getArray( $args );
		
		$current_form = str_replace( 'slr_', '', $current_form );

		if ( sanitize_text_field( $_POST['slr_form_submit'] ?? '' ) === 'yes' ) {
			
			$resp = Logon::applyAction( $current_form, $atts );

			// Empty means 
			if ( $resp['action'] === 'error' ) {
				$error_message = ! empty( $resp['message'] ) ? $resp['message'] : __( 'Something went wrong!', 'slr' );
				
			} else if ( $resp['action'] === 'redirect' ) {
				$to = ! empty( $resp['to'] ) ? $resp['to'] : sanitize_text_field( $_GET['redirect_to'] ?? get_home_url() );
				wp_safe_redirect( $to );
				exit;
			}
		}

		ob_start();
		include Main::$configs->dir . 'templates/logon-form.php';
		return ob_get_clean();
	}
}