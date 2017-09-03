<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SocialController as ParentController;
use Illuminate\Http\Request;

class SocialController extends ParentController
{
    /**
     * Verify the given user. Create one if not.
     *
     * @param \Illuminate\Http\Request $request
     * @param $provider
     * @return \Illuminate\Http\JsonResponse
     */
    protected function store(Request $request, $provider)
    {
        $user = (\App\User::whereEmail($request->email)->first())
            ?: \App\User::create([
                'name'  => $request->name ?: 'unknown',
                'email' => $request->email,
                'activated' => 1,
            ]);

        $token = jwt()->fromUser($user);

        return response()->json([
            'token' => $token,
        ], 201, [], JSON_PRETTY_PRINT);
    }
}