<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        $token = is_api_domain()
            ? jwt()->attempt($request->only('email', 'password'))
            : auth()->attempt($request->only('email', 'password'), $request->has('remember'));

        if (! $token) {
            if (\App\User::socialUser($request->input('email'))->first()) {
                return $this->respondSocialUser();
            }

            return $this->respondLoginFailed();
        }

        if (! auth()->user()->activated) {
            auth()->logout();

            return $this->respondNotConfirmed();
        }

        return $this->respondCreated($token);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {
        auth()->logout();

        flash(trans('auth.sessions.info_bye'));

        return redirect('/');
    }

    /**
     * Make an error response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondSocialUser()
    {
        flash()->error(trans('auth.sessions.error_social_user'));

        return back()->withInput();
    }

    /**
     * Make an error response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondLoginFailed()
    {
        flash()->error(trans('auth.sessions.error_incorrect_credentials'));

        return back()->withInput();
    }

    /**
     * Make an error response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondNotConfirmed()
    {
        flash()->error(trans('auth.sessions.error_not_confirmed'));

        return back()->withInput();
    }

    /**
     * Make a success response.
     *
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated($token)
    {
        flash(trans('auth.sessions.info_welcome', ['name' => auth()->user()->name]));

        return ($return = request('return'))
            ? redirect(urldecode($return))
            : redirect()->intended();
    }
}
