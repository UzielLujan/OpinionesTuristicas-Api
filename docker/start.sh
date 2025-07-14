#!/bin/sh
# Asigna permisos correctos al storage
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Inicia Supervisor para correr Nginx y PHP-FPM
exec /usr/bin/supervisord -c /etc/supervisor/supervisor.conf