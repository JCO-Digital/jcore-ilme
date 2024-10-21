<?php

/**
 * JCore WordPress Comments
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

use Jcore\Ydin\Settings\Customizer;

/**
 * Disable Comments functionality if not enabled in customizer.
 */
if ( ! Customizer::get( 'article', 'enable_comments' ) ) {
	add_action(
		'init',
		function () {
			// Close comments on the front-end.
			add_filter( 'comments_open', '__return_false', 20, 2 );
			add_filter( 'pings_open', '__return_false', 20, 2 );

			// Hide existing comments.
			add_filter( 'comments_array', '__return_empty_array', 10, 2 );

			// Remove comments page in menu.
			add_action(
				'admin_menu',
				function () {
					remove_menu_page( 'edit-comments.php' );
				}
			);

			// Remove comments links from admin bar.
			if ( is_admin_bar_showing() ) {
				remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
			}

			// Disable comments REST route.
			add_filter(
				'rest_endpoints',
				function ( $endpoints ) {
					if ( isset( $endpoints['/wp/v2/comments'] ) ) {
						unset( $endpoints['/wp/v2/comments'] );
					}
					if ( isset( $endpoints['/wp/v2/comments/(?P<id>[\d]+)'] ) ) {
						unset( $endpoints['/wp/v2/comments/(?P<id>[\d]+)'] );
					}

					return $endpoints;
				}
			);
		}
	);

	add_action(
		'admin_init',
		function () {

			// Redirect any user trying to access comments page.
			global $pagenow;

			if ( 'edit-comments.php' === $pagenow ) {
				wp_safe_redirect( admin_url() );
				exit;
			}

			// Remove comments metabox from dashboard.
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

			// Disable support for comments and trackbacks in post types.
			foreach ( get_post_types() as $post_type ) {
				if ( post_type_supports( $post_type, 'comments' ) ) {
					remove_post_type_support( $post_type, 'comments' );
					remove_post_type_support( $post_type, 'trackbacks' );
				}
			}
		}
	);
}
