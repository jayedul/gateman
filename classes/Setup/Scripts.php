<?php

namespace DevJK\Gateman\Setup;

use DevJK\Gateman\Main;

class Scripts {


	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'loadFormScripts' ) );
	}

	public function loadFormScripts() {
		wp_enqueue_style( 'gateman-logon-form', Main::$configs->url . 'assets/css/logon.css', array(), Main::$configs->version );
	}
}
