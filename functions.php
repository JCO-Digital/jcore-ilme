<?php
/**
 * JCORE main functions.
 *
 * @package jcore
 */

namespace Jcore\Ilme;

use Jcore\Ydin\Settings\Customizer;
use Jcore\Ydin\WordPress\Assets;
use Timber;
use Timber\Timber as TimberTimber;

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/modules.php';
require_once __DIR__ . '/includes/menu-header-footer.php';
require_once __DIR__ . '/includes/images.php';
require_once __DIR__ . '/includes/timber.php';
require_once __DIR__ . '/includes/archive.php';
require_once __DIR__ . '/classes/Settings.php';
require_once __DIR__ . '/includes/templates.php';
require_once __DIR__ . '/includes/polylang.php';
require_once __DIR__ . '/includes/customizer.php';

add_action( 'after_setup_theme', 'Jcore\Ilme\setup' );
add_action( 'wp_enqueue_scripts', 'Jcore\Ilme\scripts' );

// Hide LearnDash support template if LearnDash is not installed.
add_filter( 'theme_page_templates', 'Jcore\Ilme\exclude_template_learndash_content' );

/*-----  End of Hook Library  ------*/

Settings::init();

add_filter(
	'jcore_menus',
	function ( $menus ) {
		$menus['primary'] = __( 'Primary Menu', 'jcore' );
		$menus['top']     = __( 'Top Menu', 'jcore' );

		return $menus;
	}
);


/**
 * Disable Comments functionality if not enabled in customizer.
 */
if ( ! Customizer::get( 'article', 'enable_comments' ) ) {
	add_action(
		'init',
		function () {
			// Close comments on the front-end.
			add_filter( 'comments_open', '__return_false', 20, 2 );
			add_filter( 'pings_open', '__return_false', 20, 2 );

			// Hide existing comments.
			add_filter( 'comments_array', '__return_empty_array', 10, 2 );

			// Remove comments page in menu.
			add_action(
				'admin_menu',
				function () {
					remove_menu_page( 'edit-comments.php' );
				}
			);

			// Remove comments links from admin bar.
			if ( is_admin_bar_showing() ) {
				remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
			}

			// Disable comments REST route.
			add_filter(
				'rest_endpoints',
				function ( $endpoints ) {
					if ( isset( $endpoints['/wp/v2/comments'] ) ) {
						unset( $endpoints['/wp/v2/comments'] );
					}
					if ( isset( $endpoints['/wp/v2/comments/(?P<id>[\d]+)'] ) ) {
						unset( $endpoints['/wp/v2/comments/(?P<id>[\d]+)'] );
					}

					return $endpoints;
				}
			);
		}
	);

	add_action(
		'admin_init',
		function () {

			// Redirect any user trying to access comments page.
			global $pagenow;

			if ( 'edit-comments.php' === $pagenow ) {
				wp_safe_redirect( admin_url() );
				exit;
			}

			// Remove comments metabox from dashboard.
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

			// Disable support for comments and trackbacks in post types.
			foreach ( get_post_types() as $post_type ) {
				if ( post_type_supports( $post_type, 'comments' ) ) {
					remove_post_type_support( $post_type, 'comments' );
					remove_post_type_support( $post_type, 'trackbacks' );
				}
			}
		}
	);
}

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
 * Custom body open.
 *
 * @return void
 */
function custom_body_open() {
	// TODO Fix.
	if ( Settings::get( 'keys', 'google_tag_manager' ) ) {
		TimberTimber::render( 'partials/google-tag-manager-noscript.twig', array( 'tag_manager' => trim( Settings::get( 'keys', 'google_tag_manager' ) ) ) );
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
		'/vendor/alpine/alpine.min.js',
		array(),
		'3.13.0',
		array(
			'strategy' => 'defer',
		)
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
	if ( apply_filters( 'jcore_load_alpine_script', true ) ) {
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
 * Load scripts for admin pages.
 *
 * @param string $hook Hook.
 *
 * @return void
 */
function admin_scripts( $hook ) {
	Assets::style_register( 'jcore-admin-style', '/dist/css/admin.css', array(), '' );
	wp_enqueue_style( 'jcore-admin-style' );
	wp_add_inline_style( 'jcore-admin-style', Customizer::get_admin_styles() );
}

/**
 * Load scripts for login page.
 *
 * @param string $hook Hook.
 *
 * @return void
 */
function login_scripts( $hook ) {
	Assets::style_register( 'jcore-login-style', '/dist/css/wplogin.css', array(), '' );
	wp_enqueue_style( 'jcore-login-style' );
	wp_add_inline_style( 'jcore-login-style', Customizer::get_styles() );
}


/**
 * Adds the reusable blocks page to admin menu
 */
add_action( 'admin_menu', 'Jcore\Ilme\linked_url' );
function linked_url() {
	add_menu_page( 'linked_url', 'Reusable Blocks', 'read', 'edit.php?post_type=wp_block', '', 'dashicons-layout', 22 );
}

/**
 * Registers jcore widgets.
 */
function widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer main', 'jcore' ),
			'id'            => 'footer-main',
			'description'   => __( 'Main Footer Widget Area.', 'jcore' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Footer site info', 'jcore' ),
			'id'            => 'footer-info',
			'description'   => __( 'Footer info and copyright info goes here.', 'jcore' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		)
	);
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

/**
 * Hide LearnDash support template if LearnDash is not installed
 *
 * @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
 *
 * @return array Filtered array of page templates.
 */
function exclude_template_learndash_content( $post_templates ) {
	if ( ! class_exists( 'SFWD_LMS' ) ) {
		unset( $post_templates['template-learndash-content.php'] );
	}

	return $post_templates;
}
