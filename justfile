# VerCheck API - Project commands
# Usage: just <recipe>
# Requires: https://github.com/casey/just

# List available recipes
default:
    @just --list

# Run PHP CodeSniffer
cs:
    vendor/bin/phpcs

# Run version consistency check (Git tag vs readme.txt vs plugin header)
check:
    ./scripts/plugin-version-check.sh

# Tag and deploy a new release
# Usage: just release 1.1.0
#
# Prerequisites (must be done before running this):
#   1. Bump version in vercheck-api.php
#   2. Bump Stable tag in readme.txt
#   3. Add changelog entry in readme.txt
#   4. Commit and push all changes to main
#
# This recipe will:
#   1. Verify there are no uncommitted changes
#   2. Run PHPCS
#   3. Verify version consistency (tag == readme.txt == plugin header)
#   4. Create the Git tag
#   5. Push the tag to origin (triggers GitHub Actions deploy)
release VERSION:
    #!/usr/bin/env bash
    set -euo pipefail

    echo "🔍 Checking for uncommitted changes..."
    if ! git diff --quiet || ! git diff --cached --quiet; then
        echo "❌ You have uncommitted changes. Please commit and push them first."
        exit 1
    fi

    echo "🔍 Checking for unpushed commits..."
    if [ -n "$(git log origin/$(git branch --show-current)..HEAD)" ]; then
        echo "❌ You have unpushed commits. Please push to origin first."
        exit 1
    fi

    echo "🔍 Running PHPCS..."
    vendor/bin/phpcs
    echo "✅ PHPCS passed."

    echo "🔍 Checking version consistency for {{ VERSION }}..."
    README_VERSION=$(grep -i "Stable tag:" readme.txt | awk -F ': ' '{print $2}' | tr -d '\r' | xargs)
    PLUGIN_VERSION=$(grep -i "Version:" vercheck-api.php | head -n1 | awk -F ': ' '{print $2}' | tr -d '\r' | xargs)

    if [ "$README_VERSION" != "{{ VERSION }}" ] || [ "$PLUGIN_VERSION" != "{{ VERSION }}" ]; then
        echo "❌ Version mismatch!"
        echo "   Requested:   {{ VERSION }}"
        echo "   readme.txt:  $README_VERSION"
        echo "   Plugin file: $PLUGIN_VERSION"
        echo "   Make sure all three match before releasing."
        exit 1
    fi
    echo "✅ Version consistency check passed."

    echo "🏷️  Creating Git tag {{ VERSION }}..."
    git tag {{ VERSION }}

    echo "🚀 Pushing tag {{ VERSION }} to origin..."
    git push origin {{ VERSION }}

    echo "✅ Release {{ VERSION }} tagged and pushed. GitHub Actions will handle the WordPress.org deployment."
