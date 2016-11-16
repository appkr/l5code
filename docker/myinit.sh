#!/bin/bash

if [ ! -d $MYSQL_DATA_DIR/myapp ]; then
    mysql -v -e "USE mysql; UPDATE mysql.user SET authentication_string = PASSWORD('${MYSQL_ROOT_PASSWORD}') WHERE User = 'root' AND Host = '%';"
    mysql -v -e "CREATE USER 'homestead'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"
    mysql -v -e "GRANT ALL PRIVILEGES ON myapp.* TO 'homestead'@'%';"
    mysql -v -e "CREATE DATABASE myapp; FLUSH PRIVILEGES;"
    chmod -R 775 storage /var/www/myapp/bootstrap/cache /var/www/myapp/public/files
    php /var/www/myapp/artisan migrate --seed --force
fi
