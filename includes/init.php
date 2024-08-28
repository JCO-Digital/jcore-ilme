<?php

namespace Jcore\Ilme;

const AUTOLOADER_PATH = ABSPATH . 'vendor/autoload.php';
if ( file_exists( AUTOLOADER_PATH ) ) {
	require_once AUTOLOADER_PATH;
}

if ( function_exists( '\Sentry\init' ) && defined( 'SENTRY_DSN' ) && ! defined( 'JCORE_IS_LOCAL' ) ) {
	\Sentry\init( array( 'dsn' => SENTRY_DSN ) );
}

add_action(
	'after_setup_theme',
	function () {
		load_jcore_textdomain();
	}
);

/**
 * Handles setting the mailer to mailhog if we are in a dev environment.
 *
 * @param PHPMailer $phpmailer The PHPMailer instance.
 *
 * @return void
 */
add_action(
	'phpmailer_init',
	function ( $phpmailer ) {
		// phpcs:disable
        if ( defined( "JCORE_IS_LOCAL" ) && JCORE_IS_LOCAL ) {
			$phpmailer->Host = 'mailhog';
			$phpmailer->Port = 1025;
			$phpmailer->IsSMTP();
		}
        // phpcs:enable
	}
);

/**
 * Handles deactivation of the mailgun plugin when running locally.
 *
 * @return void
 */
add_action(
	'admin_init',
	function () {
		// phpcs:disable
        if ( defined("JCORE_IS_LOCAL") && JCORE_IS_LOCAL ) {
			deactivate_plugins( array( 'mailgun/mailgun.php' ) );
		}
        // phpcs:enable
	}
);


/**
 * Run on init hook.
 *
 * @return void
 */
function init() {
	Customizer::gutenberg_add_colors();

	if ( ! empty( Settings::get( 'keys', 'google_maps_key' ) ) ) {
		acf_update_setting( 'google_api_key', Settings::get( 'keys', 'google_maps_key' ) );
	}
}

/**
 * Translation Support.
 *
 * @return void
 */
function load_jcore_textdomain(): void {
	load_theme_textdomain( 'jcore', get_template_directory() . '/languages' );
}

/**
 * Add ACF fields.
 *
 * @param array $version List of templates.
 *
 * @return mixed
 */
function add_acf_fields( $version = false ) {
	new acf_field_color();
}
