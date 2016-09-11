<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('docs/{file?}', 'DocsController@show');

//DB::listen(function ($query) {
//    var_dump($query->sql);
//});