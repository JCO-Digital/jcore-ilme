<?php
/**
 * Stub that includes archive.php
 */

if ( file_exists( get_stylesheet_directory() . '/archive.php' ) ) {
	// We need to handle WP child inheritance here.
	include get_stylesheet_directory() . '/archive.php';
} else {
	include get_template_directory() . '/archive.php';
}
