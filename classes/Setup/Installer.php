<?php

namespace DevJK\Gateman\Setup;

use DevJK\Gateman\Enums\Pages;
use DevJK\Gateman\Main;
use DevJK\Gateman\Models\Settings;

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
