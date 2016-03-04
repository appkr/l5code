<?php

Route::get('/', 'WelcomeController@index');

Route::resource('articles', 'ArticlesController');
