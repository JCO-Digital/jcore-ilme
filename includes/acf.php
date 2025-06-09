<?php
/**
 * ACF Functionality and integration
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

/**
 * Handles setting the location for ACF field groups
 *
 * @return string
 */
function field_save_location(): string {
	return acf_path( 'fields' );
}
add_filter( 'acf/settings/save_json/type=acf-field-group', 'Jcore\Ilme\field_save_location', 5, 0 );

/**
 * Handles setting the location for ACF Post Types.
 *
 * @return string
 */
function post_type_save_location(): string {
	return acf_path( 'post-types' );
}
add_filter( 'acf/settings/save_json/type=acf-post-type', 'Jcore\Ilme\post_type_save_location', 5, 0 );

/**
 * Handles setting the location for ACF Taxonomies.
 *
 * @return string
 */
function taxonomy_save_location(): string {
	return acf_path( 'taxonomy' );
}
add_filter( 'acf/settings/save_json/type=acf-taxonomy', 'Jcore\Ilme\taxonomy_save_location', 5, 0 );

/**
 * Handles setting the name for the ACF JSON files to save.
 *
 * @param string $filename The name of the file to save.
 * @param array  $post The data for the current ACF "post" to save.
 * @param string $load_path The path where the ACF "post" is being loaded from.
 * @return string
 */
function acf_save_name( string $filename, array $post, string $load_path ): string {
	$our_paths = array(
		acf_path( 'fields' ),
		acf_path( 'post-types' ),
		acf_path( 'taxonomy' ),
	);
	if ( ! empty( $load_path ) ) {
		$load_path = dirname( $load_path );
		if ( ! in_array( $load_path, $our_paths, true ) ) {
			return $filename;
		}
	}
	if ( isset( $post['post_type'] ) && str_contains( $filename, 'post_type' ) ) {
		return 'post_type_' . $post['post_type'] . '.json';
	}
	if ( isset( $post['taxonomy'] ) && str_contains( $filename, 'taxonomy' ) ) {
		return 'taxonomy_' . $post['taxonomy'] . '.json';
	}
	if ( isset( $post['title'] ) && str_contains( $filename, 'group_' ) ) {
		$sanitized_title = sanitize_title( $post['title'] );
		return 'fields_' . $sanitized_title . '.json';
	}
	return $filename;
}
add_filter( 'acf/json/save_file_name', 'Jcore\Ilme\acf_save_name', 10, 3 );

/**
 * Handles appending ACF JSON files custom paths to the ACF JSON file paths that will be loaded.
 *
 * @param array $paths The current list of paths to load ACF JSON files from.
 * @return array
 */
function load_paths( array $paths ): array {
	$paths[] = acf_path( 'fields' );
	$paths[] = acf_path( 'post-types' );
	$paths[] = acf_path( 'taxonomy' );

	return $paths;
}
add_filter( 'acf/settings/load_json', 'Jcore\Ilme\load_paths', 5, 1 );

/**
 * Handles creating the save path for ACF JSON files.
 *
 * @param string $name The name of the type of options to save. e.g. post_type, taxonomy, etc.
 * @return string
 */
function acf_path( string $name ): string {
	$field_path = untrailingslashit( get_stylesheet_directory() ) . '/acf-json/' . $name;
	if ( ! is_dir( $field_path ) ) {
		wp_mkdir_p( $field_path );
	}
	return $field_path;
}