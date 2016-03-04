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
        return view('welcome');
    }
}
