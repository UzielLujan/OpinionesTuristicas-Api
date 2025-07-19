# Etapa 1: Builder - Instalar dependencias de PHP y Composer
FROM php:8.2-cli-alpine AS builder

# Instalar dependencias del sistema necesarias para compilar extensiones y para Composer
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

# Instalar extensiones de PHP comunes para Laravel
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Obtener la última versión de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /app

# Copiar solo los archivos de dependencias primero para aprovechar el cache de Docker
COPY composer.json composer.lock ./

# Instalar dependencias de Composer
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Copiar el resto del código de la aplicación
COPY . .

# Etapa 2: Final - Imagen de producción limpia
FROM php:8.2-fpm-alpine

# Instalar dependencias de sistema mínimas (Nginx y shadow para gestión de usuarios)
RUN apk add --no-cache nginx shadow

# Instalar solo las extensiones de PHP necesarias en tiempo de ejecución
RUN docker-php-ext-install pdo pdo_mysql zip gd
# Copiamos la extensión de mongodb ya compilada desde la etapa de builder
COPY --from=builder /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/lib/php/extensions/no-debug-non-zts-20220829/mongodb.so /usr/local/lib/php/extensions/no-debug-non-zts-20220829/

# Crear usuario y grupo no-root para la aplicación
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/sh -g www www

# Copiar archivos de configuración de Nginx y PHP
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini

# Establecer el directorio de trabajo
WORKDIR /var/www

# Copiar el código de la aplicación y las dependencias desde la etapa de builder
COPY --from=builder /app .

# Asignar propiedad de los archivos al usuario www para evitar problemas de permisos
RUN chown -R www:www /var/www

# Exponer el puerto 80 para Nginx
EXPOSE 80

# Iniciar PHP-FPM y Nginx
CMD ["/bin/sh", "-c", "php-fpm & nginx -g 'daemon off;'"]