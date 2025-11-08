<?php

namespace DevJK\SLR\Models;

use SolidieLib\_Array;

class RateLimit {


	private $event;
	private $validity;
	private $max_attempt;

	/**
	 * Initialize rate limiter
	 *
	 * @param string  $event    event name to limit
	 * @param integer $validity In seconds
	 */
	public function __construct( string $event, $validity = 3600, $max_attempt = 3 ) {
		$this->event       = 'slr-rate-limit-' . $event;
		$this->validity    = $validity;
		$this->max_attempt = $max_attempt;
	}

	public function limit( $ret = false ) {

		$trans = _Array::getArray( get_transient( $this->event ) );

		if ( count( $trans ) >= $this->max_attempt && $trans[0] > time() - $this->validity ) {
			if ( $ret === true ) {
				return true;
			}
			wp_send_json_error( array( 'message' => __( 'Too many invalid attempt encountered. Try later.', 'tutor-studio' ) ) );
		}
	}

	public function log() {
		$trans   = _Array::getArray( get_transient( $this->event ) );
		$trans   = array_slice( $trans, -( $this->max_attempt - 1 ) );
		$trans[] = time();
		set_transient( $this->event, $trans, $this->validity );
	}

	public function clear() {
		delete_transient( $this->event );
	}
}
