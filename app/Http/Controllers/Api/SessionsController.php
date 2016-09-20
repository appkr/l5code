<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SessionsController as ParentController;
use Illuminate\Http\Request;

class SessionsController extends ParentController
{
    /**
     * Make a success response.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated($token)
    {
        return response()->json([
            'token' => $token,
        ], 201, [], JSON_PRETTY_PRINT);
    }

    /**
     * Make an error response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondSocialUser()
    {
        return response()->json([
            'error' => 'social_user',
        ], 401, [], JSON_PRETTY_PRINT);
    }

    /**
     * Make an error response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondLoginFailed()
    {
        return response()->json([
            'error' => 'invalid_credentials',
        ], 401, [], JSON_PRETTY_PRINT);
    }

    /**
     * Make an error response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondNotConfirmed()
    {
        return response()->json([
            'error' => 'not_confirmed',
        ], 401, [], JSON_PRETTY_PRINT);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        // 라라벨 5.3에만 적용되는 메서드
        $seconds = app(\Illuminate\Cache\RateLimiter::class)->availableIn(
            $this->throttleKey($request)
        );

        return json()->tooManyRequestsError("account_locked:for_{$seconds}_sec");
    }

//    라라벨 5.2에만 적용되는 메서드
//    protected function sendLockoutResponse(Request $request)
//    {
//        $seconds = app(\Illuminate\Cache\RateLimiter::class)->availableIn(
//            $this->throttleKey($request)
//        );
//
//        return json()->tooManyRequestsError("account_locked:for_{$seconds}_sec");
//    }
}