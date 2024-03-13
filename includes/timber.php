<?php

namespace Jcore\Ilme;

use Twig\TwigFunction;
use Twig\TwigFilter;

// Timber context hook.
add_filter( 'timber/context', 'Jcore\Ilme\context', 10 );
add_filter( 'timber/twig', 'Jcore\Ilme\twig' );

/**
 * Filter for timber context.
 *
 * @param array $context The Timber context.
 *
 * @return array
 */
function context( $context ) {
	$context['main_class'] = 'is-layout-constrained';

	/* Now, you add a Timber menu and send it along to the context. */
	if ( empty( $context['menu'] ) ) {
		$context['menu'] = array();
	}
	$context['menu']['primary']   = \Timber::get_menu( 'primary' );
	$context['menu']['secondary'] = \Timber::get_menu( 'secondary' );

	if ( empty( $context['footer'] ) ) {
		$context['footer'] = array();
	}
	$context['footer']['main'] = \Timber::get_widgets( 'footer-main' );
	$context['footer']['info'] = \Timber::get_widgets( 'footer-info' );

	// $context['imagesizes'] = create_sizes( $GLOBALS['jcore_settings'] );

	$context['logo'] = get_custom_logo();

	// LearnDash support.
	if ( class_exists( 'SFWD_LMS' ) ) {
		$context['learndash_sidebar']       = \Timber::get_widgets( 'ld-sidebar' );
		$context['learndash_under_content'] = \Timber::get_widgets( 'ld-under-content' );
		$context['menu']['learndash']       = \Timber::get_menu( 'learndash' );
	}

	// If polylang is on.
	if ( function_exists( 'pll_the_languages' ) ) {
		$languages = array();
		foreach ( pll_the_languages( array( 'raw' => 1 ) ) as $l => $lang ) {
			$lang['home']    = pll_home_url( $l );
			$lang['class']   = implode( ' ', $lang['classes'] );
			$languages[ $l ] = $lang;
		}
		$context['languages']     = $languages;
		$context['language']      = pll_current_language();
		$context['language_name'] = pll_current_language( 'name' );
		$context['locale']        = pll_current_language( 'locale' );
		$context['home']          = pll_home_url();
	} else {
		$context['home']     = get_home_url();
		$context['language'] = substr( get_locale(), 0, 2 );
		$context['locale']   = get_locale();
	}

	if ( empty( $_SERVER['HTTP_REFERER'] ) ) {
		$context['referrer']          = '';
		$context['referrer_internal'] = 0;
	} else {
		$referer                      = wp_unslash( esc_url( $_SERVER['HTTP_REFERER'] ) );
		$context['referrer']          = $_SERVER['HTTP_REFERER'];
		$context['referrer_internal'] = strpos( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) ? 1 : 0;
	}

	// Yoast breadcrumbs.
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		$context['yoast_breadcrumbs'] = yoast_breadcrumb( '<p id="breadcrumbs">', '</p>', false );
	}

	// Yoast SEO links.
	$context['yoast'] = get_option( 'wpseo_social' );

	return $context;
}

function twig( $twig ) {
	$twig->addFilter( new TwigFilter( 'slug', 'Jcore\Ilme\slugify' ) );
	$twig->addFilter( new TwigFilter( 'slugify', 'Jcore\Ilme\slugify' ) );
	$twig->addFilter( new TwigFilter( 'euro', 'Jcore\Ilme\euro_format' ) );
	$twig->addFilter( new TwigFilter( 'preview', 'Jcore\Ilme\post_preview' ) );
	$twig->addFilter( new TwigFilter( 'tease_class', 'Jcore\Ilme\tease_class' ) );

	// Adding a function.
	$twig->addFunction(
		new TwigFunction( 'customizer', 'Jcore\Ydin\Settings\Customizer::get' )
	);
	$twig->addFunction(
		new TwigFunction( 'settings', 'Jcore\Ilme\Settings::get' )
	);

	$image_test = new \Twig\TwigTest(
		'timber_image',
		function ( $value ) {
			return ( gettype( $value ) === 'object' && get_class( $value ) === 'Timber\Image' );
		}
	);
	$twig->addTest( $image_test );

	return $twig;
}

