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
            if (\App\User::socialUser($request->input('email'))->first()) {
                return $this->respondError(
                    trans('auth.sessions.error_social_user')
                );
            }

            return $this->respondError(
                trans('auth.sessions.error_incorrect_credentials')
            );
        }

        if (! auth()->user()->activated) {
            auth()->logout();

            return $this->respondError(
                trans('auth.sessions.error_not_confirmed')
            );
        }

        return $this->respondCreated(
            trans('auth.sessions.info_welcome', ['name' => auth()->user()->name])
        );
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {
        auth()->logout();
        flash(
            trans('auth.sessions.info_bye')
        );

        return redirect(route('root'));
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

        return ($return = request('return'))
            ? redirect(urldecode($return))
            : redirect()->intended('home');
    }
}
