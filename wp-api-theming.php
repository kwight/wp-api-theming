<?php
/**
 * Plugin Name: WP-API Theming
 * Description: Modifications to the WP-API plugin (`develop` branch) for theming.
 * Author: kwight
 * Author URI: http://kwight.ca
 * Version: 0.1
 * Plugin URI: https://github.com/kwight/wp-api-theming
 * License: GPL2+
 */

/**
 * Add properties to posts and pages endpoints.
 */
function wp_api_theming_posts_pages_properties() {
	// Add more detailed author info.
	register_api_field( array( 'post', 'page' ), 'author', array(
		'schema'       => array(
			'type'        => 'array',
			'description' => 'Detailed author information.',
			'context'     => array( 'view' ),
		),
		'get_callback' => 'wp_api_theming_get_author_info',
	) );

	// Add the post_classes property.
	register_api_field( array( 'post', 'page' ), 'post_classes', array(
		'schema'       => array(
			'type'        => 'array',
			'description' => 'Classes for an individual post or page, determined by post_class().',
			'context'     => array( 'view' ),
		),
		'get_callback' => 'wp_api_theming_get_post_classes',
	) );

	// Add the categories property.
	register_api_field( array( 'post', 'page' ), 'categories', array(
		'schema'       => array(
			'type'        => 'array',
			'description' => 'Categories for an individual post or page.',
			'context'     => array( 'view' ),
		),
		'get_callback' => 'wp_api_theming_get_post_categories',
	) );

	// Add the tags property.
	register_api_field( array( 'post', 'page' ), 'tags', array(
		'schema'       => array(
			'type'        => 'array',
			'description' => 'Tags for an individual post or page.',
			'context'     => array( 'view' ),
		),
		'get_callback' => 'wp_api_theming_get_post_tags',
	) );
}
add_action( 'rest_api_init', 'wp_api_theming_posts_pages_properties' );

/**
 * Fetch additional author information.
 */
function wp_api_theming_get_author_info( $object ) {
	// Grab the author's raw data.
	$author = get_userdata( $object['author'] );

	// Return only what we want.
	return array(
		'id'      => $author->ID,
		'archive_link' => get_author_posts_url( $author->ID ),
		'name'    => $author->data->display_name,
	);
}

/**
 * Return the post classes for a given post or page.
 */
function wp_api_theming_get_post_classes( $object ) {
	return get_post_class( '', $object['id'] );
}

/**
 * Get categories for a given post or page.
 */
function wp_api_theming_get_post_categories( $object ) {
	return wp_api_theming_get_post_terms( $object['id'] );
}

/**
 * Get tags for a given post or page.
 */
function wp_api_theming_get_post_tags( $object ) {
	return wp_api_theming_get_post_terms( $object['id'], 'post_tag' );
}

/**
 * Get a post's terms with archive links.
 */
function wp_api_theming_get_post_terms( $id = false, $taxonomy = 'category' ) {
	// We need an ID for this one.
	if ( ! $id ) {
		return false;
	}

	// Validate the taxonomy argument.
	$valid_tax = apply_filters( 'wp_api_theming_valid_tax', array( 'category', 'post_tag' ) );
	$taxonomy = ( in_array( $taxonomy, $valid_tax ) ) ? $taxonomy : 'category';

	// Fetch our terms.
	$terms = wp_get_post_terms( absint( $id ), $taxonomy );

	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		// Append a link property to each term.
		foreach ( $terms as $term ) {
			$link = get_term_link( $term );
			$term->link = $link;
		}
	}

	return $terms;
}
