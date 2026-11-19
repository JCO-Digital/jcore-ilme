<?php
/**
 * Per-site colours and heading font for the multisite network.
 *
 * Every site in the network runs this theme with the same tokens, except the
 * colours and the heading font: each public site may have a file in
 * `sites/<key>.json` that gives some of theme.json's presets its own values.
 * Because SCSS, templates and blocks use the presets
 * (`var(--wp--preset--color--brand)`, `var(--wp--preset--font-family--heading)`),
 * changing the value per site restyles everything, in the editor too.
 *
 * theme.json holds the first site's values, so its file (`sites/alpha.json`)
 * has only its logos, and the network root has none. Site file format,
 * existing slugs only, nothing new:
 *
 *     {
 *         "palette": { "brand": "#1d3f30" },
 *         "gradients": { "cta": "linear-gradient(…)" },
 *         "custom": { "button": { "hover": { "primary": "#466e47" } } },
 *         "fontFamilies": { "heading": { "name": "…", "fontFamily": "…", "fontFace": [ … ] } },
 *         "logo": "assets/images/logo-beta.svg",
 *         "footerLogo": "assets/images/logo-beta.svg"
 *     }
 *
 * A palette or gradient entry changes the value only; `custom` values are
 * merged key by key into theme.json's settings.custom; a font family entry replaces the
 * whole theme.json family with that slug, so only the site's own font files
 * get an @font-face. `file:./` paths in fontFace work as in theme.json.
 *
 * `logo` is the site's logo when no custom logo is set in the Customizer
 * (see site_logo()); `footerLogo` is its light version for dark backgrounds,
 * used in the footer and on the login screen. An SVG is inlined so `fill="currentColor"` follows the
 * text colour around it.
 *
 * The files live in `sites/`, not `styles/`, so WordPress doesn't offer them
 * as style variations in the Site Editor.
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

defined( 'ABSPATH' ) || exit;

/**
 * The site key of the current site, or null for sites without their own
 * overrides (the network root).
 *
 * Keyed by blog ID rather than domain: the IDs are the same in every
 * environment (the database is pulled from upstream), the domains are not.
 * The two example sites, alpha and beta, are blogs 2 and 3; rename the keys
 * (and `sites/<key>.json`, `assets/favicon/<key>/`) after the project's
 * sites, or change the map with the `jcore_ilme_sites` filter.
 *
 * @return string|null
 */
function site_key(): ?string {
	$sites = apply_filters(
		'jcore_ilme_sites',
		array(
			2 => 'alpha',
			3 => 'beta',
		)
	);

	return $sites[ get_current_blog_id() ] ?? null;
}

/**
 * Read the current site's overrides from `sites/<key>.json`.
 *
 * @return array{palette?: array<string, string>, gradients?: array<string, string>, custom?: array<string, mixed>, fontFamilies?: array<string, array>}
 */
function site_overrides(): array {
	$key = site_key();
	if ( null === $key ) {
		return array();
	}

	$file = get_theme_file_path( "sites/{$key}.json" );
	if ( ! is_readable( $file ) ) {
		return array();
	}

	$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.
	if ( ! is_array( $data ) ) {
		_doing_it_wrong( __FUNCTION__, esc_html( "sites/{$key}.json is not valid JSON." ), '' );
		return array();
	}

	return array_filter(
		array(
			'palette'      => is_array( $data['palette'] ?? null ) ? $data['palette'] : array(),
			'gradients'    => is_array( $data['gradients'] ?? null ) ? $data['gradients'] : array(),
			'custom'       => is_array( $data['custom'] ?? null ) ? $data['custom'] : array(),
			'fontFamilies' => is_array( $data['fontFamilies'] ?? null ) ? $data['fontFamilies'] : array(),
			'logo'         => is_string( $data['logo'] ?? null ) ? $data['logo'] : '',
			'footerLogo'   => is_string( $data['footerLogo'] ?? null ) ? $data['footerLogo'] : '',
		)
	);
}

