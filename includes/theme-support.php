<?php

/**
 * JCore Theme  Functions
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

use Jcore\Ydin\Blocks\Blocks;
use Jcore\Ydin\Settings\Customizer;
use Jcore\Ydin\WordPress\Assets;


add_action( 'after_setup_theme', 'Jcore\Ilme\setup' );
add_action( 'wp_enqueue_scripts', 'Jcore\Ilme\scripts' );

/**
 * Do most of the things needed for the theme.
 */
function setup() {
	// Theme Supports.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'dist/css/editor.css' );

	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-units' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'menus' );
	add_theme_support( 'title-tag' );

	$defaults = array(
		'height'               => 64,
		'width'                => 200,
		'flex-height'          => true,
		'flex-width'           => true,
		'header-text'          => array( 'site-title', 'site-description' ),
		'unlink-homepage-logo' => false,
	);
	add_theme_support( 'custom-logo', $defaults );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	// Adds excerpt to pages, normally for posts only.
	add_post_type_support( 'page', 'excerpt' );

	add_filter( 'gform_confirmation_anchor', '__return_true' );

	add_theme_support(
		'woocommerce',
		array(
			'product_grid'   => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'max_rows'        => 10,
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 4,
			),
			'product_blocks' => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'max_rows'        => 10,
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 4,
			),
		)
	);
}

/**
 * Custom block categories.
 *
 * @param array $categories Categories.
 *
 * @return array
 */
function custom_block_categories( $categories ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'jcore-blocks',
				'title' => __( 'jcore blocks', 'jcore' ),
			),
		)
	);
}

/**
 * Load scripts and styles.
 *
 * @return void
 */
function scripts() {
	Assets::style_register( 'swiper', '/vendor/swiper-8.6.4/swiper-bundle.css', array(), '8.6.4' );
	Assets::script_register( 'swiper', '/vendor/swiper-8.6.4/swiper-bundle.js', array(), '8.6.4' );
	Assets::script_register( 'wp-gallery-lightbox', '/dist/js/wp-gallery.lightbox.js', array(), '1.0.0' );
	Assets::script_register( 'popper', '/dist/js/popper.js', array(), '1.0.0' );
	Assets::script_register(
		'bootstrap',
		'/dist/js/bootstrap.js',
		array(
			'jquery',
		),
		'5.2.2',
	);

	Assets::script_register( 'share', '/js/share.js' );
	Assets::script_register(
		'jcore',
		'/dist/js/jcore.js',
	);
	Assets::script_register(
		'alpine',
		'/dist/js/alpine-jcore.js',
	);

	Assets::script_register(
		'yoast-faq-accordion',
		'/dist/js/yoast-faq.js',
	);

	Assets::style_register(
		'theme',
		'/dist/css/theme.css',
		array()
	);

	Assets::style_register(
		'tailwind',
		'/dist/css/tailwind.css',
	);

	Assets::style_register(
		'fa6-free',
		'/assets/vendor/FA6/css/all.min.css',
	);

	wp_enqueue_style( 'theme' );
	wp_enqueue_style( 'tailwind' );
	wp_enqueue_style( 'fa6-free' );

	wp_enqueue_script( 'jcore' );
	wp_enqueue_script( 'jUtils' );
	wp_enqueue_script( 'fontSize' );
	wp_enqueue_script( 'wp-gallery-lightbox' );

	if ( is_singular() && has_block( 'yoast/faq-block' ) ) {
		wp_enqueue_script( 'yoast-faq-accordion' );
	}

	if ( apply_filters( 'jcore_load_alpine_script', false ) ) {
		wp_enqueue_script( 'alpine' );
	}

	wp_add_inline_style( 'theme', Customizer::get_styles() );
}

/**
 * Load block editor scripts.
 *
 * @return void
 */
function block_editor_scripts() {
	Assets::script_register( 'lightbox-gallery-filters', '/dist/js/lightbox-filters.js', array( 'wp-edit-post' ) );
	wp_enqueue_script( 'lightbox-gallery-filters' );
}

/**
 * Replace Gravity Forms submit button classes with btn class
 */
function add_custom_css_classes( $button, $form ) {
	$dom = new \DOMDocument();
	$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
	$input   = $dom->getElementsByTagName( 'input' )->item( 0 );
	$classes = $input->getAttribute( 'class' );
	$classes = 'btn';
	$input->setAttribute( 'class', $classes );

	return $dom->saveHtml( $input );
}

add_filter( 'gform_submit_button', 'Jcore\Ilme\add_custom_css_classes', 10, 2 );


add_filter( 'jcore_blocks_get_blocks', 'Jcore\Ilme\register_block_folder', 10, 1 );
/**
 * Register default block folder.
 *
 * @param array $blocks Array of blocks.
 *
 * @return array
 */
function register_block_folder( $blocks ): array {
	$blocks_folder = get_template_directory() . '/classes/Blocks';

	return array_merge( $blocks, Blocks::list_blocks( $blocks_folder, 'Jcore\Ilme\Blocks\\' ) );
}
