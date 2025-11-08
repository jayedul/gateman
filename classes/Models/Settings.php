<?php

namespace DevJK\SLR\Models;

use DevJK\SLR\Enums\Pages;
use DevJK\WPToolkit\_Array;

class Settings {


	const PAGE_TYPE   = 'slr_page_type';
	const OPTION_NAME = 'slr_plugin_settings';

	public static function getOption( string $key, $def = null ) {
		$options = get_option( self::OPTION_NAME );
		return $options[ $key ] ?? $def;
	}

	public static function getPagePermalink( Pages $page ) {

		$pages = get_posts(
			array(
				'post_type'   => 'page',
				'meta_key'    => self::PAGE_TYPE,
				'meta_value'  => $page->value,
				'fields'      => 'ids',
				'numberposts' => 1,
			)
		);

		$page_id = ! empty( $pages ) ? $pages[0] : 0;

		if ( empty( $page_id ) ) {

			$page_id = wp_insert_post(
				array(
					'post_title'   => ucwords( str_replace( '_', ' ', str_replace( 'slr_', '', $page->value ) ) ),
					'post_content' => '[' . $page->value . ']',
					'post_status'  => 'publish',
					'post_type'    => 'page',
				)
			);

			update_post_meta( $page_id, self::PAGE_TYPE, $page->value );
		}

		$the_content = get_post_field( 'post_content', $page_id );
		if ( ! has_shortcode( $the_content, $page->value ) ) {
			wp_update_post(
				array(
					'ID'           => $page_id,
					'post_content' => $the_content . '<div>[' . $page->value . ']</div>',
				)
			);
		}
		
		$args = array();
		
		if ( ! empty( $_GET['redirect_to'] ) ) {
			$args['redirect_to'] = $args['redirect_to'];
		}
		
		if ( ! empty( $_GET['reauth'] ) ) {
			$args['reauth'] = $args['reauth'];
		}

		return add_query_arg( $args, get_permalink( $page_id ) );
	}
}
