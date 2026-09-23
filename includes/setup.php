<?php
/**
 * Theme setup: which Ydin features to run, and the project's own configuration.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

defined( 'ABSPATH' ) || exit;

use Jcore\Ydin;

/**
 * The navigation menus this theme registers.
 *
 * Ydin registers them with WordPress and adds them to the Timber context, so a
 * menu added here is available in Twig as `menu.primary` and friends.
 */
add_filter(
	'jcore_menus',
	function ( $menus ) {
		$menus['primary']       = __( 'Primary Menu', 'jcore' );
		$menus['top']           = __( 'Top Menu', 'jcore' );
		$menus['footer_left']   = __( 'Footer Left', 'jcore' );
		$menus['footer_middle'] = __( 'Footer Middle', 'jcore' );
		$menus['footer_right']  = __( 'Footer Right', 'jcore' );
		$menus['footer_bottom'] = __( 'Footer Bottom', 'jcore' );

		return $menus;
	}
);

/**
 * The modules (plugin bootstraps) Ydin should initialize on `init`.
 */
add_filter(
	'jcore_theme_load_modules',
	function ( $modules ) {
		$modules[] = \Jcore\Oikeus\Bootstrap::class;

		return $modules;
	}
);

/**
 * Start Ydin, then opt in to the features this theme wants.
 *
 * Remove a line to drop the feature from the project. Each feature documents its
 * own filters if you want to keep it but change how it behaves.
 */
Ydin\Bootstrap::init();

Ydin\WordPress\Acf::init();
Ydin\WordPress\Admin::init();
Ydin\WordPress\Blocks::init();
Ydin\WordPress\Comments::init();
Ydin\WordPress\Editor::init();
Ydin\WordPress\Forms::init();
Ydin\WordPress\Login::init();
Ydin\WordPress\Media::init();
Ydin\WordPress\Menus::init();
Ydin\WordPress\ThemeSupport::init();

add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );

/**
 * Theme specific setup, on top of the Ydin baseline.
 *
 * @return void
 */
function setup(): void {
	load_theme_textdomain( 'jcore', get_template_directory() . '/languages' );
}
