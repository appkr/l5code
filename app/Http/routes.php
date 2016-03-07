<?php

//Event::listen('articles.stored', function($article) {
//    var_dump('이벤트를 받았습니다. 받은 데이터(상태)는 다음과 같습니다.');
//    var_dump($article->toArray());
//});

Route::get('/', 'WelcomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');
