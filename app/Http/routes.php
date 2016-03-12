<?php

Route::get('/', 'WelcomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('markdown', function () {
    $text = <<<EOT
# 마크다운 예제 1

이 문서는 [마크다운][1]으로 썼습니다. 화면에는 HTML로 변환되어 출력됩니다.

## 순서 없는 목록

- 첫번째 항목
- 두번째 항목[^1]
- 다른 항목

[1]: http://daringfireball.net/projects/markdown

[^1]: 두번째 항목_ http://google.com
EOT;

    return app(ParsedownExtra::class)->text($text);
});
