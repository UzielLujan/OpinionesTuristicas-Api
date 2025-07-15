FROM php:8.2

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .


RUN composer install --no-interaction --prefer-dist --optimize-autoloader || true
RUN chmod -R 775 storage bootstrap/cache


EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]







