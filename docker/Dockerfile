# Etapa 1: Builder - Instalar dependencias
FROM composer:2.5 as builder
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Etapa 2: Final - Configurar el entorno de producción
FROM php:8.2-fpm-alpine

# Instalar extensiones de PHP necesarias
RUN apk add --no-cache nginx shadow && \
    docker-php-ext-install pdo pdo_mysql && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Crear usuario y grupo no-root para la aplicación
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/sh -g www www

# Copiar archivos de configuración de Nginx y PHP
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini

# Copiar el código de la aplicación desde la etapa de builder
COPY --from=builder /app /var/www
WORKDIR /var/www

# Asignar propiedad de los archivos al usuario www
RUN chown -R www:www /var/www

# Exponer el puerto 80 para Nginx
EXPOSE 80

# Iniciar PHP-FPM y Nginx
CMD ["/bin/sh", "-c", "php-fpm & nginx -g 'daemon off;'"]