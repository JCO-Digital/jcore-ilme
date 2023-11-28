<?php

namespace Jcore\Ilme;

use Timber\Timber;
use Jcore\Ydin\Settings\Customizer;

/**
 * Checks if post type has dynamic archive, and returns settings if it has.
 *
 * @param string $post_type The post type to check.
 *
 * @return array
 */
function archive_settings( string $post_type ): array {
	$fields = array();
	if ( Customizer::get( 'article_highlight', 'date' ) ) {
		$fields[] = 'date';
	}
	if ( Customizer::get( 'article_highlight', 'author' ) ) {
		$fields[] = 'author';
	}
	if ( Customizer::get( 'article_highlight', 'cat' ) ) {
		$fields[] = 'category';
	}
	$args = array(
		'dynamic'     => true,
		'show_search' => false,
		'show_sort'   => false,
		'sort_by'     => 'date',
		'fields'      => $fields,
		'radio'       => true,
		'filtering'   => Customizer::get( 'filtering', 'taxonomy_filter' ),
		'masonry'     => Customizer::get( 'filtering', 'masonry' ),
		'columns'     => Customizer::get( 'article_highlight', 'columns' ),
		'read_more'   => Customizer::get( 'article_highlight', 'readmore' ) ? __( 'Read more', 'jcore' ) : '',
		'per_page'    => get_option( 'posts_per_page', 8 ),
		'has_link'    => true,
	);

	$settings = apply_filters( 'jcore_archive_settings_' . $post_type, array() );
	if ( ! empty( $settings ) ) {
		$args = wp_parse_args( $settings, $args );
	}

	return $args;
}

/**
 * Populates Timber context with setting for a post type.
 *
 * @param array  $context   Timber context.
 * @param string $post_type The post type to build context for.
 *
 * @return array
 */
function archive_context( array $context, string $post_type ): array {
	$settings = archive_settings( $post_type );

	// Populate context.
	$context['dynamic-archive'] = $settings['dynamic'];
	$context['filtering']       = $settings['filtering'];
	$context['show_search']     = $settings['show_search'];
	$context['show_sort']       = $settings['show_sort'];
	$context['sort_by']         = $settings['sort_by'];
	$context['taxonomy']        = $settings['taxonomy'] ?? array();
	$context['fields']          = $settings['fields'];
	$context['radio']           = $settings['radio'] ? 1 : 0;
	$context['masonry']         = $settings['masonry'];
	$context['read_more']       = $settings['read_more'];
	$context['columns']         = $settings['columns'];
	$context['per_page']        = $settings['per_page'];
	$context['has_link']        = $settings['has_link'] ? 1 : 0;

	return $context;
}

/**
 * Run a filter late on the terms to filter out any negative "order" values.
 */
add_filter(
	'jcore_tease_terms', // Jcore filter name.
	function ( $terms ) {
		// WP Filter: Run the array through a PHP filter function.
		return array_values( // Call array values to reindex filtered array.
			array_filter(
				$terms, // Array to filter.
				function ( $term ) {
					// PHP Filter Callback: Return false on terms with negative "order" values.
					return $term['order'] >= 0;
				}
			)
		);
	},
	20 // Use priority 20 to run this after other custom filters.
);

/*
 * Endpoints.
 */

/**
 * @param \WP_Request $request The request coming from the rest handler.
 *
 * @return \WP_REST_Response
 */
