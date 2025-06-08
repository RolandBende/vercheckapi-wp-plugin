<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VERCHECK_API_Core {

	const PLUGIN_NAME            = 'VerCheck API';
	const PLUGIN_TEXT_DOMAIN     = 'vercheck-api';
	const PLUGIN_SLUG            = self::PLUGIN_TEXT_DOMAIN;
	const API_BASE_ROUTE         = self::PLUGIN_TEXT_DOMAIN;
	const API_VERSION            = '1';
	const SETTING_API_AUTH_TOKEN = self::PLUGIN_TEXT_DOMAIN . '_token';

	private $checks;

	public function __construct( $checks_instance ) {
		$this->checks = $checks_instance;
		add_action( 'rest_api_init', array( $this, 'register_rest_endpoint' ) );
	}

	public function register_rest_endpoint() {
		register_rest_route(
			self::API_BASE_ROUTE . '/v' . self::API_VERSION,
			'/status',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'handle_status_request' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	public function handle_status_request( $request ) {
		$auth_header    = $request->get_header( 'authorization' );
		$provided_token = null;
		if ( strpos( $auth_header, 'Bearer ' ) === 0 ) {
			$provided_token = trim( str_replace( 'Bearer ', '', $auth_header ) );
		}
		$saved_token = get_option( self::SETTING_API_AUTH_TOKEN );

		if ( ! $provided_token || $provided_token !== $saved_token ) {
			return new WP_REST_Response( array( 'error' => 'Unauthorized' ), 401 );
		}

		require_once ABSPATH . 'wp-admin/includes/update.php'; // very ugly way to load and reach the following fncs.
		wp_update_themes();
		wp_update_plugins();

		$request_id    = $this->generate_request_id();
		$response_body = array(
			'core'             => $this->checks->get_core_status(),
			'outdated_themes'  => $this->checks->get_outdated_plugins(),
			'outdated_plugins' => $this->checks->get_outdated_themes(),
		);
		$response      = new WP_REST_Response( $response_body );
		$response->header( 'X-Request-ID', $request_id );

		return $response;
	}

	private function generate_request_id() {
		return wp_generate_uuid4();
	}
}
