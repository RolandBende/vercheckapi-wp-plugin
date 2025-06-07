# VerCheck API WordPress Plugin
A REST API endpoint for checking WordPress core, theme, and plugin versions.

This plugin adds a custom REST API endpoint that returns information about the current versions of the WordPress core, active themes, and active plugins.

Useful for remote WordPress site version monitoring & logging, CI/CD checks, and automated update workflows.

## WordPress.org Plugin

The plugin package is also downloadable from the official [WordPress.org Plugin Directory](https://wordpress.org/plugins/vercheck-api/).

## How it works

> **Important:** The endpoint requires HTTP Header authentication via a Bearer token. The token must be set first via the WordPress Admin Plugin settings.

Simply make an HTTP request:
- Method: `GET`
- API endpoint: `/wp-json/vercheck-api/v1/status`

The API returns a JSON object with the following data:
- The current WordPress version.
- A list of active plugins with available updates, including their current and latest versions.
- A list of active themes with available updates, including their current and latest versions.

**Example response:**
```json
{
    "core": {
      "current_version": "6.4.3",
      "new_version": "6.5",
      "is_outdated": true
    },
    "outdated_plugins": [
      {
        "name": "Example Plugin",
        "current_version": "1.2.0",
        "new_version": "1.3.0"
      }
    ],
    "outdated_themes": []
}
```
