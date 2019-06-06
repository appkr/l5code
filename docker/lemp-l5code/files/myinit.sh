#!/bin/bash

if [ ! -d $MYSQL_DATA_DIR/myapp ]; then
    echo -e "리모트 접속이 가능한 root 사용자를 만듭니다"
    mysql -v -e "CREATE USER 'root'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"
    mysql -v -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;"

    echo -e "homestead 사용자를 만듭니다"
    mysql -v -e "CREATE USER 'homestead'@'localhost' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"
    mysql -v -e "CREATE USER 'homestead'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"
    mysql -v -e "GRANT ALL PRIVILEGES ON myapp.* TO 'homestead'@'localhost';"
    mysql -v -e "GRANT ALL PRIVILEGES ON myapp.* TO 'homestead'@'%';"
    mysql -v -e "FLUSH PRIVILEGES;"

    echo -e "myapp 이름을 가진 데이터베이스를 만듭니다"
    mysql -v -e "CREATE DATABASE myapp;"

    echo -e "PHP 스크립트가 폴더에 쓸 수 있도록 폴더 권한을 변경합니다"
    chmod -R 775 storage /var/www/myapp/bootstrap/cache

    if [[ -f /var/www/myapp/public/files ]]; then
      echo -e "예제 프로젝트이면, 테이블 마이그레이션 및 시딩을 수행합니다"
      chmod -R 775 /var/www/myapp/public/files
      php /var/www/myapp/artisan migrate --seed --force
    fi
fi
