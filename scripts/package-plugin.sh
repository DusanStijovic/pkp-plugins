#!/usr/bin/env bash

set -euo pipefail

if [ "$#" -ne 1 ]; then
    echo "Usage: $0 <plugin-directory>" >&2
    exit 1
fi

PLUGIN_DIR="${1%/}"

if [ ! -d "$PLUGIN_DIR" ]; then
    echo "Plugin directory not found: $PLUGIN_DIR" >&2
    exit 1
fi

PLUGIN_NAME="$(basename "$PLUGIN_DIR")"
VERSION_FILE="$PLUGIN_DIR/version.xml"

if [ ! -f "$VERSION_FILE" ]; then
    echo "Missing version.xml in $PLUGIN_DIR" >&2
    exit 1
fi

RELEASE="$(sed -n 's:.*<release>\(.*\)</release>.*:\1:p' "$VERSION_FILE" | head -n1)"

if [ -z "$RELEASE" ]; then
    echo "Could not read <release> from $VERSION_FILE" >&2
    exit 1
fi

mkdir -p dist
ARCHIVE="dist/${PLUGIN_NAME}-${RELEASE}.zip"

rm -f "$ARCHIVE"
zip -r "$ARCHIVE" "$PLUGIN_DIR" \
    -x '*/.DS_Store' \
    -x '*/node_modules/*' \
    -x '*/.git/*'

echo "$ARCHIVE"
