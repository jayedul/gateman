<?php
/**
 * Plugin Name: Gateman - Simple Login Registration
 * Plugin URI: https://wordpress.org/plugins/gateman/
 * Description: A lightweight login and registration plugin designed without unnecessary bloat. Just the essential features you need for a smooth user experience.
 * Version: 1.0.1
 * Author: JK
 * Author URI: https://www.linkedin.com/in/jayedulk/
 * Requires at least: 5.3
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * Text Domain: gateman
 *
 * @package devjk/gateman
 */

use GatemanLogin\Main;

defined('ABSPATH') || exit;

require __DIR__ . '/vendor/autoload.php';

( new Main() )->init(
    (object) array(
        'file'        => __FILE__,
        'mode'        => 'development', // Auto converts to production in build file.
        'root_menu'   => 'gateman',
        'db_prefix'   => 'gateman_',
        'current_url' => ( is_ssl() ? 'https' : 'http' ) . '://' . sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'] ?? '')) . sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'] ?? '')),
    )
);
