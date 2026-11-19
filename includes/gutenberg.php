<?php
/**
 * Additions to the editor's blocks: block styles the editor can pick, blocks
 * kept out of the inserter.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

defined( 'ABSPATH' ) || exit;

add_action( 'init', __NAMESPACE__ . '\\register_block_styles' );

add_filter( 'register_block_type_args', __NAMESPACE__ . '\\hide_core_blocks', 10, 2 );

/**
 * Core blocks kept out of the editor's inserter (and its transforms): ones
 * the design has no look for (quote, media and text, search, verse) and ones a
 * plugin block replaces (details: Lohko's accordion; columns: Ruudukko's
 * jcore/grid). They still render where they already are, e.g. the search
 * block in templates/search.html and 404.html.
 *
 * @param array  $args       Block type arguments.
 * @param string $block_name Block name.
 * @return array
 */
function hide_core_blocks( array $args, string $block_name ): array {
	if ( in_array( $block_name, array( 'core/quote', 'core/media-text', 'core/search', 'core/details', 'core/columns', 'core/column', 'core/verse' ), true ) ) {
		$args['supports']             = $args['supports'] ?? array();
		$args['supports']['inserter'] = false;
	}
	return $args;
}

/**
 * Block styles the editor can pick. Their CSS is in src/scss/blocks/.
 *
 * @return void
 */
function register_block_styles(): void {
	// Images: rounded corners (core's "Rounded" is a full circle).
	register_block_style(
		'core/image',
		array(
			'name'  => 'rounded-corners',
			'label' => __( 'Rounded corners', 'jcore' ),
		)
	);

	// Images: a low, wide band with rounded corners (e.g. under a text section), cropped
	// to the same shape whatever the photo's own proportions.
	register_block_style(
		'core/image',
		array(
			'name'  => 'band',
			'label' => __( 'Wide band', 'jcore' ),
		)
	);

	// Groups: the design's call-to-action box (patterns/cta.php), a gradient
	// box with rounded corners and centred light text.
	register_block_style(
		'core/group',
		array(
			'name'  => 'cta',
			'label' => __( 'CTA box', 'jcore' ),
		)
	);

	// Groups: rounded corners only (the CTA box's radius), e.g. for a group
	// with a background colour.
	register_block_style(
		'core/group',
		array(
			'name'  => 'rounded-corners',
			'label' => __( 'Rounded corners', 'jcore' ),
		)
	);

	// Ruudukko grids: columns centred against each other, e.g. text beside an
	// image (an image and text section, see the style guide).
	register_block_style(
		'jcore/grid',
		array(
			'name'  => 'center-vertical',
			'label' => __( 'Centred vertically', 'jcore' ),
		)
	);
}

/**
 * The page hero's excerpt (parts/hero-plain.html) shows only an excerpt the
 * editor wrote, not one cut from the content: without one, the hero has the
 * title alone.
 *
 * @param string $block_content The block's HTML.
 * @param array  $block         The block.
 * @return string
 */
function hero_excerpt_only_manual( string $block_content, array $block ): string {
	$class = $block['attrs']['className'] ?? '';
	if ( str_contains( $class, 'hero-plain__excerpt' ) && ! has_excerpt() ) {
		return '';
	}
	return $block_content;
}
add_filter( 'render_block_core/post-excerpt', __NAMESPACE__ . '\\hero_excerpt_only_manual', 10, 2 );
