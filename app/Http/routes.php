<?php

Route::get('/', 'WelcomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::get('/docs/{file?}', 'DocsController@show');

Route::get('/docs/images/{image}', 'DocsController@image')
    ->where('image', '[\pL-\pN\._-]+-img-[0-9]{2}.png');
