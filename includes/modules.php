<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing

defined( 'ABSPATH' ) || exit;

/**
 * Modules
 *
 * Add all modules that are used in the project here.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

use Jcore\Ydin;
use Timber\Timber;

add_filter(
	'jcore_theme_load_modules',
	function ( $modules ) {
		$modules[] = \Jcore\Security\Bootstrap::class;
		$modules[] = \Jcore\Oikeus\Bootstrap::class;
		return $modules;
	}
);


/**
 * Loads all modules.
 *
 * @return void
 */
function load_modules(): void {
	// First we initialize Ydin.
	Timber::init();
	Ydin\Timber\ContextProvider::init();
	Ydin\Environment\Environment::init();

	$modules = apply_filters( 'jcore_theme_load_modules', array() );
	foreach ( $modules as $module ) {
		if ( class_exists( $module ) ) {
			$module::init();
		}
	}
	do_action( 'jcore_modules_loaded', $modules );
}
add_action(
	'init',
	'Jcore\Ilme\load_modules'
);
