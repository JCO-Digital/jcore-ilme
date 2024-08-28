<?php

namespace Jcore\Ilme;

/**
 * Add jcore virtual templates to template stack.
 *
 * @param array $post_templates List of templates.
 *
 * @return mixed
 */
function jcore_templates( $post_templates ) {
	// TODO Temporary fix for missing ACF.
	if ( isset( $GLOBALS['jcore_settings'] ) ) {
		foreach ( $GLOBALS['jcore_settings']->templates as $template => $name ) {
			$post_templates[ 'jcore-' . $template ] = $name;
		}
		if ( $GLOBALS['jcore_settings']->vue['enabled'] ) {
			$post_templates['jcore-vue'] = 'Vue App';
		}
	}

	return $post_templates;
}
