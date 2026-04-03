#!/usr/bin/env bash

set -euo pipefail

# Source .env to get PKP_TOOL and PKP_VERSION
source .env

CONFIG_FILE="volumes/config/pkp.config.inc.php"

cleanup() {
    docker compose down -v || true
}

trap cleanup EXIT

mkdir -p volumes/config volumes/logs/app volumes/logs/db volumes/private volumes/public

# Download config template from official image
IMAGE_TAG="${PKP_TOOL}:${PKP_VERSION}"
SOURCE_IMAGE="pkpofficial/${IMAGE_TAG}"

docker run --rm "$SOURCE_IMAGE" sh -lc 'cat /var/www/html/config.TEMPLATE.inc.php' > "$CONFIG_FILE"

sed -i 's/^installed = .*/installed = Off/' "$CONFIG_FILE"
sed -i 's#^base_url = .*#base_url = "http://localhost:8080"#' "$CONFIG_FILE"
sed -i 's/^driver = .*$/driver = mysqli/' "$CONFIG_FILE"
sed -i 's/^host = .*$/host = db/' "$CONFIG_FILE"
sed -i 's/^username = .*$/username = pkp/' "$CONFIG_FILE"
sed -i 's/^password = .*$/password = changeMePlease/' "$CONFIG_FILE"
sed -i 's/^name = .*$/name = pkp/' "$CONFIG_FILE"

# Use local build (IMAGE_SOURCE=local) for testing the local image
docker compose up -d db app

for _ in $(seq 1 60); do
    HTTP_CODE="$(curl -s -o /dev/null -w '%{http_code}' http://127.0.0.1:8080 || true)"
    if [ "$HTTP_CODE" != "000" ]; then
        break
    fi
    sleep 2
done

curl -sS -I http://127.0.0.1:8080 | tee /tmp/ojs-smoke.headers

# Test all generic plugins
for plugin_dir in plugins/generic/*/; do
    plugin_name=$(basename "$plugin_dir")
    echo "Testing plugin: $plugin_name"
    docker exec pkp_app_ojs sh -lc "test -f /var/www/html/plugins/generic/$plugin_name/index.php" && echo "✓ $plugin_name index.php found"
    docker exec pkp_app_ojs sh -lc "php -l /var/www/html/plugins/generic/$plugin_name/*.php > /dev/null" && echo "✓ $plugin_name PHP syntax valid"
done
