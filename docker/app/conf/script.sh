#!/bin/sh

mkdir -p /var/www/html/public/uploads
chown -R www-data:www-data /var/www/html/public/uploads
chmod 755 /var/www/html/public/uploads

exec "$@"