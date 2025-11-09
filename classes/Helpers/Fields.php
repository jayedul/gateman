<?php
/**
 * fields.php
 *
 * @package devjk/slr
 */

return array(
	'login'            => array(
		'fields' => array(
			'username' => array(
				'label'       => __( 'Username or Email', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your username or email', 'simple-login-registration' ),
				'type'        => 'text',
			),
			'password' => array(
				'label'       => __( 'Password', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your password', 'simple-login-registration' ),
				'type'        => 'password',
			),
		),
		'submit' => array(
			'label' => __( 'Login', 'simple-login-registration' ),
		),
	),
	'registration'     => array(
		'fields' => array(
			'first_name'      => array(
				'label'       => __( 'First Name', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your first name', 'simple-login-registration' ),
				'type'        => 'text',
			),
			'last_name'       => array(
				'label'       => __( 'Last Name', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your last name', 'simple-login-registration' ),
				'type'        => 'text',
			),
			'username'        => array(
				'label'       => __( 'Username', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your username', 'simple-login-registration' ),
				'type'        => 'text',
			),
			'email'           => array(
				'label'       => __( 'Email', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your email', 'simple-login-registration' ),
				'type'        => 'email',
			),
			'password'        => array(
				'label'       => __( 'Password', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your password', 'simple-login-registration' ),
				'type'        => 'password',
			),
			'retype_password' => array(
				'label'       => __( 'Re-type Password', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your password again', 'simple-login-registration' ),
				'type'        => 'password',
			),
		),
		'submit' => array(
			'label' => __( 'Register', 'simple-login-registration' ),
		),
	),
	'recover_password' => array(
		'fields' => array(
			'email' => array(
				'label'       => __( 'Email', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your account email', 'simple-login-registration' ),
				'type'        => 'text',
			),
		),
		'submit' => array(
			'label' => __( 'Send OTP code', 'simple-login-registration' ),
		),
	),
	'reset_password'   => array(
		'fields' => array(
			'recovery_email'  => array(
				'label'       => __( 'Email', 'simple-login-registration' ),
				'placeholder' => __( 'Enter your email', 'simple-login-registration' ),
				'type'        => 'email',
				'disabled'    => true,
			),
			'otp_code'        => array(
				'label'       => __( 'OTP Code', 'simple-login-registration' ),
				'placeholder' => __( 'Enter OTP code sent to email', 'simple-login-registration' ),
				'type'        => 'text',
			),
			'password'        => array(
				'label'       => __( 'New Password', 'simple-login-registration' ),
				'placeholder' => __( 'Enter new password', 'simple-login-registration' ),
				'type'        => 'password',
			),
			'retype_password' => array(
				'label'       => __( 'Re-type New Password', 'simple-login-registration' ),
				'placeholder' => __( 'Re-type new password', 'simple-login-registration' ),
				'type'        => 'password',
			),
		),
		'submit' => array(
			'label' => __( 'Reset Password', 'simple-login-registration' ),
		),
	),
);
