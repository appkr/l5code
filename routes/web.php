<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('docs/{file?}', function ($file = null) {
    $text = (new App\Documentation)->get($file);

    return app(ParsedownExtra::class)->text($text);
});

//DB::listen(function ($query) {
//    var_dump($query->sql);
//});