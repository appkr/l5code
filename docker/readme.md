# Docker 개발 환경 구성

로컬 개발 환경을 구성하는데 어려움이 있는 분들을 위해 Docker를 활용한 개발 환경을 구성하는 방법을 설명합니다. 결론부터 말하면 로컬 개발 환경을 구성하는 것 보다 쉽습니다.

## 1. Docker 설치

아래 링크를 방문해서 각자의 운영체제에 맞는 Docker 패키지를 설치합니다.

> https://www.docker.com/products/docker

우리가 사용할 명령은 `docker` 및 `docker-compose`인데, 잘 설치되었나 확인합니다.

```bash
$ docker --version
# Docker version 1.13.0, build 49bf474

$ docker-compose --version
# docker-compose version 1.10.0, build 4bd6f1a
```

위 링크를 방문하지 않고도 더 편하게 설치하는 방법은 다음과 같습니다.

### 1.1. OS X

```bash
$ brew cask install docker --appdir=/Applications
```

### 1.2. Ubuntu

```bash
$ sudo apt update && apt install docker 
```

## 2. Docker 환경 실행

`docker` 브랜치를 로컬 컴퓨터로 가져오고 체크아웃합니다.

```bash
~/myapp(master) $ git pull --all && git checkout docker
```

`docker-compose` 명령을 이용해서 Docker 컨테이너를 실행합니다.

```bash
~/myapp(docker) $ cd docker

~/myapp/docker(docker) $ docker-compose up -d lemp
# Creating network "docker_default" with the default driver
# Building lemp
# Step 1/7 : FROM appkr/lemp-base:latest
#  ---> b00af5dd2802
# Step 2/7 : ENV DEBIAN_FRONTEND noninteractive
#  ---> Running in 2d5cd9c0bef5
#  ---> 799be18850be
# Removing intermediate container 2d5cd9c0bef5
# Step 3/7 : COPY files /
#  ---> a5b9ac5c4a61
# Removing intermediate container 0e1053f6d18a
# Step 4/7 : RUN rm /etc/nginx/sites-available/default     && rm /etc/nginx/sites-enabled/default     && ln -nfs /etc/nginx/sites-available/myapp.conf /etc/nginx/sites-enabled/
#  ---> Running in 1691ca0261d1
#  ---> d2e29c61372c
# Removing intermediate container 1691ca0261d1
# Step 5/7 : WORKDIR /var/www/myapp
#  ---> d2e847c6d899
# Removing intermediate container ea7f9abd3f69
# Step 6/7 : VOLUME /var/www/myapp /var/lib/mysql
#  ---> Running in 0f1ae22f096d
#  ---> cfae62b5f09f
# Removing intermediate container 0f1ae22f096d
# Step 7/7 : EXPOSE 80 9991 3306
#  ---> Running in 6cd251b95fdc
#  ---> 3c78709fe2e8
# Removing intermediate container 6cd251b95fdc
# Successfully built 3c78709fe2e8
# WARNING: Image for service lemp was built because it did not already exist. To rebuild this image you must use `docker-compose build` or `docker-compose up --build`.
# Creating docker_lemp_1

~/myapp/docker(docker) $ docker-compose ps
    Name           Command       State                                        Ports
------------------------------------------------------------------------------------------------------------------------
docker_lemp_1   /entrypoint.sh   Up      0.0.0.0:33060->3306/tcp, 0.0.0.0:8000->80/tcp, 0.0.0.0:9001->9001/tcp, 9991/tcp
```

> 각자 사용하는 셸 프로파일에 alias를 추가하면, `docker-compose` 대신 `dc`와 같이 짧은 명령을 이용할 수 있습니다. 아래 명령에서는 `~/.zshrc`가 셸 프로파일이라고 가정합니다.
>
> ```bash
> $ echo "" >> ~/.zshrc && echo "alias dc=docker-compose" >> ~/.zshrc && source ~/.zshrc 
> ```

최초 한번은 필요한 데이터베이스와 사용자를 만들고, 마이그레이션과 시딩을 수행해줘야 합니다. **아래 명령은 Docker 환경을 삭제하지 않으면 다시 수행할 필요가 없습니다.**

