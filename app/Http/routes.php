<?php

Route::get('/', function () {
    return view('welcome', [
        'name' => 'Foo',
        'greeting' => '안녕하세요?',
    ]);

//    $items = ['apple', 'banana', 'tomato'];
//    return view('welcome', ['items' => $items]);

//    return view('welcome');
});