/**
 * Apply overrides to a preset list by slug.
 *
 * @param array    $presets   The theme.json preset list (or origin-grouped list).
 * @param array    $overrides Slug => override.
 * @param callable $apply     fn( array $preset, mixed $override ): array.
 * @param string   $what      For the error message ("colours", "font families").
 * @return array The plain preset list with the overrides applied.
 */
function override_presets( array $presets, array $overrides, callable $apply, string $what ): array {
	// get_data() groups presets by origin; update_with() takes a plain list.
	$presets = $presets['theme'] ?? $presets;

	foreach ( $presets as $i => $preset ) {
		if ( isset( $overrides[ $preset['slug'] ] ) ) {
			$presets[ $i ] = $apply( $preset, $overrides[ $preset['slug'] ] );
			unset( $overrides[ $preset['slug'] ] );
		}
	}

	// Whatever is left isn't in theme.json: a typo or a slug that was renamed.
	if ( ! empty( $overrides ) ) {
		_doing_it_wrong(
			__FUNCTION__,
			esc_html( sprintf( 'sites/%s.json has %s for slugs that theme.json lacks: %s', site_key(), $what, implode( ', ', array_keys( $overrides ) ) ) ),
			''
		);
	}

	return $presets;
}

/**
 * Give the theme's presets the current site's colours, gradients and fonts.
 *
 * Merged by slug: WP_Theme_JSON replaces a whole preset list on merge, so the
 * full lists are rebuilt with only the site's slugs changed.
 *
 * @param \WP_Theme_JSON_Data $theme_json The theme's theme.json data.
 * @return \WP_Theme_JSON_Data
 */
function apply_site_overrides( $theme_json ) {
	$overrides = site_overrides();
	if ( empty( $overrides ) ) {
		return $theme_json;
	}

	unset( $overrides['logo'], $overrides['footerLogo'] );
	if ( empty( $overrides ) ) {
		return $theme_json;
	}

	$data     = $theme_json->get_data();
	$settings = array();

	if ( ! empty( $overrides['palette'] ) ) {
		$settings['color']['palette'] = override_presets(
			$data['settings']['color']['palette'] ?? array(),
			$overrides['palette'],
			static function ( array $color, $value ): array {
				$color['color'] = (string) $value;
				return $color;
			},
			'colours'
		);
	}

	if ( ! empty( $overrides['gradients'] ) ) {
		$settings['color']['gradients'] = override_presets(
			$data['settings']['color']['gradients'] ?? array(),
			$overrides['gradients'],
			static function ( array $gradient, $value ): array {
				$gradient['gradient'] = (string) $value;
				return $gradient;
			},
			'gradients'
		);
	}

	// Not a preset list: update_with() merges it key by key.
	if ( ! empty( $overrides['custom'] ) ) {
		$settings['custom'] = $overrides['custom'];
	}

	if ( ! empty( $overrides['fontFamilies'] ) ) {
		$settings['typography']['fontFamilies'] = override_presets(
			$data['settings']['typography']['fontFamilies'] ?? array(),
			$overrides['fontFamilies'],
			static function ( array $family, $value ): array {
				return array_merge( (array) $value, array( 'slug' => $family['slug'] ) );
			},
			'font families'
		);
	}

	return $theme_json->update_with(
		array(
			'version'  => $data['version'],
			'settings' => $settings,
		)
	);
}
add_filter( 'wp_theme_json_data_theme', __NAMESPACE__ . '\\apply_site_overrides' );

/**
 * The site's own logo from `sites/<key>.json`, for sites without a custom logo.
 *
 * Kept in the theme rather than the media library, so every environment
 * (local, staging, production) shows the right logo without uploads; a logo
 * chosen in the Customizer still wins. Same markup as get_custom_logo(): a
 * front-page link with a `.custom-logo` inside. SVGs are inlined.
 *
 * @param string $html The HTML from get_custom_logo().
 * @return string
 */
