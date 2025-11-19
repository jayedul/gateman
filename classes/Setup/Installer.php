<?php

namespace GatemanLogin\Setup;

use GatemanLogin\Enums\Pages;
use GatemanLogin\Models\Settings;

class Installer {


	public function __construct() {
		add_action( 'gateman_plugin_activated', array( $this, 'createPages' ) );
	}

	public function createPages() {
		Settings::getPagePermalink( Pages::LOGIN );
		Settings::getPagePermalink( Pages::REGISTRATION );
		Settings::getPagePermalink( Pages::RECOVER_PASSWORD );
	}
}
