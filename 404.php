<?php
/*
 * Handler for none existant pages.
 */

$GLOBALS['page_title'] = 'Foo';

$context = Timber::context();

$post_404_id = null;
// Use $context['language'] here for language because it's already created for compatibility with polylang and without.
foreach (
	get_posts(
		array(
			'post_name' => array( 'page-404-' . $context['language'], 'page-404' ),
			'post_type' => 'page',
		)
	) as $post_404
) {
	/*
	 * Search for posts with the slug 'page-404-language' or 'page-404'.
	 * This is in order to be able to make custom 404 pages.
	 * This is an ugly solution that has to be done because of WPs fuzzy slug matching, there might be a better way.
	 */
	if ( ( null === $post_404_id && 'page-404' === $post_404->post_name ) || 'page-404-' . $context['language'] === $post_404->post_name ) {
		$post_404_id = $post_404->ID;
	}
}
if ( empty( $post_404_id ) ) {
	$context['page_title'] = __( 'Page not found', 'jcore' );
} else {
	$timber_post           = new Timber\Post( $post_404_id );
	$context['page_title'] = $timber_post->post_title;
	$context['post']       = $timber_post;
}
$GLOBALS['page_title'] = $context['page_title'];

$templates = array( '404.twig' );

get_header();
Timber::render( $templates, $context );
get_footer();
