<?php
/**
 * Handler for Archive and Home PHP templates.
 *
 * @package jcore
 */

namespace Jcore\Ilme;

use Jcore\Ydin\WordPress\Assets;
use Timber\Timber;

$context = Timber::context();

$this_post_type = get_post_type() ? get_post_type() : get_queried_object()->name ?? 'post'; // phpcs:ignore WordPress.PHP.DisallowShortTernary.Found

$context['post_type'] = $this_post_type;

$templates = array();

$context = archive_context( $context, $this_post_type );

$context['archive_class'] = 'archive-posts archive-' . $this_post_type;
if ( ! empty( $context['columns'] ) ) {
	$context['archive_class'] .= ' columns-' . $context['columns'];
}
if ( $context['masonry'] ) {
	$context['archive_class'] .= ' masonry-grid';
}

// TODO Fix this part.
if ( false && $context['dynamic-archive'] ) {
	// Dynamic Archive.
	Assets::script_register( 'dynamic-archive', '/vendor/dynamic-archive/dynamic-archive.js' );
	wp_enqueue_script( 'dynamic-archive' );

	$context['archive_class'] .= ' dynamic-archive';

	$templates[] = 'archive-dynamic-' . $this_post_type . '.twig';
	$templates[] = 'archive-dynamic.twig';
} else {
	// Classic Archive.
	if ( is_home() ) {
		$templates[] = 'posts.twig';
	}

	if ( ! empty( $context['primary'] ) ) {
		$context['terms'] = get_cats( $context['primary'] );
	}

	$templates[] = 'archive-' . $this_post_type . '.twig';
	if ( is_tag() ) {
		$run_filter_query = false;
	} elseif ( is_category() ) {
		$run_filter_query    = false;
		$category            = get_query_var( 'category_name' );
		$context['category'] = $category;
		$templates[]         = 'archive-' . $category . '.twig';
	} elseif ( is_tax() ) {
		$run_filter_query = false;
	}
	$templates[] = 'archive.twig';
}

$content_post = get_page_by_path( get_current_slug() );
if ( $content_post ) {
	$context['post'] = Timber::get_post( $content_post );
}

get_header();
Timber::render( $templates, $context );
get_footer();

/**
 * Returns the current slug.
 *
 * @return string
 */
function get_current_slug(): string {
	$url   = $_SERVER['REQUEST_URI'];
	$parts = explode( '?', $url );

	return trim( $parts[0], '/' );
}
