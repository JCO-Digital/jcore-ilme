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
