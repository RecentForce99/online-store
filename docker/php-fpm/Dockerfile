FROM php:8.3-fpm-alpine

# PHP and system packages
RUN apk add --no-cache \
    linux-headers \
    curl \
    git \
    build-base \
    zlib-dev \
    oniguruma-dev \
    autoconf \
    bash \
    librdkafka-dev \
    shadow \
    libpq-dev

RUN docker-php-ext-install pdo_pgsql

# Install and enable Kafka extension
RUN pecl install rdkafka \
    && docker-php-ext-enable rdkafka

# Configure non-root user
ARG PUID=1000
ARG PGID=1000
RUN groupmod -o -g ${PGID} www-data \
    && usermod -o -u ${PUID} -g www-data www-data

WORKDIR /var/www
COPY ./ /var/www

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction

# Start PHP-FPM
CMD php-fpm

EXPOSE 9000
