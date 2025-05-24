<?php
/**
 * Plugin Name: VerCheck API
 * Description: Version check REST API endpoint for WordPress.
 * Version: 1.0.0
 * Author: Roland Bende
 * Author URI: https://rolandbende.com
 * License: GPLv3
 * Text Domain: vercheck-api
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

define('VERCHECK_API_FILE',      __FILE__);
define('VERCHECK_API_PATH',      realpath(plugin_dir_path(VERCHECK_API_FILE)) . '/');
define('VERCHECK_API_INC_PATH',  realpath(VERCHECK_API_PATH . 'includes/') . '/');

require_once VERCHECK_API_INC_PATH . 'class-vc-api-core.php';
require_once  VERCHECK_API_INC_PATH . 'class-vc-api-checks.php';
if (is_admin()) {
    require_once  VERCHECK_API_INC_PATH . 'class-vc-api-admin.php';
}

new VC_API_Core(new VC_API_Checks());
if (is_admin()) {
    new VC_API_Admin();
}
