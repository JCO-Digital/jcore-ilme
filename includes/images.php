<?php

namespace Jcore\Ilme;

// Add mime types.
add_filter( 'upload_mimes', 'Jcore\Ilme\cc_mime_types' );

/**
 * Add SVG to allowed MIME types.
 *
 * @param array $mimes Allowed mime types.
 *
 * @return mixed
 */
function cc_mime_types( $mimes ) {
	// Add SVG as allowed upload.
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

/**
 * Set JPEG quality on resize.
 *
 * @param int    $quality The quality setting passed to the function.
 * @param string $context The type of image.
 *
 * @return int
 */
function jpeg_quality( $quality, $context ) {
	if ( 'image/jpeg' === $context && $quality > 85 ) {
		return $quality;
	}

	return 92;
}

/**
 * Replaces <img> SVG with inline SVG.
 *
 * @param string $html The HTML returned by get_custom_logo.
 *
 * @return string
 */
function get_logo( string $html ) {
	$html = preg_replace( '_<a [^>]*>(.*)</a>_sm', '\1', $html );
	if ( preg_match( '_<img[^>]+src="([^"]+(/wp-content/[^"]+.svg))"[^>]+/>_', $html, $matches ) ) {
		$filename = rtrim( ABSPATH, '/' ) . $matches[2];
		if ( file_exists( $filename ) ) {
			$svg = file_get_contents( $filename );
			$svg = preg_replace( '_<\?xml[^>]+>_', '', $svg );

			return str_replace( $matches[0], '<div class="custom-logo">' . $svg . '</div>', $html );
		}
		// Enables SVG logos to work correctly on local sites.
		if ( wp_get_environment_type() === 'local' ) {
			// Checks the transient first.
			$transient_name = '_custom_svg_logo';
			if ( false !== ( $value = get_transient( $transient_name ) ) ) { // phpcs:ignore
				return $value;
			}
			// Since we are requesting a local site and we are on a local site, we need to ignore SSL errors.
			add_filter( 'https_ssl_verify', '__return_false' );
			$request = wp_safe_remote_get( $matches[1] );
			// Undo the damage we just did.
			remove_filter( 'https_ssl_verify', '__return_false' );
			if ( is_wp_error( $request ) ) {
				return $html;
			}
			$svg = wp_remote_retrieve_body( $request );
			// Fixup the SVG and store it in a transient.
			$svg          = preg_replace( '_<\?xml[^>]+>_', '', $svg );
			$return_value = str_replace( $matches[0], '<div class="custom-logo">' . $svg . '</div>', $html );
			set_transient( $transient_name, $return_value, DAY_IN_SECONDS );

			return $return_value;
		}
	}

	return $html;
}
