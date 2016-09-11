<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

/* Markdown Viewer */
Route::get('docs/{file?}', 'DocsController@show');
Route::get('docs/images/{image}', 'DocsController@image')
    ->where('image', '[\pL-\pN\._-]+-img-[0-9]{2}.png');

//DB::listen(function ($query) {
//    var_dump($query->sql);
//});