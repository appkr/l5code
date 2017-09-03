<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordsController extends Controller
{
    /**
     * Create new password controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRemind()
    {
        return view('passwords.remind');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postRemind(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
        ]);

        $email = $request->get('email');
        $token = str_random(64);

        \DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        event(new \App\Events\PasswordRemindCreated($email, $token));

        return $this->respondSuccess(
            trans('auth.passwords.sent_reminder')
        );
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param string|null $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReset($token = null)
    {
        return view('passwords.reset', compact('token'));
    }

    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);

        $token = $request->get('token');

        if (! \DB::table('password_resets')->whereToken($token)->first()) {
            return $this->respondError(
                trans('auth.passwords.error_wrong_url')
            );
        }

        \App\User::whereEmail($request->input('email'))->first()->update([
            'password' => bcrypt($request->input('password'))
        ]);
        \DB::table('password_resets')->whereToken($token)->delete();

        return $this->respondSuccess(
            trans('auth.passwords.success_reset')
        );
    }

    /**
     * Make an error response.
     *
     * @param     $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondError($message)
    {
        flash()->error($message);

        return back()->withInput(\Request::only('email'));
    }

    /**
     * Make a success response.
     *
     * @param $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondSuccess($message)
    {
        flash($message);

        return redirect('/');
    }
}
