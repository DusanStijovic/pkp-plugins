#!/usr/bin/env bash

set -euo pipefail

CONFIG_FILE="volumes/config/pkp.config.inc.php"
TEMP_CONFIG=0

cleanup() {
    docker compose down -v || true
    if [ "$TEMP_CONFIG" = "1" ]; then
        rm -f "$CONFIG_FILE"
    fi
}

trap cleanup EXIT

mkdir -p volumes/config volumes/logs/app volumes/logs/db volumes/private volumes/public

if [ ! -f "$CONFIG_FILE" ]; then
    IMAGE_TAG="${PKP_TOOL:-ojs}:${PKP_VERSION:-stable-3_5_0}"
    SOURCE_IMAGE="pkpofficial/${IMAGE_TAG}"

    docker run --rm "$SOURCE_IMAGE" sh -lc 'cat /var/www/html/config.TEMPLATE.inc.php' > "$CONFIG_FILE"

    sed -i 's/^installed = .*/installed = Off/' "$CONFIG_FILE"
    sed -i 's#^base_url = .*#base_url = "http://localhost:8080"#' "$CONFIG_FILE"
    sed -i 's/^driver = .*$/driver = mysqli/' "$CONFIG_FILE"
    sed -i 's/^host = .*$/host = db/' "$CONFIG_FILE"
    sed -i 's/^username = .*$/username = pkp/' "$CONFIG_FILE"
    sed -i 's/^password = .*$/password = changeMePlease/' "$CONFIG_FILE"
    sed -i 's/^name = .*$/name = pkp/' "$CONFIG_FILE"
    TEMP_CONFIG=1
fi

docker compose up -d db app

for _ in $(seq 1 30); do
    HTTP_CODE="$(curl -s -o /dev/null -w '%{http_code}' http://127.0.0.1:8080 || true)"
    if [ "$HTTP_CODE" != "000" ]; then
        break
    fi
    sleep 2
done

curl -sS -I http://127.0.0.1:8080 | tee /tmp/ojs-smoke.headers
docker exec pkp_app_ojs sh -lc 'test -f /var/www/html/plugins/generic/simplePopupButton/index.php'
docker exec pkp_app_ojs sh -lc 'php -l /var/www/html/plugins/generic/simplePopupButton/SimplePopupButtonPlugin.php'
