<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('markdown', function () {
    $text =<<<EOT
# 마크다운 예제 1

이 문서는 [마크다운][1]으로 썼습니다. 화면에는 HTML로 변환되어 출력됩니다.

## 순서없는목록

- 첫 번째 항목
- 두 번째 항목[^1]

[1]: http://daringfireball.net/projects/markdown 
[^1]: 두 번째 항목_ http://google.com
EOT;

    return app(ParsedownExtra::class)->text($text);
});

//DB::listen(function ($query) {
//    var_dump($query->sql);
//});