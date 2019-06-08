[![Build Status](https://travis-ci.org/appkr/l5code.svg?branch=laravel57)](https://travis-ci.org/appkr/l5code)

## 예제 프로젝트 5.7 업그레이드

책에 수록된 코드 예제는 라라벨 5.7 버전에서도 그대로 작동합니다. 아래 달라진 점 부분만 고쳐주시면 됩니다.

```bash
~/l5code(laravel56) $ git checkout -b laravel57 laravel56
~/l5code(laravel57) $
```

```diff
# composer.json
-    "barryvdh/laravel-cors": "^0.11.0",
+    "barryvdh/laravel-cors": "^0.11.2",
+    "beyondcode/laravel-dump-server": "^1.0",
-      "@php artisan key:generate"
+      "@php artisan key:generate --ansi"
-      "@php artisan package:discover"
+      "@php artisan package:discover --ansi"
```

```bash
~/l5code(laravel57) $ composer update
```
