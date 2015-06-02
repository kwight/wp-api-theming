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
function wp_api_theming_posts_properties() {
	// Assemble schema info.
	$schema = array(
		'type'        => 'array',
		'description' => 'Classes for an individual post or page, determined by post_class().',
		'context'     => array( 'view' ),
	);

	// Add the post_classes property to posts and pages.
	register_api_field( array( 'post', 'page' ), 'post_classes', array(
		'schema'       => $schema,
		'get_callback' => 'wp_api_theming_get_post_classes',
	) );
}
add_action( 'rest_api_init', 'wp_api_theming_posts_properties' );

/**
 * Get a post's terms with archive links.
 */
function wp_api_theming_get_post_classes( $post ) {
	// We need an ID for this one.
	$classes = get_post_class( '', $post->id );

	return $classes;
}
