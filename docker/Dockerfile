# [cite_start]Imagen base con PHP 8.2 FPM [cite: 28]
FROM php:8.2-fpm

# Argumentos (no te preocupes por estos ahora, son para permisos)
ARG user
ARG uid

# Instalar dependencias del sistema
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
    jpegoptim optipng pngquant gifsicle

# Limpiar caché
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# [cite_start]Instalar extensión de MongoDB [cite: 10]
#RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN pecl uninstall mongodb && pecl install mongodb && docker-php-ext-enable mongodb

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario del sistema para ejecutar comandos
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Directorio de trabajo
WORKDIR /var/www/html

USER $user