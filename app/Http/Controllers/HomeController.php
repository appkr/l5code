<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        flash('환영합니다.');

//        flash()->success('성공했습니다.');
//        flash('성공했습니다.', 'success');

//        flash()->warning('경고! 누구세요?');
//        flash('경고! 누구세요?', 'warning');

//        flash()->danger('오류가 발생했습니다.');
//        flash('오류가 발생했습니다.', 'danger');

        return view('home');
    }
}
