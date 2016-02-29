<?php

Route::get('/', function () {
    return view('errors.503');

    //return view('errors/503');

    //return View::make('errors/503');

    //return view('welcome')->with('name', 'Foo');

    //return view('welcome')->with([
    //    'name' => 'Foo',
    //    'greeting' => '안녕하세요?',
    //]);

    //return view('welcome', [
    //    'name' => 'Foo',
    //    'greeting' => '안녕하세요?',
    //]);
});
