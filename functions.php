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

const AUTOLOADER_PATH = ABSPATH . '/vendor/autoload.php';
if ( file_exists( AUTOLOADER_PATH ) ) {
	require_once AUTOLOADER_PATH;
}

require_once __DIR__ . '/includes/modules.php';
require_once __DIR__ . '/includes/footer.php';

if ( function_exists( '\Sentry\init' ) && defined( 'SENTRY_DSN' ) && ! defined( 'JCORE_IS_LOCAL' ) ) {
	\Sentry\init( array( 'dsn' => SENTRY_DSN ) );
}

add_action( 'after_setup_theme', 'Jcore\Ilme\setup' );
add_action( 'wp_enqueue_scripts', 'Jcore\Ilme\scripts' );
add_action( 'after_setup_theme', 'Jcore\Ilme\register_menu' );
/*
add_action( 'acf/init', 'Jcore\Ilme\initialize_jcore_settings' );
add_action( 'admin_enqueue_scripts', 'Jcore\Ilme\admin_scripts' );
add_action( 'enqueue_block_editor_assets', 'Jcore\Ilme\block_editor_scripts' );
add_action( 'login_enqueue_scripts', 'Jcore\Ilme\login_scripts' );
add_action( 'widgets_init', 'Jcore\Ilme\widgets_init' );
add_action( 'init', 'Jcore\Ilme\init' );
add_action( 'wp_body_open', 'Jcore\Ilme\custom_body_open' );
add_action( 'wp_head', 'Jcore\Ilme\custom_head' );

// Custom ACF fields.
add_action( 'acf/include_field_types', 'Jcore\Ilme\add_acf_fields' );

add_filter( 'theme_templates', 'Jcore\Ilme\jcore_templates' );

// Enable shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode' );

add_filter( 'jpeg_quality', 'Jcore\Ilme\jpeg_quality', 10, 2 );
add_filter( 'wp_editor_set_quality', 'Jcore\Ilme\jpeg_quality', 10, 2 );

// Custom gutenberg category for jcore blocks.
add_filter( 'block_categories_all', 'Jcore\Ilme\custom_block_categories', 10, 2 );

// Turn off translations for reusable blocks.
add_filter( 'pll_get_post_types', 'Jcore\Ilme\remove_reusable_block_from_pll', 10, 2 );

add_filter( 'get_custom_logo', 'Jcore\Ilme\get_logo' );

add_filter( 'pre_get_document_title', 'Jcore\Ilme\custom_page_title', 20 );
*/

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

// Add mime types.
add_filter( 'upload_mimes', 'Jcore\Ilme\cc_mime_types' );

// Hide LearnDash support template if LearnDash is not installed.
add_filter( 'theme_page_templates', 'Jcore\Ilme\exclude_template_learndash_content' );

/*-----  End of Hook Library  ------*/

// Timber functions.
require_once 'includes/timber.php';

// Archive.
require_once 'includes/archive.php';


// Utility Functions.
// require_once 'includes/utility-functions.php';

// Extended Core Blocks
// require_once 'includes/extended-blocks.php';

// Gutenberg Block Patterns.
// require_once 'includes/block-patterns.php';

// jcore blocks loader.
// require_once 'includes/blocks.php';

// Breakpoints.
// require_once 'includes/breakpoints.php';


// Endpoints.
// require_once 'includes/endpoints.php';

// Image modification functions.
// require_once 'includes/images.php';

// ACF JSON Settings.
// require_once 'includes/acf-settings.php';

// Vue Functions.
// require_once 'includes/vue-functions.php';

// WooCommerce Functions.
// require_once 'includes/woo-functions.php';

require_once 'classes/Settings.php';

Settings::init();

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
 * Set JPEG quality on resize.
 *
 * @param int    $quality The quality setting passed to the function.
 * @param string $context The type of image.
 *
 * @return int
 */
function jpeg_quality( $quality, $context ) {
	if ( 'image/jpeg' === $context && $quality > 85 ) {
		return $quality;
	}

	return 92;
}

/**
 * Add SVG to allowed MIME types.
 *
 * @param array $mimes Allowed mime types.
 *
 * @return mixed
 */
