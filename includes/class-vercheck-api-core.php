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
		$namespace = self::API_BASE_ROUTE . '/v' . self::API_VERSION;

		register_rest_route(
			$namespace,
			'/status',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'handle_status_request' ),
				'permission_callback' => array( $this, 'verify_bearer_token' ),
			)
		);

		register_rest_route(
			$namespace,
			'/audit',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'handle_audit_request' ),
				'permission_callback' => array( $this, 'verify_bearer_token' ),
			)
		);
	}

	public function verify_bearer_token( $request ) {
		$auth_header    = $request->get_header( 'authorization' );
		$provided_token = null;

		if ( strpos( $auth_header, 'Bearer ' ) === 0 ) {
			$provided_token = trim( str_replace( 'Bearer ', '', $auth_header ) );
		}

		$saved_token = get_option( self::SETTING_API_AUTH_TOKEN );

		if ( ! $provided_token || $provided_token !== $saved_token ) {
			return new WP_Error( 'rest_forbidden', __( 'Unauthorized', 'vercheck-api' ), array( 'status' => 401 ) );
		}

		return true;
	}

	public function handle_status_request( $request ) {
		require_once ABSPATH . 'wp-admin/includes/update.php';
		wp_update_themes();
		wp_update_plugins();

		$response_body = array(
			'core'            => $this->checks->get_core_status(),
			'outdated_themes' => $this->checks->get_outdated_themes(),
			'outdated_plugins' => $this->checks->get_outdated_plugins(),
		);

		$response = new WP_REST_Response( $response_body );
		$response->header( 'X-Request-ID', $this->generate_request_id() );

		return $response;
	}

	public function handle_audit_request( $request ) {
		require_once ABSPATH . 'wp-admin/includes/update.php';
		wp_update_themes();
		wp_update_plugins();

		$response_body = array(
			'core'    => $this->checks->get_core_status(),
			'themes'  => $this->checks->get_all_themes(),
			'plugins' => $this->checks->get_all_plugins(),
		);

		$response = new WP_REST_Response( $response_body );
		$response->header( 'X-Request-ID', $this->generate_request_id() );

		return $response;
	}

	private function generate_request_id() {
		return wp_generate_uuid4();
	}
}
