<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\PasswordsController as ParentController;

class PasswordsController extends ParentController
{
    /**
     * Make a success response.
     *
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondSuccess($message)
    {
        return response()->json([
            'success' => 'reset_email_sent'
        ], 200, [], JSON_PRETTY_PRINT);
    }
}