<?php
/**
 * Block styles the editor can pick. Their CSS is in src/scss/blocks/.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

defined( 'ABSPATH' ) || exit;

add_action( 'init', __NAMESPACE__ . '\\register_block_styles' );

/**
 * Register the theme's block styles.
 *
 * @return void
 */
function register_block_styles(): void {
	// Rounded corners (core's own "Rounded" image style is a full circle).
	// Radius: --jcore-image-radius in _images-and-gallery.scss.
	foreach ( array( 'core/image', 'core/gallery', 'core/embed' ) as $block ) {
		register_block_style(
			$block,
			array(
				'name'  => 'rounded-corners',
				'label' => __( 'Rounded corners', 'jcore' ),
			)
		);
	}
}
