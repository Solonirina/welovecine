FROM php:8.0.15-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y unzip nodejs npm yarn

# Install xdebug 3
COPY ./etc/xdebug.ini $PHP_INI_DIR/conf.d/
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www