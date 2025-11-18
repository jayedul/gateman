<?php
/**
 * Init Gateman plugin
 */
namespace DevJK\Gateman;

use DevJK\Gateman\Setup\Installer;
use DevJK\Gateman\Setup\PageFlag;
use DevJK\Gateman\Setup\Redirect;
use DevJK\Gateman\Setup\Scripts;
use DevJK\Gateman\Setup\SettingsPage;
use DevJK\Gateman\Setup\Shortcode;
use DevJK\WPToolkit\_Array;
use DevJK\WPToolkit\Utilities;

/**
 * Main entrypoint class
 */
class Main {

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
		$manifest              = _Array::getManifestArray( self::$configs->file, ARRAY_A );
		self::$configs         = (object) array_merge( $manifest, (array) self::$configs );
		self::$configs->app_id = Utilities::getAppId( trailingslashit( get_home_url() ) . 'wp-content/plugins/gateman/' );

		// Register Activation/Deactivation Hook
		register_activation_hook( self::$configs->file, array( $this, 'activate' ) );

		new Installer();
		new Scripts();
		new Shortcode();
		new Redirect();
		new SettingsPage();
		new PageFlag();
	}

	/**
	 * Execute activation hook
	 *
	 * @return void
	 */
	public static function activate() {
		do_action( 'gateman_plugin_activated' );
	}
}
