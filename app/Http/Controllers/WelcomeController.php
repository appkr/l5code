<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    /**
     * Respond welcome message.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
//        flash('환영합니다.');
//        flash()->success('성공했습니다.');
//        flash()->warning('경고! 누구세요?');
//        flash()->error('오류가 발생했습니다.');

        return view('welcome');
    }
}
