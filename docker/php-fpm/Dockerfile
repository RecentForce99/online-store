FROM php:8.3-fpm-alpine

# PHP packages
RUN apk add --no-cache \
    linux-headers \
    curl \
    git \
    build-base \
    zlib-dev \
    oniguruma-dev \
    autoconf \
    bash

# Postgresql packages
RUN apk add libpq-dev && docker-php-ext-install pdo_pgsql

# Configure non-root user
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Copy application source code
COPY ./ /var/www
WORKDIR /var/www

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction

# Start PHP-FPM
CMD php-fpm

EXPOSE 9000
