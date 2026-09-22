<?php
/**
 * Theme entry point.
 *
 * Ilme is a blank starting point: the shared behaviour lives in `jcore/ydin`, and
 * this theme only wires it up and adds what is specific to the project. Anything
 * added here stays with the project, since the theme is detached from its
 * repository once it is copied.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

defined( 'ABSPATH' ) || exit;

const AUTOLOADER_PATH = ABSPATH . 'vendor/autoload.php';
if ( file_exists( AUTOLOADER_PATH ) ) {
	require_once AUTOLOADER_PATH;
}

require_once __DIR__ . '/includes/setup.php';
require_once __DIR__ . '/includes/assets.php';
