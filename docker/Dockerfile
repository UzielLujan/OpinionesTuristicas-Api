FROM php:8.2

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Copia Composer desde imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia solo los archivos de Composer para aprovechar la cache
COPY composer.json composer.lock ./

# Instala dependencias PHP con Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Ahora sí, copia el resto del código fuente
COPY . .

# Asegura permisos necesarios para Laravel
RUN chmod -R 775 storage bootstrap/cache

# Expone el puerto que usará el servidor interno de Laravel
EXPOSE 8000

# Comando para servir la app
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]