```bash
~/myapp/docker(docker) $ docker exec -it docker_lemp_1 bash /myinit.sh
# UPDATE mysql.user SET authentication_string = PASSWORD('secret') WHERE User = 'root' AND Host = '%'
# CREATE USER 'homestead'@'%' IDENTIFIED BY 'secret'
# GRANT ALL PRIVILEGES ON myapp.* TO 'homestead'@'%'
# CREATE DATABASE myapp
# FLUSH PRIVILEGES
# --------------
# Migration table created successfully.
# Migrated: 2014_10_12_000000_create_users_table
# Migrated: 2014_10_12_100000_create_password_resets_table
# Migrated: 2016_09_04_093824_create_articles_table
# Migrated: 2016_09_10_011647_create_tags_table
# Migrated: 2016_09_10_011654_create_article_tag_table
# Migrated: 2016_09_10_040857_add_last_login_column_on_users_table
# Migrated: 2016_09_11_224837_add_confirm_code_column_on_users_table
# Migrated: 2016_09_14_210419_add_nullable_to_password_column_on_users_table
# Migrated: 2016_09_15_043449_create_attachments_table
# Migrated: 2016_09_15_095657_create_comments_table
# Migrated: 2016_09_15_235854_create_votes_table
# Migrated: 2016_09_16_033759_add_columns_on_articles_table
# Migrated: 2016_09_16_050319_add_softdelete_on_comments_table
# Migrated: 2016_09_16_134105_add_softdelete_on_articles_table
# Migrated: 2016_09_16_231034_add_multilingual_columns_on_tags_table
# --------------
# Seeded: tags table
# Seeded: UsersTableSeeder
# Seeded: ArticlesTableSeeder
# Seeded: article_tag table
# --------------
# Downloading 10 images from lorempixel. It takes time...
# File saved: 696bf159c88708dbac0914608547edcd.jpg
# File saved: f56cb88eae9e2fdad8ac0c7f46ebb40f.jpg
# File saved: 05e8a754f37f370321105f25f309bfc8.jpg
# File saved: 4573d26943a4df95ca3f71fd8672c708.jpg
# File saved: e27a5dffddfec621d8579a5387e4aa14.jpg
# File saved: 06a4606db40f18135cad3a8dbe7a9198.jpg
# File saved: 7ee14d22b3dc4d95ef95d53e1700535d.jpg
# File saved: b4ae8d3e30f91c085f010b9ab55c0052.jpg
# File saved: 913902ac17fdd1ccfb8d8d22aa0f8d08.jpg
# File saved: 8e82a04accfb9bb1b7dcd6d897828356.jpg
# File saved: e2daf174d2970048c9a910d88ae4f2fa.jpg
# File saved: 8d5bc6e0e197934cf486afac2f5e1162.jpg
# File saved: 5524e880949f71e5cd3d4967fcbab50b.jpg
# File saved: 6507c9a6df89f1350cfcb52f39d07cbf.jpg
# File saved: 4007e851832ec8b98db7795792ed71c9.jpg
# File saved: 377c0cc10f7e80219555218cad3f8afb.jpg
# File saved: 7b5ae1eee5be12e6de0ecb06dad4f8df.jpg
# File saved: 71010cd6680bcc712acc8f7b02136da3.jpg
# File saved: b1f2ddfe84546cba3b9ed2259c18b22f.jpg
# File saved: 440eb97c83353be5ccaea60ed80621fa.jpg
# --------------
# Seeded: attachments table and files
# Seeded: comments table
# Seeded: votes table

~/myapp/docker(docker) $ cd .. && vendor/bin/phpunit
# PHPUnit 5.5.4 by Sebastian Bergmann and contributors.
# .................                      17 / 17 (100%)
# Time: 2 seconds, Memory: 18.00MB
# OK (17 tests, 90 assertions)
```

이제 브라우저를 열어 `http://localhost:8000`으로 접속해 봅니다.

> 다운로드 받은 Docker 환경을 여러 분의 프로젝트에 적용하려면 `~/myapp/docker` 폴더를 여러 분의 프로젝트 폴더로 복사하면 됩니다.
>
> ```bash
> ~/your-project-dir $ cp -r ~/myapp/docker ./
> ```

## 3. Docker 환경 중지 및 삭제

다음 명령으로 실행 중인 Docker 환경을 중지할 수 있습니다.

```bash
~/myapp/docker(docker) $ docker-compose stop
```