function site_logo( string $html ): string {
	if ( has_custom_logo() ) {
		return $html;
	}

	// The login screen is dark (partials/_login.scss), so it gets the light
	// logo the footer uses; WordPress links it there itself.
	$on_login = did_action( 'login_init' );
	$logo     = site_overrides()[ $on_login ? 'footerLogo' : 'logo' ] ?? '';
	$path     = '' !== $logo ? get_theme_file_path( $logo ) : '';
	if ( '' === $path || ! is_readable( $path ) ) {
		return $html;
	}

	$name = get_bloginfo( 'name', 'display' );
	if ( str_ends_with( $path, '.svg' ) ) {
		$image = '<span class="custom-logo">' . file_get_contents( $path ) . '</span>'; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.
	} else {
		$size  = wp_getimagesize( $path );
		$image = sprintf(
			'<img class="custom-logo" src="%s" alt="%s"%s>',
			esc_url( get_theme_file_uri( $logo ) ),
			esc_attr( $name ),
			$size ? sprintf( ' width="%d" height="%d"', $size[0], $size[1] ) : ''
		);
	}

	if ( $on_login ) {
		return $image;
	}

	return sprintf(
		'<a href="%s" class="custom-logo-link" rel="home" aria-label="%s">%s</a>',
		esc_url( home_url( '/' ) ),
		esc_attr( $name ),
		$image
	);
}
add_filter( 'get_custom_logo', __NAMESPACE__ . '\\site_logo' );

/**
 * Add `site-<key>` to the body classes, for the rare per-site SCSS rule.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function site_body_class( array $classes ): array {
	$key = site_key();
	if ( null !== $key ) {
		$classes[] = "site-{$key}";
	}

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\\site_body_class' );

/**
 * Fill the footer block with the current site's name and logo.
 *
 * The footer part is shared by every site, so its settings can't name one of
 * them: the copyright line gets the site's name and the logo comes from
 * `footerLogo` in `sites/<key>.json` (an SVG with fill="currentColor", which
 * Lohko inlines so it takes the footer's light text colour). Settings made on
 * the block in the template still win.
 *
 * @param array $block The parsed block.
 * @return array
 */
function footer_site_defaults( array $block ): array {
	if ( 'lohko/footer' !== $block['blockName'] ) {
		return $block;
	}

	if ( ! isset( $block['attrs']['copyrightText'] ) ) {
		$block['attrs']['copyrightText'] = get_bloginfo( 'name' );
	}

	$logo = site_overrides()['footerLogo'] ?? '';
	if ( '' !== $logo && empty( $block['attrs']['logo'] ) ) {
		$block['attrs']['logo']    = $logo;
		$block['attrs']['logoAlt'] = $block['attrs']['logoAlt'] ?? get_bloginfo( 'name' );
	}

	return $block;
}
add_filter( 'render_block_data', __NAMESPACE__ . '\\footer_site_defaults' );

/**
 * The current site's favicon from `assets/favicon/<key>/`: favicon.svg,
 * favicon.ico (16, 32, 48) and a 180px apple-touch-icon.png. A site without
 * the folder gets none, and a Site Icon set in WordPress wins.
 *
 * Make them from the logo's mark: a wide mark on a square of the brand colour
 * reads at 16px, and the 16px size in the .ico should be drawn on whole
 * pixels so it stays sharp. The example sites have placeholder marks.
 *
 * @return void
 */
function site_favicon(): void {
	$key = site_key();
	if ( ! $key || has_site_icon() ) {
		return;
	}

	$dir = 'assets/favicon/' . $key;
	if ( ! file_exists( get_theme_file_path( $dir . '/favicon.svg' ) ) ) {
		return;
	}

	printf( '<link rel="icon" href="%s" sizes="any">' . "\n", esc_url( get_theme_file_uri( $dir . '/favicon.ico' ) ) );
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( get_theme_file_uri( $dir . '/favicon.svg' ) ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( get_theme_file_uri( $dir . '/apple-touch-icon.png' ) ) );
}
add_action( 'wp_head', __NAMESPACE__ . '\\site_favicon' );
add_action( 'login_head', __NAMESPACE__ . '\\site_favicon' );
add_action( 'admin_head', __NAMESPACE__ . '\\site_favicon' );
