<?php
/**
 * Plugin Name: Simple Login Registration
 * Plugin URI: https://wordpress.org/plugins/simple-login-registration/
 * Description: Light weight login and registration plugin without unnecessary heavy fatures.
 * Version: 1.0.0
 * Author: JK
 * Author URI: https://www.linkedin.com/in/jayedulk/
 * Requires at least: 5.3
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * Text Domain: simple-login-registration
 *
 * @package devjk/slr
 */

use DevJK\SLR\Main;

defined('ABSPATH') || exit;

require __DIR__ . '/vendor/autoload.php';

( new Main() )->init(
    (object) array(
        'file'        => __FILE__,
        'mode'        => 'development', // Auto converts to production in build file.
        'root_menu'   => 'slr',
        'db_prefix'   => 'slr_',
        'current_url' => ( is_ssl() ? 'https' : 'http' ) . '://' . sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'] ?? '')) . sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'] ?? '')),
    )
);