> `docker-compose` 명령은 항상 `docker-compose.yml` 파일이 있는 위치에서 수행해야 합니다. 반면에 `docker` 명령은 아무 곳에서나 수행할 수 있습니다. 위 명령을 `docker` 명령으로 바꾸면 다음과 같습니다.
>
> ```bash
> ~ $ docker ps
> # CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS              PORTS                                                                             NAMES
> # 83ee809dcc66        docker_lemp         "/entrypoint.sh"    22 minutes ago      Up 3 minutes        0.0.0.0:9001->9001/tcp, 9991/tcp, 0.0.0.0:8000->80/tcp, 0.0.0.0:33060->3306/tcp   docker_lemp_1
> 
> ~ $ docker stop docker_lemp_1 # 또는 docker stop 83ee809dcc66
> # 83ee809dcc66
> ```

다시 시작하고 싶을 때는 다음 명령을 이용합니다. 이미지를 만드는 과정이 빠졌으므로, 2절과 달리 수 초 만에 Docker 환경 전체가 시작됩니다. 

```bash
~/myapp/docker(docker) $ docker-compose up -d lemp
# Starting docker_lemp_1
```

Docker 환경을 삭제하고 싶을 때는 다음 명령을 이용합니다. (컨테이너가 삭제된 것일뿐 이미지가 완전히 삭제된 것을 아닙니다.)

```bash
~/myapp/docker(docker) $ docker-compose down
```

## 4. Docker 환경 접속 정보

서비스|접속 정보
---|---
Web|`http://localhost:8000`
Supervisor|`http://localhost:9001` (HTTP Basic Auth => `homestead`/`secret`)
MySQL|`$ mysql -h127.0.0.1 -P33060 -uhomestead -p` (`root`/`homestead` Password => `secret`)

## 5. 트러블슈팅

문제가 있다면 모든 작업을 되돌리고, 처음부터 다시 시작해 보세요.

```bash
~/myapp/docker(docker) $ rm -rf mysql_datadir

~/myapp/docker(docker) $ docker ps
# CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS              PORTS                                                                             NAMES
# 83ee809dcc66        docker_lemp         "/entrypoint.sh"    22 minutes ago      Up 3 minutes        0.0.0.0:9001->9001/tcp, 9991/tcp, 0.0.0.0:8000->80/tcp, 0.0.0.0:33060->3306/tcp   docker_lemp_1

~/myapp/docker(docker) $ docker stop docker_lemp_1 && docker rm docker_lemp_1
# 83ee809dcc66

~/myapp/docker(docker) $ docker images
# REPOSITORY              TAG                 IMAGE ID            CREATED             SIZE
# docker_lemp             latest              3c78709fe2e8        43 minutes ago      610 MB

~/myapp/docker(docker) $ docker rmi --force 3c78709fe2e8
# Untagged: docker_lemp:latest
# Deleted: sha256:3c78709fe2e888935c0e2f995a38077369128399fa9c14251275a93fe94c01bd
# Deleted: sha256:cfae62b5f09f2946e24a7eb1211e114d56a5824091e9db6c132fe554d69d4c4b
# Deleted: sha256:d2e847c6d8990093ec28fd17237a14e23f6a81b1eac0f55dbbfca944d451dd29
# Deleted: sha256:420f9fc9af0fb001267963f031228f1436080c704be639f008f8f57b7da72f8e
# Deleted: sha256:d2e29c61372cb5136969b207769ca54e61da285045e5092cc115aae47a31c538
# Deleted: sha256:f4c95dcaa589de08b406e12f362a453525541a8bb39d8c56c9c72c71dec67efa
# Deleted: sha256:a5b9ac5c4a61a0ecfbec9e8c5d57d5a6ad4867a0690dd8335a9819cdbd972c01
# Deleted: sha256:7965aa66aef6695a17099d9984582e96f6afbb675fdc73b6c903320845d56f49
# Deleted: sha256:799be18850be161e6e49e55968b7b6e6ae2bb7e04c58705ae36eb9298479dbde
```

## 6. 리소스

Docker 기초를 배우려면 [오픈컨테이너 코리아 포럼](http://forum.opencontainer.co.kr/) 운영자이신 김충섭님의 [초보를 위한 도커 안내서 - 도커란 무엇인가?](https://subicura.com/2017/01/19/docker-guide-for-beginners-1.html) 포스트를 참고해주세요.
