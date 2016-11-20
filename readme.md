
# 라라벨로 배우는 실전 PHP 웹 프로그래밍 (출판용 소스코드)

이 소스코드는 다음 버전을 기준으로 한다.

컴포넌트|버전
---|---
프레임워크(`laravel/laravel`)|5.3.0
핵심 컴포넌트(`laravel/framework`)|5.3.9

## 1. 다운로드

`myapp`은 소스코드를 복제할 디렉터리 이름이다.

```sh
~ $ git clone git@github.com:appkr/l5code.git myapp
```

\- OR - 

```sh
~ $ git clone https://github.com/appkr/l5code.git myapp
```

\- OR - 

GUI 환경을 선호하는 독자는 [깃허브 데스크톱](https://desktop.github.com) 프로그램을 이용할 수 있다.

## 2. 태그 이동

각 챕터(chapter, 章)마다 깃 버전 관리 시스템으로 커밋(commit) 메시지와 태그(tag)를 부여해두었다. 다음 콘솔 명령으로 원하는 챕터의 코드로 이동할 수 있다.

```sh
~/myapp(master) $ git tag                # 전체 태그 목록을 확인한다.
~/myapp(master) $ git checkout 1001      # 까지 입력한 후 'Tab' 키를 누르고 'Enter'를 친다.
~/myapp(4443..) $ composer dump-autoload # 오토로드 레지스트리 업데이트
```

## 3. 소스코드 구동 준비 하기

복제한 소스코드를 작동을 확인하려면 다음 설명을 따른다. 이 절의 내용은 소스코드를 복제 받고 처음 한번만 하면 된다.

### 3.1. 프로젝트의 의존성 설치

내려 받은 소스코드는 이 프로젝트가 의존하는 컴포넌트들을 포함하지 않고 있다. [컴포저(composer)](https://getcomposer.org/)로 이 프로젝트가 의존하는 컴포넌트를 설치한다.

소스코드를 복제한 `myapp` 디렉터리로 이동한다.

```sh
~ $ cd myapp
```

(선택 사항) 태그로 이동했을 때를 대비해서, 마스터 브랜치로 이동한다.

```sh
~/myapp $ git checkout master
```

이 프로젝트가 의존하는 컴포넌트를 설치한다.

```sh
~/myapp $ composer install
```

### 3.2. 환경 설정

`.env.example` 파일을 복사하여 `.env` 파일을 만든다. 파일을 열어 자신의 환경에 맞게 적절히 수정하고 저장한다. 예를 들어 사용할 데이터베이스가 `myapp`라면 `DB_DATABASE=myapp`으로 수정한다.

```sh
~ $ cd myapp
~/myapp $ cp .env.example .env
```

암호화 키를 만든다. 아래 명령을 수행함과 동시에 `.env` 파일에 방금 만든 키가 자동으로 등록된다. 

```sh
~/myapp $ php artisan key:generate
```

Mac 또는 Linux를 사용한다면, `storage`, `bootstrap/cache`, `public\files` 디렉터리의 권한을 변경한다.

```sh
~/myapp $ chmod -R 777 storage bootstrap/cache public/files
```

### 3.3. 데이터베이스 마이그레이션 및 시딩

아래 명령을 실행하기 전에 `.env` 파일에서 선언한 데이터베이스가 있고, 데이터베이스에 로그인할 사용자가 권한이 있는 지 확인한다. 잘 모르겠다면 7장까지 읽고 다시 이 문서로 돌아오기 바란다.

이 명령은 테이블을 만들고 더미 데이터를 심는 과정이다.

```sh
~/myapp $ php artisan migrate --seed --force
```

### 3.4. (선택 사항) 마크다운 뷰어용 데이터 파일 설치

마크다운 뷰어를 만드는 실전 프로젝트에서 라라벨 공식 문서를 데이터로 이용했다.

```sh
~/myapp $ git clone git@github.com:laravel/docs.git
# - OR -
~/myapp $ git clone https://github.com/laravel/docs.git
```

## 4. 소스코드 구동 확인

PHP 내장 웹 서버와 로컬 데이터베이스를 사용한다고 가정한다.

### 4.1. 브라우저

PHP 내장 웹 서버를 구동한다.

```sh
~/myapp $ php artisan serve
# Laravel development server started on http://localhost:8000/
```

이제 웹 브라우저에서 `http://localhost:8000`으로 접속해서 실습 예제의 동작을 확인한다.
 
### 4.2. 통합 테스트

테스트에 사용할 SQLite 데이터베이스를 만든다.

```sh
~/myapp $ touch tests/database.sqlite
```

테이블을 만들고 시딩한다.

```sh
~/myapp $ php artisan migrate --seed --database=testing
```

PHPUnit을 실행한다.

```sh
~/myapp $ vendor/bin/phpunit
```

## 5. 라이선스

이 소스코드는 [MIT](https://github.com/appkr/l5code/blob/master/LICENSE) 라이선스를 따른다.
