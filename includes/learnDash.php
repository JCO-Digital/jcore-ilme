<?php

/**
 * JCore LearnDash Integration
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

// Hide LearnDash support template if LearnDash is not installed.
add_filter( 'theme_page_templates', 'Jcore\Ilme\exclude_template_learndash_content' );

/**
 * Hide LearnDash support template if LearnDash is not installed
 *
 * @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
 *
 * @return array Filtered array of page templates.
 */
function exclude_template_learndash_content( $post_templates ) {
	if ( ! class_exists( 'SFWD_LMS' ) ) {
		unset( $post_templates['template-learndash-content.php'] );
	}

	return $post_templates;
}
