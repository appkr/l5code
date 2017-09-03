## 예제 프로젝트 5.4 마이그레이션

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

## API 작동 테스트를 위한 포스트맨 콜렉션

https://www.getpostman.com/collections/56ac056289864393eea5