function cc_mime_types( $mimes ) {
	// Add SVG as allowed upload.
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
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
	load_jcore_textdomain();
}
/**
 * Translation Support.
 *
 * @return void
 */
function load_jcore_textdomain(): void {
	load_theme_textdomain( 'jcore', get_template_directory() . '/languages' );
}

/**
 * Add ACF fields.
 *
 * @param array $version List of templates.
 *
 * @return mixed
 */
function add_acf_fields( $version = false ) {
	new acf_field_color();
}


/**
 * Add jcore virtual templates to template stack.
 *
 * @param array $post_templates List of templates.
 *
 * @return mixed
 */
function jcore_templates( $post_templates ) {
	// TODO Temporary fix for missing ACF.
	if ( isset( $GLOBALS['jcore_settings'] ) ) {
		foreach ( $GLOBALS['jcore_settings']->templates as $template => $name ) {
			$post_templates[ 'jcore-' . $template ] = $name;
		}
		if ( $GLOBALS['jcore_settings']->vue['enabled'] ) {
			$post_templates['jcore-vue'] = 'Vue App';
		}
	}

	return $post_templates;
}

/**
 * Run on init hook.
 *
 * @return void
 */
function init() {
	Customizer::gutenberg_add_colors();

	if ( ! empty( Settings::get( 'keys', 'google_maps_key' ) ) ) {
		acf_update_setting( 'google_api_key', Settings::get( 'keys', 'google_maps_key' ) );
	}
}

/**
 *
 *
 * @return void
 */
function custom_body_open() {
	// TODO Fix.
	if ( Settings::get( 'keys', 'google_tag_manager' ) ) {
		Timber::render( 'partials/google-tag-manager-noscript.twig', array( 'tag_manager' => trim( Settings::get( 'keys', 'google_tag_manager' ) ) ) );
	}
}

function custom_head() {
	// TODO custom head.
	if ( Settings::get( 'keys', 'google_tag_manager' ) ) {
		Timber::render( 'partials/google-tag-manager.twig', array( 'tag_manager' => trim( Settings::get( 'keys', 'google_tag_manager' ) ) ) );
	}
	if ( Settings::get( 'keys', 'google_analytics' ) ) {
		Timber::render( 'partials/google-analytics.twig', array( 'analytics' => trim( Settings::get( 'keys', 'google_analytics' ) ) ) );
	}
}

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

function custom_page_title( $title ) {
	global $context;
	if ( ! empty( $context ) && ! empty( $context['page_title'] ) ) {
		return $context['page_title'] . ' - ' . get_bloginfo( 'name' );
	}

	return $title;
}

/**
 * Replaces <img> SVG with inline SVG.
 *
 * @param string $html The HTML returned by get_custom_logo.
 *
 * @return string
 */
function get_logo( string $html ) {
	$html = preg_replace( '_<a [^>]*>(.*)</a>_sm', '\1', $html );
	if ( preg_match( '_<img[^>]+src="([^"]+(/wp-content/[^"]+.svg))"[^>]+/>_', $html, $matches ) ) {
		$filename = rtrim( ABSPATH, '/' ) . $matches[2];
		if ( file_exists( $filename ) ) {
			$svg = file_get_contents( $filename );
			$svg = preg_replace( '_<\?xml[^>]+>_', '', $svg );

			return str_replace( $matches[0], '<div class="custom-logo">' . $svg . '</div>', $html );
		}
		// Enables SVG logos to work correctly on local sites.
		if ( WP_DEBUG ) {
			// Checks the transient first.
			$transient_name = '_custom_svg_logo';
			if ( false !== ( $value = get_transient( $transient_name ) ) ) { // phpcs:ignore
				return $value;
			}
			// Since we are requesting a local site and we are on a local site, we need to ignore SSL errors.
			add_filter( 'https_ssl_verify', '__return_false' );
			$request = wp_safe_remote_get( $matches[1] );
			// Undo the damage we just did.
			remove_filter( 'https_ssl_verify', '__return_false' );
			if ( is_wp_error( $request ) ) {
				return $html;
			}
			$svg = wp_remote_retrieve_body( $request );
			// Fixup the SVG and store it in a transient.
			$svg          = preg_replace( '_<\?xml[^>]+>_', '', $svg );
			$return_value = str_replace( $matches[0], '<div class="custom-logo">' . $svg . '</div>', $html );
			set_transient( $transient_name, $return_value, DAY_IN_SECONDS );

			return $return_value;
		}
	}

	return $html;
}

