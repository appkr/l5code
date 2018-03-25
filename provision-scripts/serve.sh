#!/usr/bin/env bash

# This script references
# https://github.com/laravel/homestead/blob/master/scripts/serve-laravel.sh
#
#--------------------------------------------------------------------------
# Before run this script...
#--------------------------------------------------------------------------
#
# Get sudo permission
#   user@server:~$ sudo -s
#
# TROUBLESHOOTING.
#
#   If you encounter error message like "sudo: no tty present
#   and no askpass program specified ...", you can work around this error
#   by adding the following line on your production server's /etc/sudoers.
#
#   user@server:~# visudo
#
#   deployer ALL=(ALL:ALL) NOPASSWD: ALL
#   %www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/service php7.0-fpm restart,/usr/sbin/service nginx restart
#
#--------------------------------------------------------------------------
# How to run
#--------------------------------------------------------------------------
#
#   user@server:~# bash serve.sh example.com /path/to/document-root
#

block="server {
    listen 80;
    server_name $1;
    root \"$2\";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log /var/log/nginx/$1-access.log;
    error_log  /var/log/nginx/$1-error.log error;

    sendfile off;

    client_max_body_size 100m;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_intercept_errors off;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
    }

    location ~ /\.ht {
        deny all;
    }
}
"

echo "$block" > "/etc/nginx/sites-available/$1"
ln -fs "/etc/nginx/sites-available/$1" "/etc/nginx/sites-enabled/$1"
service nginx restart
service php7.0-fpm restart