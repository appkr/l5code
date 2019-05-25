#!/bin/bash

if [ ! -d $MYSQL_DATA_DIR/myapp ]; then
    # homestead 사용자 생성
    mysql -v -e "CREATE USER 'homestead'@'localhost' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"
    mysql -v -e "CREATE USER 'homestead'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"

    # homestead 사용자 권한 부여
    mysql -v -e "GRANT ALL PRIVILEGES ON myapp.* TO 'homestead'@'localhost';"
    mysql -v -e "GRANT ALL PRIVILEGES ON myapp.* TO 'homestead'@'%';"
    mysql -v -e "FLUSH PRIVILEGES;"

    # 데이터베이스 생성
    mysql -v -e "CREATE DATABASE myapp;"

    # 폴더에 쓰기 권한 부여
    chmod -R 775 storage /var/www/myapp/bootstrap/cache /var/www/myapp/public/files

    # 테이블 마이그레이션 및 시딩
    php /var/www/myapp/artisan migrate --seed --force
fi
