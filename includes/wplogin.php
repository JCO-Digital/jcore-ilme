<?php
/**
 * JCore WP Login Functions
 *
 * @package Jcore\Ilme
 */

add_action( 'login_enqueue_scripts', 'Jcore\Ilme\login_scripts' );

/**
 * Load scripts for login page.
 *
 * @param string $hook Hook.
 *
 * @return void
 */
function login_scripts( $hook ) {
	Assets::style_register( 'jcore-login-style', '/dist/css/wplogin.css', array(), '' );
	wp_enqueue_style( 'jcore-login-style' );
}
add_filter(
	'login_headerurl',
	function () {
		return home_url();
	}
);
add_filter(
	'login_headertext',
	'get_custom_logo'
);
