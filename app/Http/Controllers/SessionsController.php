<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SessionsController extends Controller
{
    /**
     * SessionsController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * Handle login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (! auth()->attempt($request->only('email', 'password'), $request->has('remember'))) {
            return $this->respondError('이메일 또는 비밀번호가 맞지 않습니다.');
        }

        if (! auth()->user()->activated) {
            auth()->logout();

            return $this->respondError('가입확인해 주세요.');
        }

        return $this->respondCreated(auth()->user()->name . '님, 환영합니다.');
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {
        auth()->logout();
        flash('또 방문해 주세요.');

        return redirect('/');
    }

    /* Helpers */

    /**
     * Make an error response.
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondError($message)
    {
        flash()->error($message);

        return back()->withInput();
    }

    /**
     * Make a success response.
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated($message)
    {
        flash($message);

        return redirect()->intended('home');
    }
}
