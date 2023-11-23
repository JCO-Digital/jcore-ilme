<?php
/**
 * Header for external plugins to use.
 *
 * This is to support plugins using get_footer() calls.
 *
 * @see     https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package jcore_child
 */

use Timber\Timber;

$context = Timber::context();

$graph = apply_filters( 'jcore_schema', array(), $context );
if ( ! empty( $graph ) ) {
	$context['schema'] = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);
}

Timber::render( 'header.twig', $context );
