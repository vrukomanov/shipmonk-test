FROM php:8.2.11-fpm-alpine AS php_upstream
FROM mlocati/php-extension-installer:2 AS php_extension_installer_upstream
FROM composer/composer:2-bin AS composer_upstream

FROM php_upstream AS php_base

RUN apk add --no-cache \
		acl \
        bash \
		fcgi \
		file \
		gettext \
		git \
		poppler-utils \
		make \
        wget \
	;

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=php_extension_installer_upstream --link /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
    install-php-extensions \
		apcu \
		intl \
		opcache \
		zip \
		pcntl \
		sysvsem \
		amqp \
		redis \
		soap \
		sockets \
		bcmath \
		xsl \
        pdo \
        pdo_mysql \
    ;

COPY --link .docker/php/app.ini $PHP_INI_DIR/conf.d/

COPY --link .docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN mkdir -p /var/run/php

COPY --link .docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY --link .docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer_upstream --link /composer /usr/bin/composer

ARG APP_CODE_PATH
WORKDIR $APP_CODE_PATH

# Development
FROM php_base AS php_dev

ENV APP_ENV=dev

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN set -eux; \
	install-php-extensions \
    	xdebug \
    ;

COPY --link .docker/php/xdebug.ini $PHP_INI_DIR/conf.d/

### Symfony CLI


ARG GIT_CONFIG_EMAIL
ARG GIT_CONFIG_NAME

RUN git config --global user.email "${GIT_CONFIG_EMAIL:-noone@nomail.com}" \
    && git config --global user.name "${GIT_CONFIG_NAME:-user}"

RUN wget -O symfony-installer.sh 'https://get.symfony.com/cli/installer' \
    && chmod u+x symfony-installer.sh \
    && ./symfony-installer.sh \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony \
    && rm symfony-installer.sh

RUN chown -R www-data:www-data $APP_CODE_PATH