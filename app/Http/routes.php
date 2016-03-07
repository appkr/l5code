<?php

Route::get('/', 'WelcomeController@index');

Route::resource('articles', 'ArticlesController');

//DB::listen(function($query){
//    var_dump($query->sql);
//});
