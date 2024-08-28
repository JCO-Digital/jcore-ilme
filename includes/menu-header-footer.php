<?php
/**
 * JCore Footer Functions
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

use Jcore\Ydin\WordPress\PostType;

add_action( 'after_setup_theme', 'Jcore\Ilme\register_footer_post_type' );
add_action( 'after_switch_theme', 'Jcore\Ilme\create_footer_posts' );
add_action( 'after_setup_theme', 'Jcore\Ilme\register_menu' );

add_filter( 'timber/context', 'Jcore\Ilme\footer_context' );
add_filter( 'body_class', 'Jcore\Ilme\add_page_slug_body_class' );

add_filter(
	'login_headerurl',
	function () {
		return home_url();
	}
);
add_filter(
	'login_headertext',
	'get_custom_logo'
);

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
 * Registers the footer post type.
 *
 * @return void
 */
function register_footer_post_type() {
	PostType::register(
		'footer',
		array(),
		array(
			'show_in_rest'        => true,
			'has_archive'         => false,
			'show_in_menu'        => 'themes.php',
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'supports'            => array( 'title', 'editor' ),
		)
	);
}

/**
 * Creates footer posts for predefined areas if they do not exist.
 *
 * @return void
 */
function create_footer_posts() {
	$footer_areas = array(
		array(
			'slug'  => 'footer-left',
			'title' => 'Footer Left',
		),
		array(
			'slug'  => 'footer-center',
			'title' => 'Footer Center',
		),
		array(
			'slug'  => 'footer-right',
			'title' => 'Footer Right',
		),
		array(
			'slug'  => 'footer-below-logo',
			'title' => 'Footer Below Logo',
		),
		array(
			'slug'  => 'footer-copyright',
			'title' => 'Footer Copyright',
		),
	);

	foreach ( $footer_areas as $footer_area ) {
		$footer = get_page_by_path( $footer_area['slug'], OBJECT, 'footer' );
		if ( ! $footer ) {
			create_footer_post( $footer_area );
		}
	}
}

/**
 * Creates a new footer post.
 *
 * The post's title, slug (used as the post name), status, and type are set according to the provided `$footer_area` array.
 *
 * @param array $footer_area An associative array containing the 'title' and 'slug' of the footer area.
 * @return void
 */
function create_footer_post( $footer_area ) {
	wp_insert_post(
		array(
			'post_title'  => $footer_area['title'],
			'post_name'   => $footer_area['slug'],
			'post_status' => 'publish',
			'post_type'   => 'footer',
		)
	);
}

/**
 * Adds footer posts to the Timber context.
 *
 * @param array $context The Timber context.
 * @return array
 */
function footer_context( $context ) {
	if ( empty( $context['footer'] ) ) {
		$context['footer'] = array();
	}

	$footer_posts = get_posts(
		array(
			'post_type'      => 'footer',
			'posts_per_page' => -1,
		)
	);

	foreach ( $footer_posts as $footer_post ) {
		$underscored                       = str_replace( '-', '_', $footer_post->post_name );
		$context['footer'][ $underscored ] = get_footer_post( $footer_post->post_name );
	}

	return $context;
}

/**
 * Retrieves a footer post by its slug.
 *
 * @param string $slug The slug of the footer post to retrieve.
 * @return \Timber\Post The retrieved post object.
 */
function get_footer_post( $slug ) {
	$post_id = get_page_by_path( $slug, OBJECT, 'footer' )->ID;

	return \Timber::get_post( $post_id );
}

/**
 * Add page slug to body class
 */
function add_page_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}

	return $classes;
}
