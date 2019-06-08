[![Build Status](https://travis-ci.org/appkr/l5code.svg?branch=laravel56)](https://travis-ci.org/appkr/l5code)

## 예제 프로젝트 5.6 업그레이드

책에 수록된 코드 예제는 라라벨 5.6 버전에서도 그대로 작동합니다. 아래 달라진 점 부분만 고쳐주시면 됩니다.

### 1. 달라진 점

라라벨 5.3에서 작성된 최종 예제 코드 대비, 라라벨 5.6로 업그레이드하면서 달라져야 하는 점은 여섯 가지 입니다.

1. 라라벨 믹스 적용
2. `maknz/slack` 컴포넌트 제거
3. `barryvdh\laravel-cors` 사용법 변경 내용분 적용
4. 모델 팩토리 비활성화
5. 구글이 `.dev` 도메인을 구입하고 TLD(Top Level Domain)으로 등록함에 따라 예제에서 사용한 `myapp.dev`, `api.myapp.dev`는 사용할 수 없습니다. `myapp.local`, `api.myapp.local`로 변경하여 사용해야 합니다.
6. 트랜스포머 라이브러리에서 삭제된 API 사용 부분 제거 

#### 1.1. 라라벨 믹스 적용 ~ 1.4. 모델 팩토리 비활성화

5.4 및 5.5 브랜치의 README를 참고합니다. 
- https://github.com/appkr/l5code/blob/laravel54/readme.md
- https://github.com/appkr/l5code/blob/laravel55/readme.md

#### 1.5. .dev TLD ~ 1.6. 트랜스포머

### 2. 설치 및 실행법

이 브랜치를 로컬 컴퓨터에서 실행하려면 다음 안내를 따릅니다. 이 브랜치는 챕터별 커밋 이력이 없는 라라벨 5.5에서 작동하는 최종 버전의 예제 코드 입니다.

```bash
# 기존에 호스트 파일에 myapp.dev 레코드를 추가하지 않았다면.
~ $ sudo echo "127.0.0.1 myapp.local" >> /etc/hosts
~ $ sudo echo "127.0.0.1 api.myapp.local" >> /etc/hosts

# 예제 코드 저장소를 복제하지 않았다면.
~ $ git clone git@github.com:appkr/l5code.git
~ $ cd l5code

# 예제 코드 프로젝트를 셋팅한 적이 없다면.
~/l5code(master) $ cp .env.example .env
~/l5code(master) $ chmod -R 775 storage bootstrap/cache public/files
~/l5code(master) $ php artisan key:generate

# 로컬 복제된 예제 코드를 이미 가지고 있다면.
~/l5code(master) $ git pull --all

~/l5code(master) $ git checkout laravel56
~/l5code(laravel56) $ composer install
~/l5code(laravel56) $ php artisan migrate:refresh --seed --force
~/l5code(laravel56) $ php artisan serve --host=myapp.dev
```

브라우저에서 http://myapp.local:8000 을 열어 확인합니다.

```bash
~/l5code(laravel56) $ vendor/bin/phpunit
```

API 작동 테스트를 위한 포스트맨 콜렉션은 여기서 받을 수 있습니다.

https://www.getpostman.com/collections/56ac056289864393eea5
