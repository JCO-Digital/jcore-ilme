<?php
/**
 * Footer for external plugins to use.
 *
 * This is to support plugins using get_footer() calls.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package jcore_child
 */

use Timber\Timber;

$context = Timber::context();

Timber::render( 'footer.twig', $context );
