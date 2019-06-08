[![Build Status](https://travis-ci.org/appkr/l5code.svg?branch=laravel54)](https://travis-ci.org/appkr/l5code)

## 예제 프로젝트 5.4 업그레이드

책에 수록된 코드 예제는 라라벨 5.4 버전에서도 그대로 작동합니다. 아래 달라진 점 부분만 고쳐주시면 됩니다.

### 1. 달라진 점

라라벨 5.3에서 작성된 최종 예제 코드 대비, 라라벨 5.4로 업그레이드하면서 달라져야 하는 점은 세 가지 입니다.

1. 라라벨 믹스 적용
2. `maknz/slack` 컴포넌트 제거
3. `barryvdh\laravel-cors` 사용법 변경 내용분 적용 

#### 1.1. 라라벨 믹스 적용

**21장 "엘릭서와 프런트엔드"** 관련 내용입니다. 

https://github.com/appkr/l5code/issues/11 에 자세히 설명해 두었습니다.

#### 1.2. `maknz/slack` 컴포넌트 제거

**30장 2절의 "오류 알림"** 관련 내용입니다. 

`maknz/slack`가 제공하는 서비스프로바이더가 문제를 일으켜 걷어 내고, 라라벨 5.4부터 제공하는 Notification 기능으로 대체해서 슬랙 메시지를 보내는 것으로 변경했습니다.

```php
<?php // config/services.php

return [
    'slack' => [
        'endpoint' => env('SLACK_WEBHOOK', ''),
    ]
];
```

```php
<?php // app/Exceptions/Handler.php

class Handler extends ExceptionHandler
{
    use \Illuminate\Notifications\Notifiable;
    
    // ...
    
    public function report(Exception $exception)
    {
        if (app()->environment('production') && $this->shouldReport($exception)) {
            $this->notify(new ExceptionOccurred($exception));
        }

        parent::report($exception);
    }

    public function routeNotificationForSlack()
    {
        return config('services.slack.endpoint');
    }
    
    // ...
}
```

```php
<?php // app/Notifications/ExceptionOccurred.php

namespace App\Notifications;

use Exception;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ExceptionOccurred extends Notification
{
    private $e;

    public function __construct(Exception $e)
    {
        $this->e = $e;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->error()
            ->content(sprintf(
                "%s \n\n%s \n%s:%d \n\n%s",
                get_class($this->e),
                $this->e->getMessage(),
                $this->e->getFile(),
                $this->e->getLine(),
                $this->e->getTraceAsString()
            ));
    }
}
```

#### 1.3. `barryvdh\laravel-cors` 사용법 변경 내용분 적용 

**36장 1절의 "CORS"** 관련 내용입니다.

해당 컴포넌트에서 제공하는 미들웨어 사용법이 변경되었습니다. `'cors'`라는 상수 방식의 미들웨어 별칭을 더 이상 제공하지 않고, FQCN을 이용해야 합니다.

```diff
<?php // routes/api.php

Route::group([
    'domain' => config('project.api_domain'),
    'namespace' => 'Api',
    'as' => 'api.',
-    'middleware' => ['cors']
], function () {
    // ...
});
```

```diff
<?php // app/Http/Kernel.php

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'api' => [
            'throttle:60,1',
            'bindings',
+            \Barryvdh\Cors\HandleCors::class,
        ],
    ];
}
```

### 2. 설치 및 실행법

이 브랜치를 로컬 컴퓨터에서 실행하려면 다음 안내를 따릅니다. 이 브랜치는 챕터별 커밋 이력이 없는 라라벨 5.4에서 작동하는 최종 버전의 예제 코드 입니다.

```bash
# 기존에 호스트 파일에 myapp.dev 레코드를 추가하지 않았다면. 
~ $ sudo echo "127.0.0.1 myapp.dev" >> /etc/hosts
~ $ sudo echo "127.0.0.1 api.myapp.dev" >> /etc/hosts

# 예제 코드 저장소를 복제하지 않았다면.
~ $ git clone git@github.com:appkr/l5code.git
~ $ cd l5code

# 예제 코드 프로젝트를 셋팅한 적이 없다면.
~/l5code(master) $ cp .env.example .env
~/l5code(master) $ chmod -R 775 storage bootstrap/cache public/files
~/l5code(master) $ php artisan key:generate

# 로컬 복제된 예제 코드를 이미 가지고 있다면.
~/l5code(master) $ git pull --all

~/l5code(master) $ git checkout laravel54
~/l5code(laravel54) $ composer install
~/l5code(laravel54) $ php artisan migrate:refresh --seed --force

# 이전 버전에서 예제 코드를 구동한 적이 있다면. 
~/l5code(laravel55) $ php artisan view:clear; php artisan cache:clear; php artisan config:clear; php artisan route:clear

~/l5code(laravel54) $ php artisan serve --host=myapp.dev
```

브라우저에서 http://myapp.dev:8000 을 열어 확인합니다.

```bash
~/l5code(laravel54) $ touch tests/database.sqlite
~/l5code(laravel54) $ php artisan migrate --seed --database=testing
~/l5code(laravel54) $ vendor/bin/phpunit
```

API 작동 테스트를 위한 포스트맨 콜렉션은 여기서 받을 수 있습니다.

https://www.getpostman.com/collections/56ac056289864393eea5

### 3. 업그레이드 방법

> 아래는 저자가 작업한 방식입니다. 제 기억을 복기하기 위해 기록해 둔 것입니다.

라라벨 5.4 클린 프로젝트를 생성한다.

```bash
~ $ composer create-project laravel/laravel laravel54 5.4.30
~ $ cd laravel54
~/laravel54 $ git init
~/laravel54(master) $ git add .
~/laravel54(master) $ git commit -m '라라벨 5.4 클린 프로젝트 생성'
```

라라벨 5.3 클린 프로젝트를 생성한다.

```bash
~ $ composer create-project laravel/laravel laravel53 5.3.0
~ $ cd laravel53
~/laravel53 $ git init
~/laravel53(master) $ git add .
~/laravel53(master) $ git commit -m '라라벨 5.3 클린 프로젝트 생성'
```

5.3으로 작성된 예제 프로젝트를 방금 생성한 라라벨 5.3 클린 프로젝트에 덮어 쓴다.

```bash
~/laravel53(master) $ cp -r ../l5code/* ./
~/laravel53(master) $ git add .
~/laravel53(master) $ git commit -m 'Diff를 위해 출판용 예제 코드 복제'
```

변경된 파일만 뽑아내서, 라라벨 5.4 클린 프로젝트에 덮어 쓴다.

```bash
~/laravel53(master) $ git rev-parse --short HEAD
~/laravel53(master) $ git diff-tree --no-commit-id --name-only -r d9c6942 | xargs -I{} rsync -R {} ../laravel54
~/laravel53(master) $ cp ../l5code/.env ./
~/laravel53(master) $ cp ../l5code/.env.example ./
~/laravel53(master) $ cp ../l5code/.editorconfig ./
~/laravel53(master) $ cp ../l5code/.gitignore ./
```

[업그레이드 가이드](https://laravel.com/docs/5.4/upgrade)에 따라 코드를 수정하고, 작동을 확인한다.

```bash
# Delete "require" block of composer.json, and "composer require package" 
# one by one is more safe (than resolving conflicts manually)
~/laravel54(master) $ rm -rf composer.lock vendor
~/laravel54(master) $ composer install
~/laravel54(master) $ phpstrom .
```
