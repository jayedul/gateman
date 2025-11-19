<?php

namespace GatemanLogin\Setup;

use GatemanLogin\Enums\Pages;
use GatemanLogin\Models\Settings;

class PageFlag {

	public function __construct() {
		add_filter( 'display_post_states', array( $this, 'flag' ), 10, 2 );

	}

	public function flag( $post_states, $post ) {

		if ( $post->post_type !== 'page' ) {
			return $post_states;
		}

		$type = get_post_meta( $post->ID, Settings::PAGE_TYPE, true );

		switch ( $type ) {

			case Pages::LOGIN->value:
				$post_states['gateman_login_page'] = __( 'Gateman Login', 'gateman' );
				break;

			case Pages::REGISTRATION->value:
				$post_states['gateman_reg_page'] = __( 'Gateman Registration', 'gateman' );
				break;

			case Pages::RECOVER_PASSWORD->value:
				$post_states['gateman_recvr_page'] = __( 'Gateman Recover Pass', 'gateman' );
				break;
		}

		return $post_states;
	}
}
