<?php

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