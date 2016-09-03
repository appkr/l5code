<?php

Route::get('/', function () {
    return view('errors.503');
});

Route::get('/', function () {
    return view('welcome')->with('name', 'Foo');
});

Route::get('/', function () {
    return view('welcome')->with([
        'name' => 'Foo',
        'greeting' => '안녕하세요?',
    ]);
});

Route::get('/', function () {
    return view('welcome', [
        'name' => 'Foo',
        'greeting' => '환영합니다!',
    ]);
});