<?php
/**
 * Plugin Name: Simple Login & Registration
 * Plugin URI: https://wordpress.org/plugins/simple-login-registration/
 * Description: A minimal, secure login and registration plugin without unnecessary bloat.
 * Version: 1.0.0
 * Author: DevJK
 * Author URI: https://devjk.com
 * Text Domain: slr
 * Requires at least: 6.7
 * Requires PHP: 7.4
 *
 * @package devjk/simple-login-registration
 */

use DevJK\SLR\Main;

defined( 'ABSPATH' ) || exit;

include __DIR__ . '/vendor/autoload.php';

( new Main() )->init(
	(object) array(
		'file'        => __FILE__,
		'mode'        => 'development', // Auto converts to production in build file.
		'root_menu'   => 'slr',
		'db_prefix'   => 'slr_',
		'current_url' => ( is_ssl() ? 'https' : 'http' ) . '://' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ?? '' ) ) . sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) ),
	)
);
