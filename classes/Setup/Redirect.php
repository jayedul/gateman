<?php
/**
 * Manage URL redirects
 */
namespace DevJK\SLR\Setup;

use DevJK\SLR\Enums\Pages;
use DevJK\SLR\Models\Settings;

/**
 * Redirect class
 */
class Redirect {

	/**
	 * Register redirect hooks
	 */
	public function __construct() {
		add_filter( 'login_url', array( $this, 'overrideLoginURL' ), 10, 3 );
		add_filter( 'lostpassword_url', array( $this, 'overRideRecovreURL' ), 10, 2 );
		add_filter( 'register_url', array( $this, 'overrideRegURL' ), 10, 1 );
		add_action( 'init', array( $this, 'redirectWP' ) );
	}

	/**
	 * Override login URL
	 *
	 * @param string $login_url
	 * @param string $redirect
	 * @param string $force_reauth
	 * @return string
	 */
	public function overrideLoginURL( $login_url, $redirect, $force_reauth ) {

		if ( empty( Settings::getOption( 'replace_wp_login' ) ) ) {
			return $login_url;
		}

		$home = Settings::getPagePermalink( Pages::LOGIN );

		// If a redirect was provided, append it; otherwise do not append empty param
		if ( ! empty( $redirect ) ) {
			$home = add_query_arg( 'redirect_to', rawurlencode( $redirect ), $home );
		}

		// If force_reauth is set add that param (to mimic wp_login_url behavior)
		if ( $force_reauth ) {
			$home = add_query_arg( 'reauth', '1', $home );
		}

		return $home;
	}

	/**
	 * Override pass recovery URL
	 *
	 * @param string $lostpassword_url
	 * @param string $redirect
	 * @return string
	 */
	public function overRideRecovreURL( $lostpassword_url, $redirect ) {

		if ( empty( Settings::getOption( 'replace_wp_login' ) ) ) {
			return $lostpassword_url;
		}

		$url = Settings::getPagePermalink( Pages::RECOVER_PASSWORD );

		if ( ! empty( $redirect ) ) {
			$url = add_query_arg( 'redirect_to', rawurlencode( $redirect ), $url );
		}

		return $url;
	}

	/**
	 * Override register url
	 *
	 * @param string $register_url
	 * @return string
	 */
	public function overrideRegURL( $register_url ) {

		if ( empty( Settings::getOption( 'replace_wp_login' ) ) ) {
			return $register_url;
		}

		$url = Settings::getPagePermalink( Pages::REGISTRATION );

		// if original had a redirect_to param, preserve it
		$parsed = wp_parse_url( $register_url );
		if ( ! empty( $parsed['query'] ) ) {
			parse_str( $parsed['query'], $q );
			if ( ! empty( $q['redirect_to'] ) ) {
				$url = add_query_arg( 'redirect_to', $q['redirect_to'], $url );
			}
		}

		return $url;
	}

	/**
	 * Redirect loaded WP login pages
	 *
	 * @return void
	 */
	public function redirectWP() {

		if ( empty( Settings::getOption( 'replace_wp_login' ) ) ) {
			return;
		}

		// your custom login page slug
		$custom_slug = 'custom-login';

		// if current script is wp-login.php
		$current_script = basename( $_SERVER['PHP_SELF'] ?? '' );
		if ( $current_script !== 'wp-login.php' ) {
			return;
		}

		// Allow exceptions (like admin-post.php actions)
		$action = $_GET['action'] ?? 'login';
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		if ( $action === 'logout' ) {
			return;
		}

		// avoid redirect loop if already accessing custom slug through internal include
		if ( strpos( $_SERVER['REQUEST_URI'], "/$custom_slug" ) !== false ) {
			return;
		}

		// Build redirect URL to your custom slug
		$redirect = Settings::getPagePermalink( Pages::LOGIN );
		$query    = array();

		// Preserve relevant query params (action, redirect_to, reauth)
		foreach ( array( 'redirect_to', 'reauth' ) as $key ) {
			if ( ! empty( $_GET[ $key ] ) ) {
				$query[ $key ] = $_GET[ $key ];
			}
		}

		if ( ! empty( $query ) ) {
			$redirect = add_query_arg( $query, $redirect );
		}

		wp_safe_redirect( $redirect );
		exit;
	}
}