function slugify( $text ) {
	$text = str_replace( array( 'å', 'ä', 'ö' ), array( 'a', 'a', 'o' ), strtolower( trim( $text ) ) );
	$text = preg_replace( '/[^a-z0-9]/', '-', $text );

	return $text;
}

function euro_format( $value ) {
	return number_format( $value, 2, ',', '' );
}

/**
 * @param $post
 * @param $nr
 * @param $threshold
 * @param $ellipsis
 * @param $force
 *
 * @return string
 */
function post_preview( $post, $nr = 50, $threshold = null, $ellipsis = '', $force = false ): string {
	if ( null === $threshold ) {
		$threshold = ceil( $nr / 5 );
	}

	if ( is_string( $post ) ) {
		// If post is string.
		$text = trim( wp_strip_all_tags( $post ) );
	} elseif ( is_object( $post ) ) {
		// If post is object.
		if ( empty( $post->post_excerpt ) ) {
			// If excerpt is not set, use post content.
			$remove_shortcodes = strip_shortcodes( $post->post_content );
			$text              = trim( wp_strip_all_tags( $remove_shortcodes ) );

		} elseif ( $force ) {
			// If excerpt is set, and $force is set to true, use excerpt.
			$text = trim( $post->post_excerpt );
		} else {
			// If excerpt is set, and $force is not set, just return excerpt without processing.
			return trim( $post->post_excerpt );
		}
	} else {
		// If neither string nor object, just return empty string.
		return '';
	}
	// Split the text into array of words.
	$words = preg_split( '/\s+/', $text );

	// Set defaults for results.
	$closest = array(
		'length'   => 0,
		'distance' => 10000,
	);
	// Loop through the words.
	foreach ( $words as $i => $word ) {
		// Only run on words ending in punctuation.
		if ( preg_match( '/[.!,?]$/', $word ) ) {
			// Calculate the distance from the requested length.
			$distance = abs( $i - $nr );
			// If closer than the previous match.
			if ( $distance <= $closest['distance'] ) {
				// Set this as closest match.
				$closest = array(
					'length'   => $i + 1,
					'distance' => $distance,
				);
			}
		}
	}
	// Default length to requested length.
	$length = $nr;
	if ( abs( $closest['distance'] ) <= $threshold ) {
		// If distance is equal or smaller than threshold, set length to the closest match.
		$length = $closest['length'];
	}
	if ( $length > count( $words ) ) {
		// If length is longer than the whole text, set to whole text length.
		$length = count( $words );
	}

	// Set empty string as output.
	$output = '';
	// Loop all the words to set length.
	for ( $i = 0; $i < $length; $i++ ) {
		// Add the words to output.
		$output .= $words[ $i ] . ' ';
	}
	// If output is shorter than the whole text, add ellipsis.
	if ( count( $words ) > $length ) {
		$output .= $ellipsis;
	}

	// Return the output, with whitespace trim.
	return trim( $output );
}

function tease_class( $post, $nr = null ) {
	$class = 'tease';
	if ( ! empty( $post ) ) {
		$class .= ' tease-' . $post->post_type;
	}
	foreach ( $post->categories as $term ) {
		$class .= ' tease-term-' . $term->slug;
	}
	if ( ! empty( $nr ) ) {
		$class .= ' tease-nr-' . $nr;
	}

	return $class;
}

function get_cats( $taxo = 'category' ) {
	$cats = array();
	foreach ( \Timber::get_terms( $taxo ) as $term ) {
		if ( $term->count > 0 && ! str_starts_with( $term->slug, 'uncategorized' ) ) {
			$cats[] = $term;
		}
	}

	return $cats;
}
