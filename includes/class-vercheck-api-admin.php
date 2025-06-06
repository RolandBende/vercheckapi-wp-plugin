<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VERCHECK_API_Admin {


	private $plugin_name;
	private $plugin_slug;
	private $token_option_name;

	public function __construct() {
		$this->plugin_name       = VERCHECK_API_Core::PLUGIN_NAME;
		$this->plugin_slug       = VERCHECK_API_Core::PLUGIN_SLUG;
		$this->token_option_name = VERCHECK_API_Core::SETTING_API_AUTH_TOKEN;
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_settings_page() {
		add_options_page(
			// translators: %s is the plugin name.
			sprintf( __( '%s Settings', 'vercheck-api' ), $this->plugin_name ),
			$this->plugin_name,
			'manage_options',
			$this->plugin_slug,
			array( $this, 'render_settings_page' )
		);
	}

	public function register_settings() {
		register_setting(
			$this->plugin_slug . '_settings',
			$this->token_option_name,
			array( $this, 'validate_token' )
		);
		add_settings_section(
			$this->plugin_slug . '_main',
			'API Token',
			null,
			$this->plugin_slug
		);
		add_settings_field(
			$this->token_option_name,
			'Token',
			array( $this, 'render_token_field' ),
			$this->plugin_slug,
			$this->plugin_slug . '_main',
			array(
				'description' => __( 'Token must be at least 32 characters long and contain only valid characters (letters, numbers, dashes, underscores, dots).', 'vercheck-api' ),
			)
		);
	}

	public function render_settings_page() {
		?>
		<div class="wrap">
			<h1>
				<?php
					printf(
						// translators: %s is the plugin name.
						esc_html__( '%s Settings', 'vercheck-api' ),
						esc_html( $this->plugin_name )
					);
				?>
			</h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( $this->plugin_slug . '_settings' );
				do_settings_sections( $this->plugin_slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function render_token_field( $args ) {
		$token = get_option( $this->token_option_name );
		echo "<input type='text' name='" . esc_attr( $this->token_option_name ) . "' value='" . esc_attr( $token ) . "' size='40' />";
		if ( ! empty( $args['description'] ) ) {
			echo "<p class='description'>" . esc_html( $args['description'] ) . '</p>';
		}
	}

	public function validate_token( $input ) {
		$input = trim( $input );

		if ( strlen( $input ) < 32 ) {
			add_settings_error(
				$this->token_option_name,
				'token_too_short',
				__( 'The token must be at least 32 characters long.', 'vercheck-api' ),
				'error'
			);
			return '';
		}

		if ( ! preg_match( '/^[a-zA-Z0-9._-]+$/', $input ) ) {
			add_settings_error(
				$this->token_option_name,
				'token_invalid_chars',
				__( 'The token contains invalid characters. Allowed are letters, numbers, dots, dashes and underscores.', 'vercheck-api' ),
				'error'
			);
			return '';
		}

		return $input;
	}
}
