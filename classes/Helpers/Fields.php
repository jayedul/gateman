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
				'label'       => __( 'Username or Email', 'slr' ),
				'placeholder' => __( 'Enter your username or email', 'slr' ),
				'type'        => 'text',
			),
			'password' => array(
				'label'       => __( 'Password', 'slr' ),
				'placeholder' => __( 'Enter your password', 'slr' ),
				'type'        => 'password',
			),
		),
		'submit' => array(
			'label' => __( 'Login', 'slr' ),
		),
	),
	'registration'     => array(
		'fields' => array(
			'first_name'      => array(
				'label'       => __( 'First Name', 'slr' ),
				'placeholder' => __( 'Enter your first name', 'slr' ),
				'type'        => 'text',
			),
			'last_name'       => array(
				'label'       => __( 'Last Name', 'slr' ),
				'placeholder' => __( 'Enter your last name', 'slr' ),
				'type'        => 'text',
			),
			'username'        => array(
				'label'       => __( 'Username', 'slr' ),
				'placeholder' => __( 'Enter your username', 'slr' ),
				'type'        => 'text',
			),
			'email'           => array(
				'label'       => __( 'Email', 'slr' ),
				'placeholder' => __( 'Enter your email', 'slr' ),
				'type'        => 'email',
			),
			'password'        => array(
				'label'       => __( 'Password', 'slr' ),
				'placeholder' => __( 'Enter your password', 'slr' ),
				'type'        => 'password',
			),
			'retype_password' => array(
				'label'       => __( 'Re-type Password', 'slr' ),
				'placeholder' => __( 'Enter your password again', 'slr' ),
				'type'        => 'password',
			),
		),
		'submit' => array(
			'label' => __( 'Register', 'slr' ),
		),
	),
	'recover_password' => array(
		'fields' => array(
			'email' => array(
				'label'       => __( 'Email', 'slr' ),
				'placeholder' => __( 'Enter your account email', 'slr' ),
				'type'        => 'text',
			),
		),
		'submit' => array(
			'label' => __( 'Send OTP code', 'slr' ),
		),
	),
	'reset_password'   => array(
		'fields' => array(
			'recovery_email'           => array(
				'label'       => __( 'Email', 'slr' ),
				'placeholder' => __( 'Enter your email', 'slr' ),
				'type'        => 'email',
				'disabled'    => true,
			),
			'otp_code'        => array(
				'label'       => __( 'OTP Code', 'slr' ),
				'placeholder' => __( 'Enter OTP code sent to email', 'slr' ),
				'type'        => 'text',
			),
			'password'        => array(
				'label'       => __( 'New Password', 'slr' ),
				'placeholder' => __( 'Enter new password', 'slr' ),
				'type'        => 'password',
			),
			'retype_password' => array(
				'label'       => __( 'Re-type New Password', 'slr' ),
				'placeholder' => __( 'Re-type new password', 'slr' ),
				'type'        => 'password',
			),
		),
		'submit' => array(
			'label' => __( 'Reset Password', 'slr' ),
		),
	),
);
