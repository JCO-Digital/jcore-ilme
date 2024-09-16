<?php
/**
 * Timber context.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

use Twig\TwigFunction;
use Twig\TwigFilter;
use Timber\Timber;

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

	foreach ( apply_filters( 'jcore_menus', array() ) as $menu => $name ) {
		$context['menu'][ $menu ] = Timber::get_menu( $menu );
	}

	// $context['imagesizes'] = create_sizes( $GLOBALS['jcore_settings'] );

	$context['logo'] = get_custom_logo();

	// LearnDash support.
	if ( class_exists( 'SFWD_LMS' ) ) {
		$context['learndash_sidebar']       = Timber::get_widgets( 'ld-sidebar' );
		$context['learndash_under_content'] = Timber::get_widgets( 'ld-under-content' );
		$context['menu']['learndash']       = Timber::get_menu( 'learndash' );
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
		$context['referrer']          = wp_unslash( esc_url( $_SERVER['HTTP_REFERER'] ) );
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

/**
 * Add filters for Timber Twig.
 *
 * @param \Twig\Environment $twig The Twig environment.
 *
 * @return \Twig\Environment
 */
function twig( $twig ) {
	$twig->addFilter( new TwigFilter( 'slug', 'Jcore\Ilme\slugify' ) );
	$twig->addFilter( new TwigFilter( 'slugify', 'Jcore\Ilme\slugify' ) );
	$twig->addFilter( new TwigFilter( 'euro', 'Jcore\Ilme\euro_format' ) );
	$twig->addFilter( new TwigFilter( 'preview', 'Jcore\Ilme\post_preview' ) );
	$twig->addFilter( new TwigFilter( 'shorten', 'Jcore\Ilme\title_trim' ) );
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

/**
 * Converts a string into a slug.
 *
 * This function takes a string as input and converts it into a slug. It first replaces any occurrences of the characters 'å', 'ä', and 'ö' with 'a' and 'o' respectively. It then converts the string to lowercase and trims any leading or trailing whitespace. Finally, it replaces any character that is not a lowercase letter or a digit with a dash.
 *
 * @param string $text The input string to be converted into a slug.
 * @return string The resulting slug.
 */
function slugify( $text ) {
	$text = str_replace( array( 'å', 'ä', 'ö' ), array( 'a', 'a', 'o' ), strtolower( trim( $text ) ) );
	$text = preg_replace( '/[^a-z0-9]/', '-', $text );

	return $text;
}

/**
 * Formats a number as a euro value.
 *
 * This function takes a number as input and formats it as a euro value. It uses the number_format function to format the number with two decimal places and a comma as the decimal separator.
 *
 * @param float $value The number to be formatted as a euro value.
 * @return string The formatted euro value.
 */
function euro_format( $value ) {
	return number_format( $value, 2, ',', '' );
}

/**
 * Generates a preview of a post's content or excerpt.
 *
 * This function generates a preview of a post's content or excerpt. It takes a post (either as a string or a WP_Post object) and a number of words to include in the preview. It also accepts an optional threshold for the distance from the requested length at which to cut the preview, an optional string to append to the preview if it is cut, and an optional flag to force the use of the post's excerpt.
 *
 * If the post is a string, it is used as the text to generate the preview from. If the post is a WP_Post object and it has an excerpt, the excerpt is used as the text to generate the preview from. If the post is a WP_Post object and it does not have an excerpt, the post's content is used as the text to generate the preview from. If the post is neither a string nor a WP_Post object, an empty string is returned.
 *
 * The function then splits the text into an array of words and iterates over the array. For each word that ends in punctuation, it calculates the distance from the requested length. If the distance is closer than the previous closest distance, it sets the current distance and length as the closest.
 *
 * If the closest distance is equal to or smaller than the threshold, the length of the preview is set to the closest length. Otherwise, the length of the preview is set to the requested length. If the length of the preview is longer than the whole text, the length is set to the length of the whole text.
 *
 * The function then constructs the preview by concatenating the words up to the determined length. If the length of the preview is shorter than the whole text, it appends the ellipsis to the preview. Finally, it trims any leading or trailing whitespace from the preview and returns it.
 *
 * @param mixed    $post The post to generate a preview for. Can be a string or a WP_Post object.
 * @param int      $nr The number of words to include in the preview. Default is 50.
 * @param int|null $threshold The distance from the requested length at which to cut the preview. Default is 20% of the requested length.
 * @param string   $ellipsis The string to append to the preview if it is cut. Default is an empty string.
 * @param bool     $force Whether to force the use of the post's excerpt if it exists. Default is false.
 * @return string The generated post preview.
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

function title_trim( string $title, int $length = 60, int $threshold = 10 ) {
	if ( strlen( $title ) <= $length ) {
		return $title;
	}
	$return = '';
	foreach ( explode( ' ', $title ) as $word ) {
		$current     = strlen( $return );
		$word_length = strlen( $word );
		if ( $current + $word_length < $length || $length - $current > ( $current + $word_length + 1 ) - $length ) {
			$return .= ' ' . $word;
		} else {
			break;
		}
	}
	if ( abs( strlen( $return ) - $length ) > $threshold ) {
		$return = substr( $title, 0, $length );
	}
	if ( strlen( $return ) < strlen( $title ) ) {
		return $return . '...';
	}
	return $return;
}


/**
 * Returns a class string for a tease.
 *
 * This function takes a post and an optional number as input and returns a class string for a tease. The class string is built from the post type and the categories of the post, and optionally the number.
 *
 * @param \Timber\Post $post The post to build the class string for.
 * @param int|null     $nr   The number to build the class string for.
 * @return string The resulting class string.
 */
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

/**
 * Retrieves categories that are not uncategorized and have at least one post.
 *
 * @param string $taxo The taxonomy to retrieve terms from. Default is 'category'.
 *
 * @return array An array of terms that are not uncategorized and have at least one post.
 */
function get_cats( $taxo = 'category' ) {
	$cats = array();
	foreach ( \Timber::get_terms( $taxo ) as $term ) {
		if ( $term->count > 0 && ! str_starts_with( $term->slug, 'uncategorized' ) ) {
			$cats[] = $term;
		}
	}

	return $cats;
}


function get_children() {
	global $post;
	if ( is_page() ) {
		if ( $post->post_parent ) {
			$ancestors = get_post_ancestors( $post->ID );
			$parent    = $ancestors[ count( $ancestors ) - 1 ];
		} else {
			$parent = $post->ID;
		}
		$args = array(
			'numberposts' => - 1,
			'post_type'   => 'page',
			'post_parent' => $parent,
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
		);

		return Timber::get_posts( $args );
	}
}
