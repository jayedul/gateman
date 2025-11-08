<?php
/**
 * Init SLR plugin
 */
namespace DevJK\SLR;

use DevJK\SLR\Setup\Installer;
use DevJK\SLR\Setup\PageFlag;
use DevJK\SLR\Setup\Redirect;
use DevJK\SLR\Setup\Scripts;
use DevJK\SLR\Setup\SettingsPage;
use DevJK\SLR\Setup\Shortcode;
use DevJK\WPToolkit\_Array;
use DevJK\WPToolkit\Dispatcher;
use DevJK\WPToolkit\Utilities;

/**
 * Main entrypoint class
 */
class Main {

	const ACT_HOOK = 'slr_plugin_activated';
	const DB_HOOK  = 'slr_plugin_db_deployed';

	/**
	 * Static data to reuse across Tutor Studio plugin
	 *
	 * @var object
	 */
	public static $configs;

	/**
	 * Register autoloader in constructor
	 */
	public function __construct() {

	}

	/**
	 * Initialize the plugin
	 *
	 * @param  object $configs Runtime config object
	 *
	 * @return void
	 */
	public function init( object $configs ) {

		self::$configs = $configs;

		// Prepare runtime data
		$manifest                        = _Array::getManifestArray( self::$configs->file, ARRAY_A );
		self::$configs                   = (object) array_merge( $manifest, (array) self::$configs );
		self::$configs->app_id           = Utilities::getAppId( trailingslashit( get_home_url() ) . 'wp-content/plugins/simple-login-registration/' );
		self::$configs->activation_hook  = self::ACT_HOOK;
		self::$configs->db_deployed_hook = self::DB_HOOK;

		// Register Activation/Deactivation Hook
		register_activation_hook( self::$configs->file, array( $this, 'activate' ) );

		new Installer();
		new Scripts();
		new Shortcode();
		new Redirect();
		new SettingsPage();
		new PageFlag();

		// Register ajax endpoints
		new Dispatcher(
			self::$configs->app_id,
			array()
		);
	}

	/**
	 * Execute activation hook
	 *
	 * @return void
	 */
	public static function activate() {
		do_action( self::ACT_HOOK );
	}
}
