<?php

if (!defined('ABSPATH')) {
    exit;
}

class VC_API_Checks
{
    public function get_core_status()
    {
        global $wp_version;
        $core_update = get_core_updates();

        return [
            'current_version' => $wp_version,
            'new_version' => isset($core_update[0]->version) ? $core_update[0]->version : null,
            'is_outdated' => isset($core_update[0]) && version_compare($wp_version, $core_update[0]->version, '<'),
        ];
    }

    public function get_outdated_themes()
    {
        $themes = wp_get_themes();
        $theme_updates = get_site_transient('update_themes');
        $outdated = [];

        foreach ($themes as $slug => $theme) {
            if (isset($theme_updates->response[$slug])) {
                $update = $theme_updates->response[$slug];
                $outdated[] = [
                    'name' => $theme->get('Name'),
                    'current_version' => $theme->get('Version'),
                    'new_version' => $update['new_version'],
                ];
            }
        }

        return $outdated;
    }

    public function get_outdated_plugins()
    {
        $plugin_updates = get_site_transient('update_plugins');
        $plugins = get_plugins();
        $outdated = [];

        foreach ($plugins as $file => $plugin) {
            if (isset($plugin_updates->response[$file])) {
                $update = $plugin_updates->response[$file];
                $outdated[] = [
                    'name' => $plugin['Name'],
                    'current_version' => $plugin['Version'],
                    'new_version' => $update->new_version,
                ];
            }
        }

        return $outdated;
    }
}
