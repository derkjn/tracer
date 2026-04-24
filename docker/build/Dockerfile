FROM composer:2.2 AS composer

FROM php:5.6-apache AS base

RUN sed -i 's|deb.debian.org/debian|archive.debian.org/debian|g' /etc/apt/sources.list \
	&& sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list \
	&& sed -i '/stretch-updates/d' /etc/apt/sources.list \
	&& printf 'Acquire::Check-Valid-Until "false";\nAcquire::AllowInsecureRepositories "true";\nAcquire::AllowDowngradeToInsecureRepositories "true";\n' > /etc/apt/apt.conf.d/99archive \
	&& apt-get -o Acquire::AllowInsecureRepositories=true -o Acquire::AllowDowngradeToInsecureRepositories=true update \
	&& apt-get install -y --allow-unauthenticated --no-install-recommends \
		git \
		unzip \
		libxml2-dev \
	&& docker-php-ext-install -j"$(nproc)" dom xml xmlwriter \
	&& pecl install apcu-4.0.11 \
	&& docker-php-ext-enable apcu \
	&& echo "apc.enable_cli=1" > /usr/local/etc/php/conf.d/apcu-cli.ini \
	&& apt-get purge -y --auto-remove \
	&& rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

FROM base AS dev

RUN pecl install xdebug-2.5.5 \
	&& docker-php-ext-enable xdebug \
	&& { \
		echo "xdebug.remote_enable=1"; \
		echo "xdebug.remote_autostart=1"; \
		echo "xdebug.remote_host=host.docker.internal"; \
		echo "xdebug.remote_port=9000"; \
		echo "xdebug.idekey=VSCODE"; \
	} > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini