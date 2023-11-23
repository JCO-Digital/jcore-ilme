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

$context['posts'] = new PostQuery();

get_header();
Timber::render( $templates, $context );
get_footer();
