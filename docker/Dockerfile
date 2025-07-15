FROM php:8.2

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Copia Composer desde imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia solo los archivos de Composer para aprovechar la cache
COPY composer.json composer.lock ./

# Instala dependencias sin que falle por falta de artisan
RUN composer install --no-interaction --prefer-dist --optimize-autoloader || true

# Copia el resto del código fuente (incluye artisan, rutas, etc.)
COPY . .

# Reinstala composer (ahora con artisan presente)
RUN composer dump-autoload --optimize && composer install --no-interaction --prefer-dist --optimize-autoloader

# Ajusta permisos para Laravel
RUN chmod -R 775 storage bootstrap/cache

# Expone el puerto del servidor embebido
EXPOSE 8000

# Comando para iniciar Laravel en modo dev
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]









