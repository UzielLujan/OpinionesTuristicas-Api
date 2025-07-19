# Etapa 1: Builder - Compilar todo
FROM php:8.2-cli-alpine AS builder

# Instalar dependencias del sistema para compilar
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    build-base \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev

# Instalar la extensión de MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Configurar y compilar la extensión GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Compilar extensiones comunes de Laravel
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar TODO el código de la aplicación primero
COPY . .

# AHORA ejecutar composer install, con el archivo 'artisan' ya presente
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Generar caché de configuración y rutas para producción
RUN php artisan config:cache
RUN php artisan route:cache

# Etapa 2: Final - Imagen de producción limpia
FROM php:8.2-fpm-alpine

# Instalar solo las librerías de sistema necesarias en tiempo de ejecución
RUN apk add --no-cache nginx shadow libzip libpng jpeg freetype

# Crear usuario y grupo no-root
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/sh -g www www

# Copiar archivos de configuración de Nginx y PHP
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copiar las extensiones de PHP ya compiladas y sus archivos de configuración desde la etapa builder
COPY --from=builder /usr/local/lib/php/extensions/no-debug-non-zts-20220829/ /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

WORKDIR /var/www

# Copiar el código de la aplicación y las dependencias desde la etapa builder
COPY --from=builder /app .

# Asignar propiedad de los archivos al usuario www
RUN chown -R www:www /var/www

# Establecer permisos de escritura para Laravel
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

CMD ["/bin/sh", "-c", "php-fpm & nginx -g 'daemon off;'"]