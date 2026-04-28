<?php
/**
 * Handlers for initialization of theme.
 *
 * @package Jcore\Ilme
 */

defined( 'ABSPATH' ) || exit;

namespace Jcore\Ilme;

const AUTOLOADER_PATH = ABSPATH . 'vendor/autoload.php';
if ( file_exists( AUTOLOADER_PATH ) ) {
	require_once AUTOLOADER_PATH;
}

require_once get_template_directory() . '/classes/Settings.php';

add_action(
	'after_setup_theme',
	function () {
		// Load Text Domain.
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
		if ( wp_get_environment_type() === 'local' ) {
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
	'environment_changed',
	function ( $environment ) {
		if ( 'local' === $environment ) {
			deactivate_plugins( array( 'mailgun/mailgun.php', 'smtp2go/smtp2go-wordpress-plugin.php' ) );
		}
	}
);

/**
 * Run on init hook.
 *
 * @return void
 */
add_action(
	'init',
	function () {
		// Init Settings class.
		Settings::init();

		// Load theme gutenberg blocks.
		$dir_name = get_stylesheet_directory() . '/dist/blocks';
		if ( is_dir( $dir_name ) ) {
			$dir = new \DirectoryIterator( $dir_name );
			foreach ( $dir as $fileinfo ) {
				if ( ! $fileinfo->isDot() && $fileinfo->isDir() ) {
					register_block_type( $fileinfo->getRealPath() );
				}
			}
		}

		if ( ! empty( Settings::get( 'keys', 'google_maps_key' ) ) ) {
			acf_update_setting( 'google_api_key', Settings::get( 'keys', 'google_maps_key' ) );
		}
	}
);

/**
 * Translation Support.
 *
 * @return void
 */
function load_jcore_textdomain(): void {
	load_theme_textdomain( 'jcore', get_template_directory() . '/languages' );
}
