#!/bin/bash

set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
DOCKER_DIR="$SCRIPT_DIR/docker"

if [[ ! -f "$DOCKER_DIR/docker-compose.yml" ]]; then
	echo "Missing compose file: $DOCKER_DIR/docker-compose.yml"
	exit 1
fi

if [[ "$1" == "dev" ]]; then
	docker compose \
		-f "$DOCKER_DIR/docker-compose.yml" \
		-f "$DOCKER_DIR/docker-compose-dev.yml" \
		up -d --build
	docker compose \
		-f "$DOCKER_DIR/docker-compose.yml" \
		-f "$DOCKER_DIR/docker-compose-dev.yml" \
		exec -T php sh -lc 'cd /var/www && composer install -o'
else
	docker compose \
		-f "$DOCKER_DIR/docker-compose.yml" \
		up -d --build
	docker compose \
		-f "$DOCKER_DIR/docker-compose.yml" \
		exec -T php sh -lc 'cd /var/www && composer install -o'
fi
