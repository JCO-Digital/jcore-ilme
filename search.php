<?php
/**
 * Template to handle search call.
 *
 * @package jcore
 */

namespace Jcore\Ilme;

// Search page.
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
