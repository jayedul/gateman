<?php

namespace DevJK\SLR\Setup;

use DevJK\SLR\Enums\Pages;
use DevJK\SLR\Models\Settings;

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
				$post_states['slr_login_page'] = __( 'SLR Login', 'slr' );
				break;

			case Pages::REGISTRATION->value:
				$post_states['slr_reg_page'] = __( 'SLR Registration', 'slr' );
				break;

			case Pages::RECOVER_PASSWORD->value:
				$post_states['slr_recvr_page'] = __( 'SLR Recover Pass', 'slr' );
				break;
		}

		return $post_states;
	}
}
