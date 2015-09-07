<?php


function wp_api_theming_register_widgets() {
	$collection_args = array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'wp_api_theming_widgets_collection',
		'permission_callback' => '__return_true',
	);
	$resource_args = array(

	);

	//
	register_rest_route( 'wp_api_theming/v1', 'widgets', )
}

add_action('rest_api_init', 'wp_api_theming_register_widgets' );

function wp_api_theming_widgets_collection() {

}

function wp_api_theming_widgets_permission_check() {

}