<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SessionsController as ParentController;

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
}
