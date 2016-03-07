<?php

Route::get('/', 'WelcomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('/docs/{file}', function ($file) {
    $text = (new App\Documentation)->get($file);

    return app(ParsedownExtra::class)->text($text);
});
