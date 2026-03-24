=== VerCheck API ===
Contributors: rolandbende
Donate link: https://rolandbende.com/
Tags: versioncheck, healthcheck
Requires at least: 5.2
Tested up to: 6.9
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPL-3.0-or-later
License URI: http://www.gnu.org/licenses/gpl.html

A REST API endpoint for checking WordPress core, theme, and plugin versions.

== Description ==

This plugin adds a custom REST API endpoint that returns information about the current versions of the WordPress core, active themes, and active plugins.

Useful for remote WordPress site version monitoring & logging, CI/CD checks, and automated update workflows.

Both endpoints require authentication via a Bearer token.

---

**Endpoint 1: Status** — outdated items only

- **HTTP method:** `GET`
- **API endpoint:** `/wp-json/vercheck-api/v1/status`

Returns only items that have available updates:
- WordPress core update status.
- A list of plugins with available updates.
- A list of themes with available updates.

---

**Endpoint 2: Audit** — full inventory

- **HTTP method:** `GET`
- **API endpoint:** `/wp-json/vercheck-api/v1/audit`

Returns a complete inventory of everything installed:
- WordPress core version info.
- All installed themes (active and inactive) with version and update info.
- All installed plugins (active and inactive) with version and update info.

**Example response — /v1/status:**
`
  {
    "core": {
      "current_version": "6.4.3",
      "new_version": "6.5",
      "is_outdated": true
    },
    "outdated_themes": [],
    "outdated_plugins": [
      {
        "name": "Example Plugin",
        "current_version": "1.2.0",
        "new_version": "1.3.0"
      }
    ]
  }
`

**Example response — /v1/audit:**
`
  {
    "core": {
      "current_version": "6.4.3",
      "new_version": "6.5",
      "is_outdated": true
    },
    "themes": [
      {
        "name": "Twenty Twenty-Four",
        "slug": "twentytwentyfour",
        "current_version": "1.3",
        "new_version": null,
        "is_outdated": false,
        "is_active": true
      }
    ],
    "plugins": [
      {
        "name": "Example Plugin",
        "slug": "example-plugin/example-plugin.php",
        "current_version": "1.2.0",
        "new_version": "1.3.0",
        "is_outdated": true,
        "is_active": true
      }
    ]
  }
`

**Additional info:**
The unique request ID for each API call is returned in the response header:

`X-Request-ID: {{unique-request-id}}`


== Screenshots ==

1. Admin settings

== Changelog ==

= 1.1.0 =
* New: Added `/v1/audit` endpoint — full inventory of all installed themes and plugins with version and update info
* Improvement: Moved Bearer token authentication to `permission_callback` (WordPress REST API best practice)

= 1.0.3 =
* Bugfix: Response themes & plugin order

= 1.0.2 =
* Bugfix: Removed unnecessary fnc. call (loadtextdomain)

= 1.0.1 =
* PHPCS fixes

= 1.0.0 =
* Initial release. REST API endpoint and admin token settings.