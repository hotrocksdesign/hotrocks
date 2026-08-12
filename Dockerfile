FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libmcrypt-dev \
    default-mysql-client \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql bcmath

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /app

COPY composer.json composer.lock* ./

RUN composer install --no-dev --no-scripts --optimize-autoloader

COPY . .

RUN composer dump-autoload --no-dev --optimize

RUN php artisan storage:link || true

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
