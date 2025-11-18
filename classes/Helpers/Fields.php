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
				'label'       => __( 'Username or Email', 'gateman' ),
				'placeholder' => __( 'Enter your username or email', 'gateman' ),
				'type'        => 'text',
			),
			'password' => array(
				'label'       => __( 'Password', 'gateman' ),
				'placeholder' => __( 'Enter your password', 'gateman' ),
				'type'        => 'password',
			),
		),
		'submit' => array(
			'label' => __( 'Login', 'gateman' ),
		),
	),
	'registration'     => array(
		'fields' => array(
			'first_name'      => array(
				'label'       => __( 'First Name', 'gateman' ),
				'placeholder' => __( 'Enter your first name', 'gateman' ),
				'type'        => 'text',
			),
			'last_name'       => array(
				'label'       => __( 'Last Name', 'gateman' ),
				'placeholder' => __( 'Enter your last name', 'gateman' ),
				'type'        => 'text',
			),
			'username'        => array(
				'label'       => __( 'Username', 'gateman' ),
				'placeholder' => __( 'Enter your username', 'gateman' ),
				'type'        => 'text',
			),
			'email'           => array(
				'label'       => __( 'Email', 'gateman' ),
				'placeholder' => __( 'Enter your email', 'gateman' ),
				'type'        => 'email',
			),
			'password'        => array(
				'label'       => __( 'Password', 'gateman' ),
				'placeholder' => __( 'Enter your password', 'gateman' ),
				'type'        => 'password',
			),
			'retype_password' => array(
				'label'       => __( 'Re-type Password', 'gateman' ),
				'placeholder' => __( 'Enter your password again', 'gateman' ),
				'type'        => 'password',
			),
		),
		'submit' => array(
			'label' => __( 'Register', 'gateman' ),
		),
	),
	'recover_password' => array(
		'fields' => array(
			'email' => array(
				'label'       => __( 'Email', 'gateman' ),
				'placeholder' => __( 'Enter your account email', 'gateman' ),
				'type'        => 'text',
			),
		),
		'submit' => array(
			'label' => __( 'Send OTP code', 'gateman' ),
		),
	),
	'reset_password'   => array(
		'fields' => array(
			'recovery_email'  => array(
				'label'       => __( 'Email', 'gateman' ),
				'placeholder' => __( 'Enter your email', 'gateman' ),
				'type'        => 'email',
				'disabled'    => true,
			),
			'otp_code'        => array(
				'label'       => __( 'OTP Code', 'gateman' ),
				'placeholder' => __( 'Enter OTP code sent to email', 'gateman' ),
				'type'        => 'text',
			),
			'password'        => array(
				'label'       => __( 'New Password', 'gateman' ),
				'placeholder' => __( 'Enter new password', 'gateman' ),
				'type'        => 'password',
			),
			'retype_password' => array(
				'label'       => __( 'Re-type New Password', 'gateman' ),
				'placeholder' => __( 'Re-type new password', 'gateman' ),
				'type'        => 'password',
			),
		),
		'submit' => array(
			'label' => __( 'Reset Password', 'gateman' ),
		),
	),
);
