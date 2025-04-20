<?php

/**
 * Load scripts for admin pages.
 *
 * @param string $hook Hook.
 *
 * @return void
 */

namespace Jcore\Ilme;

use Jcore\Ydin\WordPress\Assets;

function admin_scripts( $hook ) {
	Assets::style_register( 'jcore-admin-style', '/dist/css/admin.css', array(), '' );
	wp_enqueue_style( 'jcore-admin-style' );
}
