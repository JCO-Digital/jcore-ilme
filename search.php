<?php
/*
 * Template to handle search call.
 */

// Search page.
use Timber\PostQuery;
use Timber\Timber;

$templates[] = 'search.twig';
$templates[] = 'archive.twig';
$templates[] = 'index.twig';

$context = Timber::context();

// Get posts from default query.
global $wp_query;

$posts = Timber::get_posts( $wp_query );

$context['posts'] = $posts;

get_header();
Timber::render( $templates, $context );
get_footer();
