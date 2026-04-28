<?php
/**
 * JCORE main functions added in /includes/ folders, theme specific functions added here.
 *
 * @package jcore
 */

defined( 'ABSPATH' ) || exit;

namespace Jcore\Ilme;

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/modules.php';
require_once __DIR__ . '/includes/menu-header-footer.php';
require_once __DIR__ . '/includes/images.php';
require_once __DIR__ . '/includes/wplogin.php';
require_once __DIR__ . '/includes/admin.php';
require_once __DIR__ . '/includes/theme-support.php';
require_once __DIR__ . '/includes/acf.php';
require_once __DIR__ . '/includes/gutenberg.php';

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
