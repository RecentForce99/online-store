FROM php:8.3-fpm-alpine

# Install necessary packages including linux-headers
RUN apk add --no-cache linux-headers curl git build-base zlib-dev oniguruma-dev autoconf bash

# Install Xdebug and enable it
RUN pecl install xdebug && docker-php-ext-enable xdebug
#TODO Check out the context of path. Look at ./docker and current directory
COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

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

# Expose ports for PHP-FPM and Xdebug
EXPOSE 9000 9003
