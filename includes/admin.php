<?php

/**
 * Load scripts for admin pages.
 *
 * @param string $hook Hook.
 *
 * @return void
 */

namespace Jcore\Ilme;

use Jcore\Ydin\Settings\Customizer;
use Jcore\Ydin\WordPress\Assets;

function admin_scripts( $hook ) {
	Assets::style_register( 'jcore-admin-style', '/dist/css/admin.css', array(), '' );
	wp_enqueue_style( 'jcore-admin-style' );
	wp_add_inline_style( 'jcore-admin-style', Customizer::get_admin_styles() );
}

/**
 * Adds the reusable blocks page to admin menu
 */
add_action( 'admin_menu', 'Jcore\Ilme\linked_url' );
function linked_url() {
	add_menu_page( 'linked_url', 'Reusable Blocks', 'read', 'edit.php?post_type=wp_block', '', 'dashicons-layout', 22 );
}
