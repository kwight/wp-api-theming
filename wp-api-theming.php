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
function wp_api_theming_posts_properties( $response ) {
	// Set author's name.
	$author = get_userdata( $response->data['author'] );
	$response->data['author'] = array(
		'id' => $author->ID,
		'link' => get_author_posts_url( $author->ID ),
		'name' => $author->data->display_name,
	);

	// Add post classes.
	$response->data['post_class'] = get_post_class( $response->data['id'] );

	// Add categories.
	$categories = wp_api_theming_get_post_terms( $response->data['id'], 'category' );
	if ( ! empty( $categories ) ) {
		$response->data['categories'] = $categories;
	}

	// Add tags.
	$tags = wp_api_theming_get_post_terms( $response->data, 'post_tag' );
	if ( ! empty( $tags ) ) {
		$response->data['tags'] = $tags;
	}

	return $response;
}
add_filter( 'json_prepare_post', 'wp_api_theming_posts_properties' );
add_filter( 'json_prepare_page', 'wp_api_theming_posts_properties' );

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




