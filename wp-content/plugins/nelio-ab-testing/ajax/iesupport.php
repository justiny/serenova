<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}//end if

add_action( 'wp_ajax_nopriv_nelioab_forward_request_to_backend', 'nelioab_forward_request_to_backend' );
add_action( 'wp_ajax_nelioab_forward_request_to_backend', 'nelioab_forward_request_to_backend' );

function nelioab_forward_request_to_backend() {

	// If we're not processing a POST request...
	if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
		// Silence is gold
		die();
	}//end if

	$method = false;
	$options = false;

	if ( isset( $_POST['backendMethod'] ) ) {
		$method = sanitize_text_field( $_POST['backendMethod'] );
		// Make sure that the method is something like this: «/rn», «/hm»
		if ( ! preg_match( '/\/[a-zA-Z]+/', $method ) ) {
			$method = false;
		}//end if
	}//end if

	if ( isset( $_POST['backendTextOptions'] ) ) {
		$options = sanitize_text_field( $_POST['backendTextOptions'] );
	}//end if

	if ( ! $method || ! $options ) {
		header( 'HTTP/1.1 400 Bad Request' );
		die();
	}//end if

	$args = array(
		'headers'   => array( 'Content-Type' => 'application/x-www-form-urlencoded' ),
		'timeout'   => 30,
		'sslverify' => false,
		'body'      => $options,
	);

	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		$args['headers']['Referer'] = $_SERVER['HTTP_REFERER'];
	}//end if

	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		$args['headers']['User-Agent'] = $_SERVER['HTTP_USER_AGENT'];
	}//end if

	$url = NELIOAB_BACKEND_SERVLET_URL . $method;
	wp_remote_post( $url, $args );
	die();

}//end nelioab_forward_request_to_backend()

