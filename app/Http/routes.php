<?php

Route::get('/', 'WelcomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('/docs/{file?}', 'DocsController@show');
