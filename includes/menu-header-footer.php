<?php
/**
 * JCore Footer Functions
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

defined( 'ABSPATH' ) || exit;

use Jcore\Ydin\WordPress\PostType;
use Timber\Timber;

add_action( 'after_setup_theme', 'Jcore\Ilme\register_menu' );

add_filter( 'body_class', 'Jcore\Ilme\add_page_slug_body_class' );

add_action( 'wp_body_open', 'Jcore\Ilme\custom_body_open' );
add_action( 'wp_head', 'Jcore\Ilme\custom_head' );



/**
 * Register Menus added by "jcore_menus" filter.
 *
 *  @return void
 */
function register_menu() {
	foreach ( apply_filters( 'jcore_menus', array() ) as $menu => $name ) {
		register_nav_menu( $menu, $name );
	}
}

/**
 * Add page slug to body class
 *
 * @param array $classes The body classes.
 *
 * @return mixed
 */
function add_page_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}

	return $classes;
}

/**
 * Custom body open.
 *
 * @return void
 */
function custom_body_open() {
	// TODO Fix.
	if ( Settings::get( 'keys', 'google_tag_manager' ) ) {
		Timber::render( 'partials/google-tag-manager-noscript.twig', array( 'tag_manager' => trim( Settings::get( 'keys', 'google_tag_manager' ) ) ) );
	}
}

/**
 * Custom head.
 *
 * @return void
 */
function custom_head() {
	// TODO custom head.
	if ( Settings::get( 'keys', 'google_tag_manager' ) ) {
		Timber::render( 'partials/google-tag-manager.twig', array( 'tag_manager' => trim( Settings::get( 'keys', 'google_tag_manager' ) ) ) );
	}
	if ( Settings::get( 'keys', 'google_analytics' ) ) {
		Timber::render( 'partials/google-analytics.twig', array( 'analytics' => trim( Settings::get( 'keys', 'google_analytics' ) ) ) );
	}
}
