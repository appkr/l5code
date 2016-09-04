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

Route::get('/', function () {
    $items = ['apple', 'banana', 'tomato'];

    return view('welcome', ['items' => $items]);
});

// 페이지 30
// 템플릿 상속을 테스트하기 위한 라우트
Route::get('/', function () {
    return view('welcome');
});