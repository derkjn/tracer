#!/bin/bash

set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
DOCKER_DIR="$SCRIPT_DIR/docker"
PHPCS_BIN="./vendor/bin/phpcs"

if [[ "$1" == "--fix" ]]; then
	PHPCS_BIN="./vendor/bin/phpcbf"
fi

cd "$SCRIPT_DIR"

docker compose \
	-f "$DOCKER_DIR/docker-compose.yml" \
	-f "$DOCKER_DIR/docker-compose-dev.yml" \
	exec -T php sh -lc "cd /var/www && $PHPCS_BIN --standard=PSR2 --extensions=php src html"