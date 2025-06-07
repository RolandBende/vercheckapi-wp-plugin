#!/bin/bash

set -e

PLUGIN_FILE="vercheck-api.php"
GIT_TAG=$(git describe --tags --abbrev=0)
README_VERSION=$(grep -i "Stable tag:" readme.txt | awk '{print $3}')
PLUGIN_VERSION=$(grep -i "Version:" $PLUGIN_FILE | head -n1 | awk '{print $2}')

echo "🔖 Git tag:         $GIT_TAG"
echo "📄 readme.txt:     $README_VERSION"
echo "🧩 Plugin version: $PLUGIN_VERSION"

if [[ "$GIT_TAG" != "$README_VERSION" || "$GIT_TAG" != "$PLUGIN_VERSION" ]]; then
  echo "❌ Version mismatch detected!"
  echo "Make sure the Git tag, readme.txt, and plugin file all use the same version."
  exit 1
else
  echo "✅ Version consistency check passed."
fi
