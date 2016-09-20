<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    use \Illuminate\Foundation\Auth\ThrottlesLogins;

    /**
     * 지정된 횟수를 초과해서 로그인이 틀렸을 때 로그인이 잠기는 시간.
     *
     * @var int
     */
    protected $lockoutTime = 60;

    /**
     * 틀린 로그인을 몇 번까지 허용할 지를 설정한다.
     *
     * @var int
     */
    protected $maxLoginAttempts = 5;

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

        // ThrottlesLogins 트레이트를 사용하면 사용자의 로그인 아이디와 IP 주소를 조합하여
        // 로그인 횟수 제한 기능을 활성화할 수 있다.
        $throttles = method_exists($this, 'hasTooManyLoginAttempts');

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $token = is_api_domain()
            ? jwt()->attempt($request->only('email', 'password'))
            : auth()->attempt($request->only('email', 'password'), $request->has('remember'));

        if (! $token) {
            if (\App\User::socialUser($request->input('email'))->first()) {
                return $this->respondSocialUser();
            }

            if ($throttles && ! $lockedOut) {
                // 로그인에 성공하지 못하면 로그인 실패 횟수가 증가시킨다.
                // $maxLoginAttempts로 정한 횟수를 초과해서 실패하면
                // $lockoutTime(초) 동안 로그인을 할 수 없다.
                $this->incrementLoginAttempts($request);
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
        flash(
            trans('auth.sessions.info_bye')
        );

        return redirect(route('root'));
    }

    /* Response Methods */

    /**
     * Make a success response.
     *
     * @param string|boolean $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated($token)
    {
        flash(
            trans('auth.sessions.info_welcome', ['name' => auth()->user()->name])
        );

        return ($return = request('return'))
            ? redirect(urldecode($return))
            : redirect()->intended(route('home'));
    }

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
     * @return $this
     */
    protected function respondSocialUser()
    {
        flash()->error(
            trans('auth.sessions.error_social_user')
        );

        return back()->withInput();
    }

    /**
     * @return $this
     */
    protected function respondLoginFailed()
    {
        flash()->error(
            trans('auth.sessions.error_incorrect_credentials')
        );

        return back()->withInput();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondNotConfirmed()
    {
        flash()->error(
            trans('auth.sessions.error_not_confirmed')
        );

        return back()->withInput();
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        // 로그인 throttling을 위한 메서드다.
        // 라라벨 5.3에서만 필요하다. 다른 버전은 아래를 참고한다.
        return 'email';
    }

    /* Helpers */
//    라라벨 5.2에서는 아래 코드 블럭이 필요하다.
//    public function loginUsername()
//    {
//        return 'email';
//    }
}
