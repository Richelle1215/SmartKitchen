FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Create tmp directory with proper permissions in storage/framework
RUN mkdir -p /var/www/storage/framework/tmp && \
    chmod -R 777 /var/www/storage/framework/tmp

# Set environment variables for temporary directory
ENV TMPDIR=/var/www/storage/framework/tmp \
    TEMP=/var/www/storage/framework/tmp \
    TMP=/var/www/storage/framework/tmp

# Configure PHP to suppress tempnam() notice (PHP 8.2+ behavior)
RUN echo "error_reporting = E_ALL & ~E_NOTICE & ~E_WARNING" >> /usr/local/etc/php/conf.d/laravel.ini