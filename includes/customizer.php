<?php
/**
 * JCORE Customizer settings
 *
 * @package Jcore\Ilme
 */

namespace Jcore\Ilme;

use Jcore\Ydin\Settings\Customizer;

Customizer::add_section(
	'article',
	'Article',
	'Settings for articles',
	array(
		'date'   => array(
			'type'    => 'checkbox',
			'label'   => 'Show Date',
			'default' => true,
		),
		'cat'    => array(
			'type'    => 'checkbox',
			'label'   => 'Show Category',
			'default' => true,
		),
		'author' => array(
			'type'    => 'checkbox',
			'label'   => 'Show Author',
			'default' => true,
		),
		'image'  => array(
			'type'    => 'checkbox',
			'label'   => 'Show Image',
			'default' => true,
		),
		'share_button'  => array(
			'type'    => 'checkbox',
			'label'   => 'Share Button',
			'default' => true,
		),
		'social_media_buttons'  => array(
			'type'    => 'checkbox',
			'label'   => 'Social Media Buttons',
			'default' => true,
		),
		'enable_comments'  => array(
			'type'    => 'checkbox',
			'label'   => 'Enable Comments',
			'default' => true,
		),
	),
);

Customizer::add_section(
	'article_highlight',
	'Article Highlight',
	'Choose data displayed. If postcard is not checked, image will be displayed as a square.',
	array(
		'highlight_on_single' => array(
			'type'    => 'checkbox',
			'label'   => 'Highlight on Single',
			'default' => true,
		),
		'date'   => array(
			'type'    => 'checkbox',
			'label'   => 'Show Date',
			'default' => true,
		),
		'author' => array(
			'type'    => 'checkbox',
			'label'   => 'Show Author',
			'default' => true,
		),
		'cat'    => array(
			'type'    => 'checkbox',
			'label'   => 'Show Category',
			'default' => true,
		),
		'image'  => array(
			'type'    => 'checkbox',
			'label'   => 'Show Image',
			'default' => true,
		),
		'postcard'            => array(
			'type'    => 'checkbox',
			'label'   => 'Postcard',
			'default' => true,
		),
		'preview'             => array(
			'type'    => 'checkbox',
			'label'   => 'Show Preview / Excerpt',
			'default' => true,
		),
		'readmore'            => array(
			'type'    => 'checkbox',
			'label'   => 'Read More',
			'default' => true,
		),
		'excerpt_length'      => array(
			'type'    => 'number',
			'label'   => 'Excerpt Length',
			'default' => '23',
		),
		'columns'             => array(
			'type'    => 'number',
			'label'   => 'Columns',
			'default' => '3',
		),
	),
);

Customizer::add_section(
	'filtering',
	'Archive Filtering Settings',
	'Display category filtering as buttons on archive page. Translation in language files.',
	array(
		'taxonomy_filter'          => array(
			'type'    => 'checkbox',
			'label'   => 'Taxonomy Filter',
			'default' => true,
		),
		'text_in_front_of_buttons' => array(
			'type'    => 'checkbox',
			'label'   => 'Text above categories',
			'default' => false,
		),
		'masonry'                  => array(
			'type'    => 'checkbox',
			'label'   => 'Use masonry grid in archive',
			'default' => false,
		),
	),
);
Customizer::add_section(
	'navigation',
	'Navigation',
	'Settings for navigation',
	array(
		'submenu_button_is_link'   => array(
			'type'    => 'checkbox',
			'label'   => 'Submenu buttons are links',
			'default' => false,
		),
		'topbar'   => array(
			'type'    => 'checkbox',
			'label'   => 'Show topbar',
			'default' => false,
		),
		'scroll' => array(
			'type'    => 'checkbox',
			'label'   => 'Scroll',
			'default' => false,
		),
		'sticky' => array(
			'type'    => 'checkbox',
			'label'   => 'sticky',
			'default' => false,
		),
		'spacer' => array(
			'type'    => 'checkbox',
			'label'   => 'spacer',
			'default' => false,
		),
		'search' => array(
			'type'    => 'checkbox',
			'label'   => 'search',
			'default' => false,
		),
	),
);