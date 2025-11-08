<?php

namespace DevJK\SLR\Setup;

use DevJK\SLR\Enums\Pages;
use DevJK\SLR\Main;
use DevJK\SLR\Models\Logon;
use DevJK\WPToolkit\_Array;

class Shortcode {


	public function __construct() {
		add_shortcode( Pages::LOGIN->value, array( $this, 'login' ) );
		add_shortcode( Pages::REGISTRATION->value, array( $this, 'registration' ) );
		add_shortcode( Pages::RECOVER_PASSWORD->value, array( $this, 'recoverPassword' ) );
	}

	public function login():string {
		return $this->render( Pages::LOGIN->value );
	}

	public function registration( $attrs = null ):string {
		return $this->render( Pages::REGISTRATION->value, $attrs );
	}

	public function recoverPassword():string {
		return $this->render( Pages::RECOVER_PASSWORD->value );
	}

	private function render( string $current_form, $args = null ):string {

		$atts = _Array::getArray( $args );

		$current_form = ( $current_form === Pages::RECOVER_PASSWORD->value && ! empty( sanitize_text_field( $_GET['email'] ) ) ) ? Pages::RESET_PASSWORD->value : $current_form;
		$current_form = str_replace( 'slr_', '', $current_form );

		if ( sanitize_text_field( $_POST['slr_form_submit'] ?? '' ) === 'yes' ) {

			$resp = Logon::applyAction( $current_form, $atts );

			// Empty means
			if ( $resp['type'] === 'error' ) {
				$error_message = ! empty( $resp['message'] ) ? $resp['message'] : __( 'Something went wrong!', 'slr' );

			} elseif ( $resp['type'] === 'redirect' ) {
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
