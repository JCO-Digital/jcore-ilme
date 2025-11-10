<?php

/**
 * Register custom spacer styles for Gutenberg
 */
function register_custom_spacer_styles() {
	// Responsive spacer styles
	register_block_style(
		'core/spacer',
		array(
			'name'  => 'sm',
			'label' => __( 'Small', 'jcore' ),
		)
	);
	register_block_style(
		'core/spacer',
		array(
			'name'  => 'md',
			'label' => __( 'Medium', 'jcore' ),
		)
	);
	register_block_style(
		'core/spacer',
		array(
			'name'  => 'lg',
			'label' => __( 'Large', 'jcore' ),
		)
	);
	register_block_style(
		'core/spacer',
		array(
			'name'  => 'xl',
			'label' => __( 'X-Large', 'jcore' ),
		)
	);
}


// Hook the functions to init
add_action( 'init', 'register_custom_spacer_styles' );
