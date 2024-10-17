<?php
/**
 * Example default block
 *
 * @package Jcore\Ilme\Blocks
 */

namespace Jcore\Ilme\Blocks;

use Jcore\Ydin\Blocks\Block;

/**
 * Example block
 * This is an example of a simple block
 */
class Accordion extends Block {
	/**
	 * The block name, will be transformed to be compliant with Gutenberg.
	 *
	 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-registration/#block-name
	 *
	 * @var string
	 */
	protected static string $name = 'Accordion';
	/**
	 * Block description, can be any string.
	 *
	 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-registration/#description-optional
	 *
	 * @var string
	 */
	protected static string $description = 'Accordion for use in pages';
	/**
	 * Keywords for the block, useful for making the block easily searchable
	 *
	 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-registration/#keywords-optional
	 *
	 * @var array
	 */
	/**
	 * Script to load when block is loaded on page.
	 *
	 * @var string
	 */
	protected string $script_name = 'alpine';

	/**
	 * Path to the script to load on page.
	 *
	 * @var string
	 */
	protected string $script_path = '/dist/js/alpine.js';

	/**
	 * Registers the fields
	 *
	 * @return array
	 */
	public function register_fields(): array {
		return array(
			array(
				'key'               => 'field_66fbd648547d5',
				'label'             => 'First item open',
				'name'              => 'first_item_open',
				'aria-label'        => '',
				'type'              => 'true_false',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'message'           => '',
				'default_value'     => 1,
				'allow_in_bindings' => 0,
				'ui'                => 0,
				'ui_on_text'        => '',
				'ui_off_text'       => '',
			),
			array(
				'key'               => 'field_5d7249c8af035',
				'label'             => 'Accordion',
				'name'              => 'accordion',
				'type'              => 'repeater',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'collapsed'         => '',
				'min'               => 0,
				'max'               => 0,
				'layout'            => 'block',
				'button_label'      => 'Add item',
				'sub_fields'        => array(
					array(
						'key'               => 'field_5d724ad6af036',
						'label'             => 'Item Title',
						'name'              => 'item_title',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'maxlength'         => '',
					),
					array(
						'key'               => 'field_5d724ad6af0363482',
						'label'             => 'Content',
						'name'              => 'item_text',
						'aria-label'        => '',
						'type'              => 'wysiwyg',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'allow_in_bindings' => 0,
						'tabs'              => 'all',
						'toolbar'           => 'basic',
						'media_upload'      => 1,
						'delay'             => 1,
					),
				),
			),
		);
	}
}