function scripts() {
	Assets::style_register( 'font-awesome', '/vendor/fontawesome-pro-6.4.2-web/css/all.min.css', array(), '6.4.2' );
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

	Assets::script_register( 'jUtils', '/dist/js/jUtils.js' );
	Assets::script_register( 'fontSize', '/dist/js/fontSize.js' );
	Assets::script_register( 'share', '/js/share.js' );
	Assets::script_register(
		'jcore',
		'/dist/js/jcore.js',
		array(
			'jquery',
			'bootstrap',
			'swiper',
			'share',
		)
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

	wp_enqueue_style( 'theme' );
	wp_enqueue_style( 'tailwind' );

	wp_enqueue_script( 'jcore' );
	wp_enqueue_script( 'jUtils' );
	wp_enqueue_script( 'fontSize' );
	wp_enqueue_script( 'wp-gallery-lightbox' );
	if ( apply_filters( 'jcore_load_alpine_script', true ) ) {
		wp_enqueue_script( 'alpine' );
	}

	wp_add_inline_style( 'theme', Customizer::get_styles() );
}

function block_editor_scripts() {
	Assets::script_register( 'lightbox-gallery-filters', '/dist/js/lightbox-filters.js', array( 'wp-edit-post' ) );
	wp_enqueue_script( 'lightbox-gallery-filters' );
}

function admin_scripts( $hook ) {
	Assets::style_register( 'jcore-admin-style', '/dist/css/admin.css', array(), '' );
	wp_enqueue_style( 'jcore-admin-style' );
	wp_add_inline_style( 'jcore-admin-style', Customizer::get_admin_styles() );
}

function login_scripts( $hook ) {
	Assets::style_register( 'jcore-login-style', '/dist/css/wplogin.css', array(), '' );
	wp_enqueue_style( 'jcore-login-style' );
	wp_add_inline_style( 'jcore-login-style', Customizer::get_styles() );
}


function register_menu() {
	register_nav_menu( 'primary', __( 'Primary Menu', 'jcore' ) );
	register_nav_menu( 'secondary', __( 'Secondary Menu', 'jcore' ) );
}

function get_children() {
	global $post;
	if ( is_page() ) {
		if ( $post->post_parent ) {
			$ancestors = get_post_ancestors( $post->ID );
			$parent    = $ancestors[ count( $ancestors ) - 1 ];
		} else {
			$parent = $post->ID;
		}
		$args = array(
			'numberposts' => - 1,
			'post_type'   => 'page',
			'post_parent' => $parent,
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
		);

		return Timber::get_posts( $args );
	}
}

/**
 * Turn off translations for reusable blocks
 */
function remove_reusable_block_from_pll( $post_types, $is_settings ) {
	unset( $post_types['wp_block'] );

	return $post_types;
}

/**
 *  Adds the reusable blocks page to admin menu
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
 * Add page slug to body class
 */
function add_page_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}

	return $classes;
}

add_filter( 'body_class', 'Jcore\Ilme\add_page_slug_body_class' );

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

/**
 * Handles setting the mailer to mailhog if we are in a dev environment.
 *
 * @param PHPMailer $phpmailer The PHPMailer instance.
 *
 * @return void
 */
add_action(
	'phpmailer_init',
	function ( $phpmailer ) {
		// phpcs:disable
        if ( defined( "JCORE_IS_LOCAL" ) && JCORE_IS_LOCAL ) {
			$phpmailer->Host = 'mailhog';
			$phpmailer->Port = 1025;
			$phpmailer->IsSMTP();
		}
        // phpcs:enable
	}
);


/**
 * Handles deactivation of the mailgun plugin when running locally.
 *
 * @return void
 */
add_action(
	'admin_init',
	function () {
		// phpcs:disable
        if ( defined("JCORE_IS_LOCAL") && JCORE_IS_LOCAL ) {
			deactivate_plugins( array( 'mailgun/mailgun.php' ) );
		}
        // phpcs:enable
	}
);
