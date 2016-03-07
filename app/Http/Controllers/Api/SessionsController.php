<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SessionsController as ParentController;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;

class SessionsController extends ParentController
{
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
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = app(RateLimiter::class)->availableIn(
            $this->getThrottleKey($request)
        );

        return json()->tooManyRequestsError("account_locked:for_{$seconds}_sec");
    }
}
