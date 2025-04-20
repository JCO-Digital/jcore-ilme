<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing
/**
 * Modules
 *
 * Add all modules that are used in the project here.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

use Jcore\Ydin;
use Jcore\Ydin\BootstrapInterface;
use Jcore\Security;

add_filter(
	'jcore_theme_load_modules',
	function ( $modules ) {
		$modules[] = Security\Bootstrap::class;
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
	Ydin\Bootstrap::init();
	$modules = apply_filters( 'jcore_theme_load_modules', array() );
	foreach ( $modules as $module ) {
		$module::init();
	}
	do_action( 'jcore_modules_loaded', $modules );
}
add_action(
	'init',
	'Jcore\Ilme\load_modules'
);
