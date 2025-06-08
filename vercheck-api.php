<?php
/**
 * VerCheck API
 *
 * @package             VerCheckAPI
 * @author              Roland Bende
 * @copyright           2025 - Roland Bende
 * @license             GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:         VerCheck API
 * Description:         Version check REST API endpoint for WordPress.
 * Version:             1.0.2
 * Requires at least:   5.2
 * Requires PHP:        7.4
 * Author:              Roland Bende
 * Author URI:          https://rolandbende.com
 * License:             GPL-3.0-or-later
 * Text Domain:         vercheck-api
 * Domain Path:         /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'VERCHECK_API_FILE', __FILE__ );
define( 'VERCHECK_API_PATH', realpath( plugin_dir_path( VERCHECK_API_FILE ) ) . '/' );
define( 'VERCHECK_API_INC_PATH', realpath( VERCHECK_API_PATH . 'includes/' ) . '/' );

require_once VERCHECK_API_INC_PATH . 'class-vercheck-api-core.php';
require_once VERCHECK_API_INC_PATH . 'class-vercheck-api-checks.php';
if ( is_admin() ) {
	require_once VERCHECK_API_INC_PATH . 'class-vercheck-api-admin.php';
}

new VERCHECK_API_Core( new VERCHECK_API_Checks() );
if ( is_admin() ) {
	new VERCHECK_API_Admin();
}
