<?php

namespace DevJK\SLR\Models;

use DevJK\WPToolkit\_Array;

class Logon {

	/**
	 * Get form fields by form type
	 *
	 * @param string $form
	 * @return array
	 */
	public static function getFields( string $form ):array {

		static $fields = null;

		if ( $fields === null ) {
			$fields = include dirname( __DIR__ ) . '/Helpers/Fields.php';
			$fields = _Array::getArray( $fields );
		}

		return $fields[ $form ] ?? array();
	}

	public static function applyAction( string $form ) {
		
		switch ( $form ) {
			
			case 'login' :
				
				$creds = array();
				$creds['user_login']    = sanitize_text_field( $_POST['username'] ?? '' );
				$creds['user_password'] = $_POST['password'];
				$creds['remember']      = sanitize_text_field( $_POST['remember'] ?? '' ) === 'yes';

				$user = wp_signon( $creds, false );
				$is_error = is_wp_error( $user );

				return array(
					'action' => $is_error ? 'error' : 'redirect',
					'message' => $user->get_error_message()
				);

				break;
		}
	}
}