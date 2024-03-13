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

/**
 * The modules to load.
 *
 * @var BootstrapInterface[] $modules
 */
$modules = array();

/**
 * Called immediately and loads all modules.
 *
 * @param BootstrapInterface[] $modules Array of modules to load.
 *
 * @return void
 */
function load_modules( array $modules ): void {
	// First we initialize Ydin.
	Ydin\Bootstrap::init();
	$modules = apply_filters( 'jcore_ilme_load_modules', $modules );
	foreach ( $modules as $module ) {
		$module::init();
	}
}

load_modules( $modules );
