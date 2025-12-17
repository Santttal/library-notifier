#!/usr/bin/env bash
set -euo pipefail

APP_DIR=${APP_DIR:-/var/www/html}
RUNTIME_DIR="${APP_DIR}/runtime"
ASSETS_DIR="${APP_DIR}/web/assets"
UPLOADS_DIR="${APP_DIR}/web/uploads"

if [ ! -f "${APP_DIR}/vendor/autoload.php" ]; then
    cat <<'MSG'
[php-entrypoint] WARNING: Composer dependencies not found (vendor/autoload.php missing).
[php-entrypoint] Run `composer install` inside the ./app directory (host) or inside this container before serving traffic.
MSG
fi

mkdir -p "${RUNTIME_DIR}" "${ASSETS_DIR}" "${UPLOADS_DIR}"
chmod -R 777 "${RUNTIME_DIR}" "${ASSETS_DIR}" "${UPLOADS_DIR}"

exec "$@"
