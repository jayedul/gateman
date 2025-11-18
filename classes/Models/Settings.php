<?php

namespace DevJK\Gateman\Models;

use DevJK\Gateman\Enums\Pages;
use DevJK\Gateman\Setup\Shortcode;
use DevJK\WPToolkit\_Array;

class Settings {


	const PAGE_TYPE   = 'gateman_page_type';
	const OPTION_NAME = 'gateman_plugin_settings';

	/**
	 * Get Gateman option value
	 *
	 * @param string $key
	 * @param mixed  $def
	 * @return mixed
	 */
	public static function getOption( string $key, $def = null ) {
		$options = get_option( self::OPTION_NAME );
		return $options[ $key ] ?? $def;
	}

	/**
	 * Get Gateman page ID. It creates page if not found.
	 *
	 * @param Pages $page
	 * @return integer
	 */
	public static function getPageID( Pages $page ):int {

		$pages = get_posts(
			array(
				'post_type'   => 'page',
				'meta_key'    => self::PAGE_TYPE,
				'meta_value'  => $page->value,
				'fields'      => 'ids',
				'numberposts' => 1,
			)
		);

		$page_id   = ! empty( $pages ) ? $pages[0] : 0;
		$role      = $page->value === Pages::REGISTRATION ? ' role="subscriber"' : '';
		$shortcode = "[{$page->value}{$role}]";

		if ( empty( $page_id ) ) {

			$page_id = wp_insert_post(
				array(
					'post_title'   => ucwords( str_replace( '_', ' ', str_replace( 'gateman_', '', $page->value ) ) ),
					'post_content' => $shortcode,
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
					'post_content' => $the_content . "<div>{$shortcode}</div>",
				)
			);
		}

		return $page_id;
	}

	/**
	 * Get page permalink for Gateman
	 *
	 * @param Pages $page
	 * @return string
	 */
	public static function getPagePermalink( Pages $page ):string {

		$page_id = self::getPageID( $page );

		$args = array();

		if ( ! empty( Shortcode::$input['redirect_to'] ) ) {
			$args['redirect_to'] = Shortcode::$input['redirect_to'];
		}

		if ( ! empty( Shortcode::$input['reauth'] ) ) {
			$args['reauth'] = Shortcode::$input['reauth'];
		}

		return add_query_arg( apply_filters( 'gateman_permalink_args', $args ), get_permalink( $page_id ) );
	}

	/**
	 * Get args as array from shortcode
	 *
	 * @param Pages $page
	 * @return array
	 */
	public static function getShortcodeAtts( Pages $page ):array {

		$page_id = self::getPageID( $page );
		$content = get_post_field( 'post_content', $page_id );

		if ( ! has_shortcode( $content, $page->value ) ) {
			return array();
		}

		$pattern = get_shortcode_regex( array( $page->value ) );

		if ( preg_match_all( '/' . $pattern . '/s', $content, $matches, PREG_SET_ORDER ) ) {
			foreach ( $matches as $shortcode ) {
				if ( $page->value === $shortcode[2] ) {
					return _Array::getArray( shortcode_parse_atts( $shortcode[3] ) );
				}
			}
		}

		return array();
	}
}
