<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

//14.1. 이벤트 시스템 작동 기본 원리
//Event::listen('article.created', function ($article) {
//    var_dump('이벤트를 받았습니다. 받은 데이터(상태)는 다음과 같습니다.');
//    var_dump($article->toArray());
//});

//DB::listen(function ($query) {
//    var_dump($query->sql);
//});