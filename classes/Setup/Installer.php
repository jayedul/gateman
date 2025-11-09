<?php

namespace DevJK\SLR\Setup;

use DevJK\SLR\Enums\Pages;
use DevJK\SLR\Main;
use DevJK\SLR\Models\Settings;

class Installer {


	public function __construct() {
		add_action( 'slr_plugin_activated', array( $this, 'createPages' ) );
	}

	public function createPages() {
		Settings::getPagePermalink( Pages::LOGIN );
		Settings::getPagePermalink( Pages::REGISTRATION );
		Settings::getPagePermalink( Pages::RECOVER_PASSWORD );
	}
}
