<?php
/*
 * Template to handle search call.
 */

// Search page.
use Timber\PostQuery;
use Timber\Timber;

$templates = array(
	'search.twig',
	'archive.twig',
	'index.twig',
);

$context = Timber::context();

// Get posts from default query.
global $wp_query;
$context['posts'] = Timber::get_posts( $wp_query );

get_header();
Timber::render( $templates, $context );
get_footer();
