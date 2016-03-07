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

    /**
     * Set locale.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function locale()
    {
        $cookie = cookie()->forever('locale__myapp', request('locale'));

        cookie()->queue($cookie);

        return ($return = request('return'))
            ? redirect(urldecode($return))->withCookie($cookie)
            : redirect('/')->withCookie($cookie);
    }
}