function get_items( $request ): \WP_REST_Response {
	$response = new \WP_REST_Response();
	$type     = $request->get_param( 'type' );

	$settings = archive_settings( $type );
	if ( false === $settings ) {
		$response->set_status( 404 );

		return $response;
	}

	$page     = $request->get_param( 'page' );
	$lang     = strtolower( $request->get_param( 'lang' ) );
	$search   = strtolower( $request->get_param( 'search' ) );
	$year     = $request->get_param( 'year' );
	$month    = $request->get_param( 'month' );
	$order    = strtoupper( $request->get_param( 'order' ) );
	$per_page = $request->get_param( 'posts' ) * 2;
	$sort     = $request->get_param( 'sort' );

	$args = array(
		'suppress_filters' => false,
		'post_type'        => $type,
		'post_status'      => 'publish',
		'posts_per_page'   => $per_page,
		'paged'            => $page,
	);

	$search_taxonomies = array();
	foreach ( $settings['taxonomy'] as $taxo ) {
		$terms                      = $request->get_param( $taxo ) ?? '';
		$search_taxonomies[ $taxo ] = preg_split( '/,/', $terms, 0, PREG_SPLIT_NO_EMPTY );
	}

	$tax_query = array();
	foreach ( $search_taxonomies as $taxo => $terms ) {
		if ( ! empty( $terms ) ) {
			$tax_query[] = array(
				'taxonomy' => $taxo,  // Taxonomy slug.
				'terms'    => $terms, // Taxonomy term slug.
				'field'    => 'slug', // Taxonomy field.
			);
		}
	}

	if ( ! empty( $tax_query ) ) {
		$tax_query['relation'] = 'OR';
		$args['tax_query']     = $tax_query;
	}

	if ( ! empty( $search ) ) {
		$args['s'] = $search;
	}
	if ( ! empty( $year ) ) {
		$date_query = array(
			'year' => $year,
		);
		if ( ! empty( $month ) ) {
			$date_query['month'] = $month;
		}
		$args['date_query'] = array(
			$date_query,
		);
	}

	// Fetch language versions is type is translated.
	if ( function_exists( 'pll_is_translated_post_type' ) && pll_is_translated_post_type( $type ) ) {
		$args['lang'] = $lang;
	}

	if ( 'ASC' === $order || 'DESC' === $order ) {
		if ( 'date' === $sort ) {
			$args['orderby'] = 'post_date';
		} else {
			$args['orderby'] = 'post_title';
		}
		$args['order'] = $order;
	}

	$query   = new \WP_Query( $args );
	$items   = array();
	$context = Timber::get_context();

	foreach ( $query->get_posts() as $post ) {
		$terms = array();
		foreach ( $settings['taxonomy'] as $taxo ) {
			$temp       = array();
			$added      = array();
			$post_terms = get_the_terms( $post->ID, $taxo );
			if ( false !== $post_terms ) {
				// Loop the found terms.
				foreach ( $post_terms as $term ) {
					// If term is not already added, add it.
					if ( ! in_array( $term->term_id, $added, true ) ) {
						do {
							// Added term.
							$temp[] = array(
								'name' => html_entity_decode( $term->name ),
								'slug' => $term->slug,
							);
							// Marked as added.
							$added[] = $term->term_id;
							// Check if term has parent.
							if ( $term->parent > 0 ) {
								// Set term to parent term.
								$term = get_term( $term->parent );
							} else {
								// Set term to null if no parent to break loop.
								$term = null;
							}
						} while ( $term !== null );
					}
				}
			}
			$terms[ $taxo ] = $temp;
		}

		$search_terms = array();
		if ( ! empty( $search ) ) {
			$search_terms[] = $search;
		}

		$context['terms'] = $terms;
		$context['post']  = Timber::get_post( $post );
		$templates        = apply_filters(
			'jcore_tease_templates',
			array(
				'partials/tease-dynamic-' . $post->post_type . '.twig',
				'partials/tease-dynamic.twig',
				'partials/tease-' . $post->post_type . '.twig',
				'partials/tease-content.twig',
			),
			$post
		);
		$item             = apply_filters(
			'jcore_tease_item',
			array(
				'id'     => $post->ID,
				'lang'   => $lang,
				'title'  => $post->post_title,
				'date'   => date_format( date_create( $post->post_date_gmt ), 'c' ),
				'terms'  => $terms,
				'search' => $search_terms,
				'html'   => Timber::fetch( $templates, $context ),
			),
			$post,
		);
		$items[]          = $item;
	}

	$data = array(
		'total' => $query->found_posts,
		'page'  => $page,
		'items' => $items,
	);

	$response->set_data( $data );

	return $response;
}

/**
 * Return terms for taxonomy.
 *
 * @param $request
 *
 * @return \WP_REST_Response
 */
