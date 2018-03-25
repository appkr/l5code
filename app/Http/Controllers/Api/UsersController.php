<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\UsersController as ParentController;

class UsersController extends ParentController
{
    /**
     * Make a success response.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondUpdated(\App\User $user)
    {
        $token = jwt()->fromUser($user);

        return response()->json([
            'token' => $token
        ], 201, [], JSON_PRETTY_PRINT);
    }

    /**
     * Make a success response.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondConfirmationEmailSent()
    {
        return response()->json([
            'success' => 'confirmation_email_sent'
        ], 201, [], JSON_PRETTY_PRINT);
    }
}