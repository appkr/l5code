# Docker 개발 환경 구성

로컬 개발 환경을 구성하는데 어려움이 있는 분들을 위해 Docker를 활용한 개발 환경을 구성하는 방법을 설명합니다. 결론부터 말하면 로컬 개발 환경을 구성하는 것 보다 쉽습니다.

## 1. Docker 설치

아래 링크를 방문해서 각자의 운영체제에 맞는 Docker 패키지를 설치합니다.

> https://docs.docker.com/install/

우리가 사용할 명령은 `docker` 및 `docker-compose`인데, 잘 설치되었나 확인합니다.

```bash
$ docker --version
# Docker version 1.13.0, build 49bf474

$ docker-compose --version
# docker-compose version 1.10.0, build 4bd6f1a
```

## 2. Docker 환경 실행

`docker` 브랜치를 로컬 컴퓨터로 가져오고 체크아웃합니다.

```bash
~ $ git clone git@github.com:appkr/l5code.git myapp
~ $ cd myapp  
~/myapp(master) $ git checkout docker
~/myapp(docker) $ cp .env.example .env
~/myapp(docker) $ composer install
~/myapp(docker) $ php artisan key:generate
```

`docker-compose` 명령을 이용해서 Docker 컨테이너를 실행합니다.

```bash
~/myapp(docker) $ docker-compose -f docker/docker-compose.yml up -d lemp
# Creating network "docker_default" with the default driver
# Building lemp
# Step 1/7 : FROM appkr/lemp-base:latest
# ...
# Removing intermediate container 6cd251b95fdc
# Successfully built 3c78709fe2e8
# WARNING: Image for service lemp was built because it did not already exist. To rebuild this image you must use `docker-compose build` or `docker-compose up --build`.
# Creating docker_lemp_1

~/myapp(docker) $ docker-compose -f docker/docker-compose.yml ps
    Name           Command       State                                        Ports
------------------------------------------------------------------------------------------------------------------------
docker_lemp_1   /entrypoint.sh   Up      0.0.0.0:3306->3306/tcp, 0.0.0.0:80->80/tcp, 0.0.0.0:9001->9001/tcp, 9991/tcp
```

> 80 또는 3306 포트 충돌로 인해 Docker 컨테이너가 실행되지 않는다면, 해당 포트를 사용하는 서비스를 중단하고 5 트러블슈팅 부분을 참고해서 재시도합니다.

최초 한번은 필요한 데이터베이스와 사용자를 만들고, 마이그레이션과 시딩을 수행해줘야 합니다. **아래 명령은 mysql_datadir를 완전히 삭제한 경우가 아니라면 다시 수행할 필요가 없습니다.**

```bash
~/myapp(docker) $ docker exec -it docker_lemp_1 bash /myinit.sh
# CREATE USER 'homestead'@'%' IDENTIFIED BY 'secret'
# ...
# --------------
# Migration table created successfully.
# Migrated: 2014_10_12_000000_create_users_table
# ...
# --------------
# Seeded: tags table
# ...
# --------------
# Downloading 10 images from lorempixel. It takes time...
# File saved: 696bf159c88708dbac0914608547edcd.jpg
# ...
# --------------
# Seeded: attachments table and files
# ...
```

이제 브라우저를 열어 `http://localhost`으로 접속해 봅니다.

## 3. Docker 환경 중지 및 삭제

다음 명령으로 실행 중인 Docker 환경을 중지할 수 있습니다.

```bash
~/myapp(docker) $ docker-compose -f docker/docker-compose.yml stop
```

다시 시작하고 싶을 때는 다음 명령을 이용합니다. 이미지를 만드는 과정이 빠졌으므로, 2절과 달리 수 초 만에 Docker 환경 전체가 시작됩니다. 

```bash
~/myapp(docker) $ docker-compose -f docker/docker-compose.yml start -d
# Starting docker_lemp_1
```

Docker 환경을 삭제하고 싶을 때는 다음 명령을 이용합니다. (컨테이너가 삭제된 것일뿐 이미지가 완전히 삭제된 것을 아닙니다.)

```bash
~/myapp(docker) $ docker-compose -f docker/docker-compose.yml down
```

## 4. Docker 환경 접속 정보

서비스|접속 정보
---|---
Web|`http://localhost`
MySQL|`$ docker exec -it mysql`
Supervisor|`http://localhost:9001` (HTTP Basic Auth => `homestead`/`secret`)
Xdebug|10001 (IDEKEY=IDEA)

## 5. 트러블슈팅

문제가 있다면 아래 명령을 참고해서 모든 작업을 되돌리고, 처음부터 다시 시작해 보세요.

```bash
~/myapp(docker) $ docker-compose -f docker/docker-compose.yml down
~/myapp(docker) $ ls -d docker/mysql_datadir/* | grep -v .gitignore | xargs rm -rf
```

## 6. 리소스

Docker 기초를 배우려면 김충섭님의 [초보를 위한 도커 안내서 - 도커란 무엇인가?](https://subicura.com/2017/01/19/docker-guide-for-beginners-1.html) 포스트를 참고해주세요.

---

## 참고. Docker를 설치하는 다른 방법

#### OS X

```bash
$ brew cask install docker --appdir=/Applications
```

#### Ubuntu

```bash
$ sudo apt update && apt install docker 
```

## 참고. `docker-compose` 대신 `docker` 명령어를 이용하는 방법

```bash
# 프로젝트 폴더를 Docker에 연결해야하므로 이 명령은 프로젝트 폴더에서 수행해야 합니다.
~/myapp(docker) $ docker build \
    -f ./docker/lemp-l5code/Dockerfile \
    --tag lemp:latest \
    ./docker/lemp-l5code

~/myapp(docker) $ docker run -d \
    --name myapp
    -v `pwd`:/var/www/myapp \
    -v `pwd`/docker/mysql_datadir:/var/lib/mysql \
    -p 80:80 \
    -p 3306:3306 \
    -p 9001:9001 \
    -p 10001:10001 \
    lemp:latest
```

```bash
# 한번 run했던 컨테이너를 삭제하지 않았다면, 이후 stop, start는 아무 폴더에서나 할 수 있습니다.
~ $ docker stop myapp
~ $ docker start myapp
```

## 참고. 다운로드 받은 Docker 환경을 여러분의 프로젝트에 적용하려면 

`~/myapp/docker` 폴더를 여러 분의 프로젝트 폴더로 복사하면 됩니다.

```bash
~/your-project-dir $ cp -r ~/myapp/docker ./
```

여기서 설명하는 Docker 이미지에는 PHP7.0이 담겨있습니다. 여러분의 프로젝트가 PHP 7.2를 필요로 한다면 `docker/lemp-l5code/Dockerfile`파일을 아래와 같이 고쳐서 사용해주세요.

```diff
-FROM appkr/lemp-base:16.04
+FROM appkr/lemp-base:18.04
```

image:tag|Ubuntu|PHP|MySQL
---|---|---|---
[`appkr/lemp-base/16.04`](https://hub.docker.com/r/appkr/lemp-base/tags)|16.04|7.0|5.7
[`appkr/lemp-base/18.04`](https://hub.docker.com/r/appkr/lemp-base/tags)|18.04|7.2|5.7
