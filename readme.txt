=== VerCheck API ===
Contributors: rolandbende
Donate link: https://rolandbende.com/
Tags: versioncheck, healthcheck
Requires at least: 5.2
Tested up to: 6.8
Stable tag: 1.0.3
Requires PHP: 7.4
License: GPL-3.0-or-later
License URI: http://www.gnu.org/licenses/gpl.html

A REST API endpoint for checking WordPress core, theme, and plugin versions.

== Description ==

This plugin adds a custom REST API endpoint that returns information about the current versions of the WordPress core, active themes, and active plugins.

Useful for remote WordPress site version monitoring & logging, CI/CD checks, and automated update workflows.

- **HTTP method:** `GET`  
- **API endpoint:** `/wp-json/vercheck-api/v1/status`

**Important:** The endpoint requires authentication via a Bearer token.

The API returns a JSON object with the following data:
- The current WordPress version.
- A list of active plugins with available updates, including their current and latest versions.
- A list of active themes with available updates, including their current and latest versions.

**Example response:**
`
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
`

**Additional info:**
The unique request ID for each API call is returned in the response header:

`X-Request-ID: {{unique-request-id}}`


== Screenshots ==

1. Admin settings

== Changelog ==

= 1.0.3 =
* Bugfix: Response themes & plugin order

= 1.0.2 =
* Bugfix: Removed unnecessary fnc. call (loadtextdomain)

= 1.0.1 =
* PHPCS fixes

= 1.0.0 =
* Initial release. REST API endpoint and admin token settings.