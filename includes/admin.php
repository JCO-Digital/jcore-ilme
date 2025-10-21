<?php
/**
 * Load scripts for admin pages.
 *
 * @param string $hook Hook.
 *
 * @return void
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

use Jcore\Ydin\WordPress\Assets;

/**
 * Enqueue admin styles.
 *
 * @param string $hook Hook.
 *
 * @return void
 */
function admin_scripts( $hook ) {
	Assets::style_register( 'jcore-admin-style', '/dist/css/admin.css', array(), '' );
	wp_enqueue_style( 'jcore-admin-style' );
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\admin_scripts' );
