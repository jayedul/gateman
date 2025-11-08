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

	public static function getUniqueUsername( $username ) {
		return $username;
	}

	public static function applyAction( string $form, array $args = array() ) {
		
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

			case 'registration' :

				$first_name = ucwords( strtolower( sanitize_text_field( $_POST['first_name'] ?? '' ) ) );
				$last_name  = ucwords( strtolower( sanitize_text_field( $_POST['first_name'] ?? '' ) ) );

				// Prepare registration data
				$form = array(
					'first_name'   => $first_name,
					'last_name'    => $last_name,
					'display_name' => $first_name . ' ' . $last_name,
					'user_login'   => self::getUniqueUsername( sanitize_text_field( $_POST['username'] ) ),
					'user_email'   => sanitize_text_field( $_POST['email'], '' ),
					'user_pass'    => $_POST['password'] ?? '',
					'role'         => ! empty( $args['role'] ) ? $args['role'] : 'subscriber',
				);

				// Check common data existence
				if ( 
					empty( $form['first_name'] ) || 
					empty( $form['last_name'] ) || 
					empty( $form['user_login'] ) || 
					empty( $_POST['password'] ) || 
					$_POST['password'] !== ( $_POST['retype_password'] ?? '' )
				) {
					return array(
						'type' => 'error',
						'message' => __( 'All fields are required', 'slr' )
					);
				}

				// Prepare the display name. 
				$user_id = wp_insert_user( apply_filters( 'slr_register_user_data', $form ) );
				if ( is_wp_error( $user_id ) ) {
					return array(
						'type' => 'error',
						'message' => $user_id->get_error_message()
					);
				}
				
				// Login now after register
				$creds = array( 
					'user_login' => $form['user_login'],
					'password' => $form['password']
				);
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