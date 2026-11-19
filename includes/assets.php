<?php
/**
 * The theme's own scripts and styles.
 *
 * Admin, editor and login stylesheets are enqueued by Ydin; this file only covers
 * the front end bundle.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

defined( 'ABSPATH' ) || exit;

use Jcore\Ydin\WordPress\Assets;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts' );
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\editor_scripts' );

/**
 * Register and enqueue the theme's front end assets.
 *
 * @return void
 */
function scripts(): void {
	Assets::style_register( 'theme', '/dist/css/theme.css' );
	Assets::script_register( 'jcore', '/dist/js/jcore.js' );
	Assets::script_register( 'alpine', '/dist/js/alpine-jcore.js' );
	Assets::script_register( 'yoast-faq-accordion', '/dist/js/yoast-faq.js' );

	wp_enqueue_style( 'theme' );
	wp_enqueue_script( 'jcore' );

	// Alpine is only worth shipping when a block on the page asks for it.
	if ( apply_filters( 'jcore_load_alpine_script', false ) ) {
		wp_enqueue_script( 'alpine' );
	}

	// Turns the Yoast FAQ block into an accordion.
	if ( is_singular() && has_block( 'yoast/faq-block' ) ) {
		wp_enqueue_script( 'yoast-faq-accordion' );
	}
}

/**
 * The theme's block editor script (src/js/editor.ts).
 *
 * @return void
 */
function editor_scripts(): void {
	$file = '/dist/js/editor.js';
	wp_enqueue_script(
		'jcore-editor',
		get_theme_file_uri( $file ),
		array( 'wp-hooks', 'wp-compose', 'wp-data', 'wp-element', 'wp-block-editor' ),
		(string) filemtime( get_theme_file_path( $file ) ),
		true
	);
}
