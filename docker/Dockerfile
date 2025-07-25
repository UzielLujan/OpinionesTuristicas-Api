# Etapa 1: Builder - Compilar todo
FROM php:8.2-cli-bullseye AS builder

# Instalar dependencias del sistema para compilar
RUN apt-get update && apt-get install -y \
    $PHPIZE_DEPS     build-essential     git     unzip     zip     libzip-dev     libpng-dev     libjpeg-dev     libfreetype6-dev     && rm -rf /var/lib/apt/lists/*

# Instalar la extensión de MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Configurar y compilar la extensión GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Compilar extensiones comunes de Laravel
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar TODO el código de la aplicación primero
COPY . .

# Actualizar composer.lock y luego instalar dependencias
RUN composer update --no-dev --no-interaction && \
    composer install --no-dev --no-interaction --optimize-autoloader

# Etapa 2: Final - Imagen de producción limpia
FROM php:8.2-fpm-bullseye

# Instalar solo las librerías de sistema necesarias en tiempo de ejecución
RUN apt-get update && apt-get install -y nginx libzip-dev libpng-dev libjpeg-dev libfreetype6-dev     && rm -rf /var/lib/apt/lists/*

# Crear usuario y grupo no-root
# Crear usuario y grupo no-root

# Copiar archivos de configuración de Nginx y PHP
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copiar las extensiones de PHP ya compiladas y sus archivos de configuración desde la etapa builder
COPY --from=builder /usr/local/lib/php/extensions/no-debug-non-zts-20220829/ /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

WORKDIR /var/www

# Copiar el código de la aplicación y las dependencias desde la etapa builder
COPY --from=builder /var/www .

# Asignar propiedad de los archivos al usuario www
RUN chown -R www-data:www-data /var/www

# Establecer permisos de escritura para Laravel
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Crear directorio para el socket FPM y establecer permisos
RUN mkdir -p /var/www/run && chown www-data:www-data /var/www/run && chmod 777 /var/www/run

EXPOSE 80

CMD ["/bin/sh", "-c", "php-fpm & nginx -g 'daemon off;'"]