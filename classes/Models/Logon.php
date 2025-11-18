<?php

namespace DevJK\Gateman\Models;

use DevJK\Gateman\Enums\Pages;
use DevJK\Gateman\Setup\Shortcode;
use DevJK\WPToolkit\_Array;
use DevJK\WPToolkit\_String;

class Logon {


	/**
	 * Get form fields by form type
	 *
	 * @param  string $form
	 * @return array
	 */
	public static function getFields( string $form = '' ):array {

		static $fields = null;

		if ( $fields === null ) {
			$fields = include dirname( __DIR__ ) . '/Helpers/Fields.php';
			$fields = _Array::getArray( $fields );
		}

		return apply_filters( 'gateman_form_fields', ( ! empty( $form ) ? _Array::getArray( $fields[ $form ] ?? array() ) : $fields ), $form );
	}

	public static function getUniqueUsername( $username ) {
		return $username;
	}

	public static function makeLogin( string $user_login, string $user_pass, bool $remember = false ):array {

		$creds = array(
			'user_login'    => $user_login,
			'user_password' => $user_pass,
			'remember'      => $remember,
		);

		$user     = wp_signon( $creds, false );
		$is_error = is_wp_error( $user );

		return array(
			'type'    => $is_error ? 'error' : 'redirect',
			'message' => $is_error ? $user->get_error_message() : null,
		);
	}

	public static function applyAction( Pages $form, array $args = array() ) {

		switch ( $form->value ) {

			// Make login
			case Pages::LOGIN->value:
				return self::makeLogin(
					sanitize_text_field( Shortcode::$input['username'] ?? '' ),
					( Shortcode::$input['password'] ?? '' ),
					sanitize_text_field( Shortcode::$input['remember'] ?? '' ) === 'yes'
				);
			break;

			// Make new use rregistration
			case Pages::REGISTRATION->value:
				if ( empty( get_option( 'users_can_register' ) ) ) {
					return array(
						'type'    => 'error',
						'message' => __( 'Registration is disabled', 'gateman' ),
					);
				}

				$first_name = ucwords( strtolower( sanitize_text_field( Shortcode::$input['first_name'] ?? '' ) ) );
				$last_name  = ucwords( strtolower( sanitize_text_field( Shortcode::$input['first_name'] ?? '' ) ) );

				// Prepare registration data
				$form = array(
					'first_name'   => $first_name,
					'last_name'    => $last_name,
					'display_name' => $first_name . ' ' . $last_name,
					'user_login'   => self::getUniqueUsername( sanitize_text_field( Shortcode::$input['username'] ) ),
					'user_email'   => sanitize_text_field( Shortcode::$input['email'], '' ),
					'user_pass'    => Shortcode::$input['password'] ?? '',
					'role'         => ! empty( $args['role'] ) ? $args['role'] : 'subscriber',
				);

				// Check common data existence
				if ( empty( $form['first_name'] )
				|| empty( $form['last_name'] )
				|| empty( $form['user_login'] )
				|| empty( Shortcode::$input['password'] )
				|| Shortcode::$input['password'] !== ( Shortcode::$input['retype_password'] ?? '' )
				) {
					return array(
						'type'    => 'error',
						'message' => __( 'All fields are required', 'gateman' ),
					);
				}

				// Prepare the display name.
				$user_id = wp_insert_user( apply_filters( 'gateman_register_user_data', $form ) );
				if ( is_wp_error( $user_id ) ) {
					return array(
						'type'    => 'error',
						'message' => $user_id->get_error_message(),
					);
				}

				return self::makeLogin( $form['user_login'], $form['user_pass'] );

				break;

			// Send OTP code to reset password
			case Pages::RECOVER_PASSWORD->value:
				$email        = sanitize_text_field( Shortcode::$input['email'] ?? '' );
				$md5_mail     = md5( $email );
				$rate_limit   = new RateLimit( 'otp-' . $md5_mail, 60, 2 );
				$rate_limited = $rate_limit->limit( true ) === true;

				$rate_limit->log();

				// Prevent repetitive OTP request for single email per minute
				if ( $rate_limited || ! email_exists( $email ) ) {
					return array(
						'type'    => 'error',
						'message' => $rate_limited ? __( 'Too many attempt', 'gateman' ) : __( 'User does not exist', 'gateman' ),
					);
				}

				// Generate the OTP, store and send to mail
				$otp = str_shuffle( strtoupper( substr( _String::getRandomString( '', '' ), 1, 8 ) ) );
				set_transient( 'gateman_otp_' . $md5_mail, $otp, 60 * 30 );

				wp_mail(
					$email,
					// translators: 1: Site title
					apply_filters( 'gateman_otp_mail_subject', sprintf( __( 'Password Reset OTP Code | %s', 'gateman' ), get_bloginfo( 'name' ) ), $email ),
					// translators: 1: OTP code
					apply_filters( 'gateman_otp_mail_body', sprintf( __( 'Your OTP code is %s', 'gateman' ), $otp ), $otp )
				);

				return array(
					'type' => 'redirect',
					'to'   => add_query_arg( array( 'recovery_email' => $email ), Settings::getPagePermalink( Pages::RECOVER_PASSWORD ) ),
				);
				break;

			// Reset password with OTP code and email.
			case Pages::RESET_PASSWORD->value:
				$otp          = sanitize_text_field( Shortcode::$input['otp_code'] ?? '' );
				$password     = Shortcode::$input['password'] ?? '';
				$ret_pass     = Shortcode::$input['retype_password'] ?? '';
				$email        = sanitize_text_field( Shortcode::$input['recovery_email'] ?? '' );
				$md5_mail     = md5( $email );
				$rate_limit   = new RateLimit( 'otp-verify-' . $md5_mail, 60 * 2, 3 );
				$rate_limited = $rate_limit->limit( true ) === true;
				$trans_code   = get_transient( 'gateman_otp_' . $md5_mail );

				$rate_limit->log();

				if ( $rate_limited
				|| empty( $otp )
				|| empty( $email )
				|| empty( $password )
				|| $password !== $ret_pass
				|| $trans_code !== $otp
				|| ! email_exists( $email )
				) {
					return array(
						'type'    => 'error',
						'message' => $rate_limited ? __( 'Too many attempts', 'gateman' ) : __( 'Invalid submission or session expired', 'gateman' ),
					);
				}

				$user = get_user_by( 'email', $email );
				wp_set_password( $password, $user->ID );

				return self::makeLogin( $user->user_login, $password );

				break;

			default:
				return array(
					'type'    => 'error',
					'message' => __( 'Invalid action', 'gateman' ),
				);
				break;
		}
	}
}
