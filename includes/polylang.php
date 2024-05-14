<?php
/**
 * Functions regarding polylang plugin.
 *
 * @package jcore
 */

/**
 * Turn off translations for reusable blocks
 */
function remove_reusable_block_from_pll( $post_types, $is_settings ) {
	unset( $post_types['wp_block'] );

	return $post_types;
}