function get_item_terms( $request ): \WP_REST_Response {
	$response = new \WP_REST_Response();
	$params   = $request->get_params();

	$terms = get_archive_terms( $params['type'] );

	if ( is_wp_error( $terms ) ) {
		$response->set_status( 404 );
		$response->set_data( $terms );
	} else {
		$response->set_data(
			array(
				'terms' => $terms,
			),
		);
	}

	return $response;
}

/**
 * Get terms for taxonomy.
 *
 * @param string $taxonomy
 *
 * @return array|WP_Error
 */
function get_archive_terms( string $taxonomy ) {
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		)
	);
	if ( is_wp_error( $terms ) ) {
		return $terms;
	}

	$data       = array();
	$sort_order = 0;
	foreach ( $terms as $term ) {
		$data[] = apply_filters(
			'jcore_tease_term',
			array(
				'id'     => $term->term_id,
				'name'   => html_entity_decode( $term->name ),
				'slug'   => $term->slug,
				'parent' => $term->parent,
				'order'  => $sort_order++,
			),
			$term
		);
	}

	return apply_filters( 'jcore_tease_terms', $data );
}


/**
 * Get years and months that have posts.
 *
 * @param $request
 *
 * @return \WP_REST_Response
 */
function get_months( $request ): \WP_REST_Response {
	global $wpdb;
	$response = new \WP_REST_Response();
	$lang     = strtolower( $request->get_param( 'lang' ) );

	$args = array(
		'post_type' => $request->get_param( 'type' ),
	);
	if ( ! empty( $lang ) ) {
		$args['lang'] = $lang;
	}

	if ( ! post_type_exists( $args['post_type'] ) ) {
		$response->set_status( 404 );

		return $response;
	}

	$sql_where = $wpdb->prepare( "WHERE post_type = %s AND post_status = 'publish'", $args['post_type'] );

	/**
	 * Filters the SQL WHERE clause for retrieving archives.
	 *
	 * @param string $sql_where Portion of SQL query containing the WHERE clause.
	 * @param array  $args      An array of default arguments.
	 *
	 * @since 2.2.0
	 */
	$where = apply_filters( 'getarchives_where', $sql_where, $args );

	/**
	 * Filters the SQL JOIN clause for retrieving archives.
	 *
	 * @param string $sql_join Portion of SQL query containing JOIN clause.
	 * @param array  $args     An array of default arguments.
	 *
	 * @since 2.2.0
	 */
	$join = apply_filters( 'getarchives_join', '', $args );

	$last_changed = wp_cache_get_last_changed( 'posts' );

	$query   = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
	$key     = md5( $query );
	$key     = "wp_get_archives:$key:$last_changed";
	$results = wp_cache_get( $key, 'posts' );
	if ( ! $results ) {
		$results = $wpdb->get_results( $query );
		wp_cache_set( $key, $results, 'posts' );
	}

	$year   = null;
	$months = array();
	$data   = array();
	foreach ( $results as $post ) {
		if ( $post->year !== $year ) {
			if ( $year !== null ) {
				$data[] = array(
					'year'   => $year,
					'months' => $months,
				);
			}
			$months = array();
			$year   = $post->year;
		}
		$months[] = $post->month;
	}

	$response->set_data( $data );

	return $response;
}

/**
 * Get settings for a post type.
 *
 * @param $request
 *
 * @return \WP_REST_Response
 */
function get_archive_settings( $request ): \WP_REST_Response {
	$response = new \WP_REST_Response();
	$type     = $request->get_param( 'type' );

	if ( post_type_exists( $type ) ) {
		$settings = archive_settings( $type );

		$terms = array();
		foreach ( $settings['taxonomy'] as $taxo ) {
			$data = get_archive_terms( $taxo );
			if ( ! is_wp_error( $data ) ) {
				$terms[ $taxo ] = $data;
			}
		}
		$settings['terms'] = $terms;

		$response->set_data( $settings );
	} else {
		$response->set_status( 404 );
		$response->set_data( array( 'error' => 'Post type does not exist.' ) );
	}

	return $response;
}
