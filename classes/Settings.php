<?php
/**
 * Create some options for the theme.
 *
 * @package jcore
 */

namespace Jcore\Ilme;

use Jcore\Ydin\Settings\Option;

class Settings extends Option {

	/**
	 * Array that contains the "saved" settings.
	 *
	 * @var array
	 */
	protected static array $data = array();

	/**
	 * Array that contains all the fields.
	 *
	 * @var array
	 */
	protected static array $fields = array();

	/**
	 * Initialize things.
	 *
	 * @return void
	 */
	public static function init(): void {
		parent::init();
		static::create_acf();
		add_action( 'acf/init', '\Jcore\Ilme\Settings::add_menu_page' );
	}

	/**
	 * Return the setting definition array.
	 *
	 * @return array[]
	 */
	protected static function get_fields(): array {
		return apply_filters(
			'jcore_init_settings_fields',
			array(
				'keys' => array(
					'title'       => __( 'Keys & IDs', 'jcore' ),
					'description' => __( 'Settings for APIs and other.', 'jcore' ),
					'capability'  => 'manage_options',
					'fields'      => array(
						'google_maps_key'    => array(
							'type'    => 'text',
							'label'   => 'Google Maps Key',
							'default' => '',
						),
						'google_analytics'   => array(
							'type'    => 'text',
							'label'   => 'Google Analytics ID',
							'default' => '',
						),
						'google_tag_manager' => array(
							'type'    => 'text',
							'label'   => 'Google Tag Manager ID',
							'default' => '',
						),
						'matomo_tag_manager' => array(
							'type'        => 'text',
							'label'       => 'Matomo Tag Manager Uri',
							'placeholder' => 'Example: https://analytics.bojaco.com/js/container_392oAHyE.js',
							'default'     => '',
						),
					),
				),
			)
		);
	}

	/**
	 * Method to get values for settings.
	 *
	 * @param string $field_name The name of the field to get.
	 * @param mixed  $default    Default value to return if not set.
	 *
	 * @return mixed
	 */
	protected static function get_value( string $field_name, mixed $default ): mixed {
		return function_exists( 'get_field' ) ? get_field( $field_name, 'option' ) : $default;
	}

	/**
	 * Add the settings page.
	 *
	 * @return void
	 */
	public static function add_menu_page(): void {
		foreach ( static::$fields as $key => $group ) {
			acf_add_options_sub_page(
				array(
					'page_title'  => $group['description'],
					'menu_title'  => $group['title'],
					'menu_slug'   => $key,
					'capability'  => $group['capability'],
					'parent_slug' => 'options-general.php',
				)
			);
		}
	}

	/**
	 * Create ACF fields.
	 *
	 * @return void
	 */
	private static function create_acf(): void {
		foreach ( static::$fields as $key => $group ) {

			$fields = array();
			foreach ( $group['fields'] as $name => $field ) {
				$fields[] = array(
					'key'           => self::get_field_name( $name, $key ),
					'label'         => $field['label'],
					'name'          => self::get_field_name( $name, $key ),
					'type'          => $field['type'],
					'placeholder'   => $field['placeholder'] ?? '',
					'wrapper'       => array(
						'width' => $field['width'] ?? '100',
						'class' => '',
						'id'    => '',
					),
					'default_value' => $field['default'],
				);
			}

			if ( function_exists( 'acf_add_local_field_group' ) ) {
				acf_add_local_field_group(
					array(
						'key'                   => 'jcore_settings_' . $key,
						'title'                 => $group['title'],
						'fields'                => $fields,
						'location'              => array(
							array(
								array(
									'param'    => 'options_page',
									'operator' => '==',
									'value'    => $key,
								),
							),
						),
						'menu_order'            => 0,
						'position'              => 'normal',
						'style'                 => 'default',
						'label_placement'       => 'top',
						'instruction_placement' => 'label',
						'hide_on_screen'        => '',
						'active'                => true,
						'description'           => '',
						'show_in_rest'          => 0,
					)
				);
			}
		}
	}
}
