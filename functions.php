<?php
/**
 * JCORE main functions added in /includes/ and /clases/ folders, Theme specific functions added here.
 *
 * @package jcore
 */

namespace Jcore\Ilme;

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/modules.php';
require_once __DIR__ . '/includes/menu-header-footer.php';
require_once __DIR__ . '/includes/images.php';
require_once __DIR__ . '/includes/timber.php';
require_once __DIR__ . '/includes/theme-support.php';
require_once __DIR__ . '/includes/archive.php';

require_once __DIR__ . '/includes/templates.php';
require_once __DIR__ . '/includes/polylang.php';
require_once __DIR__ . '/includes/customizer.php';
require_once __DIR__ . '/includes/wplogin.php';
require_once __DIR__ . '/includes/admin.php';

require_once __DIR__ . '/includes/wp-comments.php';
require_once __DIR__ . '/includes/learnDash.php';


add_filter(
	'jcore_menus',
	function ( $menus ) {
		$menus['primary'] = __( 'Primary Menu', 'jcore' );
		$menus['top']     = __( 'Top Menu', 'jcore' );

		return $menus;
	}
);
