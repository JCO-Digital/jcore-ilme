<?php
/**
 * Additions to the editor's blocks: block styles the editor can pick (their
 * CSS is in src/scss/blocks/) and blocks hidden on top of Ydin's list.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

defined( 'ABSPATH' ) || exit;

add_action( 'init', __NAMESPACE__ . '\\register_block_styles' );
add_filter( 'jcore_restricted_blocks', __NAMESPACE__ . '\\restricted_blocks' );

/**
 * Changes to Ydin's list of hidden blocks.
 *
 * - Details is hidden: accordions are Lohko's accordion block
 *   (lohko/accordions), one accordion everywhere.
 * - Columns stay available. Layouts with columns are built with Ruudukko
 *   (jcore/grid), which is what the style guide shows.
 *
 * @param array $blocks Block names Ydin hides.
 *
 * @return array
 */
function restricted_blocks( array $blocks ): array {
	$blocks   = array_values( array_diff( $blocks, array( 'core/columns', 'core/column' ) ) );
	$blocks[] = 'core/details';

	return $blocks;
}

/**
 * Register the theme's block styles.
 *
 * @return void
 */
function register_block_styles(): void {
	// Paragraphs: the intro paragraph at the top of a page or article.
	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'ingress',
			'label' => __( 'Ingress', 'jcore' ),
		)
	);

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
