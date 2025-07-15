FROM php:8.2-fpm

ARG user=www-data
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    libssl-dev \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    jpegoptim optipng pngquant gifsicle \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cambiar esta línea para solo crear usuario si no existe:
RUN id -u $user &>/dev/null || useradd -G www-data,root -u $uid -d /home/$user $user

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

CMD ["php-fpm"]



