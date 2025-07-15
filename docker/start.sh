#!/bin/sh
# Inicia Supervisor para correr Nginx y PHP-FPM
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf