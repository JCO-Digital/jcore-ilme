<?php
/**
 * Handler for singular pages.
 *
 * @package jcore
 */

namespace Jcore\Ilme;

use WP_Query;

// These are the special post types.
$ld_course_types         = array(
	'sfwd-courses',
	'sfwd-lessons',
	'sfwd-topic',
	'sfwd-quiz',
	'sfwd-question',
	'sfwd-assignment',
	'groups',
	'sfwd-essays',
);
$tribe_events_post_types = array(
	'tribe_events',
	'tribe_venue',
	'tribe_organizer',
);
$bbpress_post_types      = array(
	'forum',
	'topic',
	'reply',
);

$context = \Timber::context();

$timber_post = \Timber::get_post();

$templates = array();

$context['post'] = $timber_post;
if ( class_exists( 'SFWD_LMS' ) && in_array( $timber_post->post_type, $ld_course_types, true ) ) {
	$context['show_learndash_sidebar'] = true;
	$context['body_class']            .= ' apply-learndash-styles';
	$templates[]                       = 'single-ld.twig';
} elseif ( class_exists( 'Tribe__Events__Main' ) && in_array( $timber_post->post_type, $tribe_events_post_types, true ) ) {
	$context['body_class'] .= ' apply-tribe-events-styles';
	$templates[]            = 'single-tribe.twig';
} elseif ( class_exists( 'bbPress' ) && in_array( $timber_post->post_type, $bbpress_post_types, true ) ) {
	$context['body_class'] .= ' apply-bbpress-styles';
	$templates[]            = 'single-bbpress.twig';
} elseif ( is_single() ) {
	// Post.
	$templates[] = 'single-' . $timber_post->ID . '.twig';
	$templates[] = 'single-' . $timber_post->post_type . '.twig';
	$templates[] = 'single.twig';

	if ( \Jcore\Ydin\Settings\Customizer::get( 'article', 'group_padding' ) ) {
		$context['main_class'] .= ' group-padding';
	}

	if ( comments_open() && \Jcore\Ydin\Settings\Customizer::get( 'article', 'enable_comments' ) ) {
		$comments_list           = apply_filters( 'jcore_comments_list', get_comments( array( 'post_id' => $timber_post->ID ) ) );
		$context['comment_list'] = wp_list_comments( array( 'echo' => false ), $comments_list );
		wp_enqueue_script( 'comment-reply' );
	}

	$related_types = apply_filters( 'jcore_single_related_post_types', array( 'post' ) );

	if ( in_array( $timber_post->post_type, $related_types, true ) && \Jcore\Ydin\Settings\Customizer::get( 'article_highlight', 'highlight_on_single' ) ) {
		$args               = apply_filters(
			'jcore_single_related_args',
			array(
				'post_type'      => $timber_post->post_type,
				'posts_per_page' => \Jcore\Ydin\Settings\Customizer::get( 'article_highlight', 'columns' ),
				'post__not_in'   => array( $timber_post->ID ),
				'orderby'        => array(
					'date' => 'DESC',
				),
			),
			$timber_post,
		);
		$query              = new WP_Query( $args );
		$context['related'] = new \Timber\PostQuery( $query );
	}
}

if ( strpos( $timber_post->_wp_page_template, 'jcore-' ) === 0 ) {
	$t           = substr( $timber_post->_wp_page_template, 6 );
	$templates[] = 'template-' . $t . '.twig';
}
$templates[] = 'page-' . $timber_post->post_name . '.twig';

if ( is_front_page() ) {
	$templates[] = 'front-page.twig';
}
$templates[] = 'page.twig';
if ( post_password_required() ) {
	$templates = array(
		'password-protected.twig',
	);
}

get_header();
\Timber::render( $templates, $context );
get_footer();
