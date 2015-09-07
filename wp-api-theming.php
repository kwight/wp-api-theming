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
	// Add the post_classes property.
	register_api_field( array( 'post', 'page' ), 'post_classes', array(
		'schema'       => array(
			'type'        => 'array',
			'description' => 'Classes for an individual post or page, determined by post_class().',
			'context'     => array( 'view' ),
		),
		'get_callback' => 'wp_api_theming_get_post_classes',
	) );
}
add_action( 'rest_api_init', 'wp_api_theming_posts_pages_properties' );

/**
 * Return the post classes for a given post or page.
 */
function wp_api_theming_get_post_classes( $object ) {
	return get_post_class( '', $object['id'] );
}
