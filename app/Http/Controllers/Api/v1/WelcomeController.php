<?php

namespace App\Http\Controllers\Api\v1;

class WelcomeController extends \App\Http\Controllers\Controller
{
    /**
     * Respond welcome message.
     */
    public function index()
    {
        return json([
            'name'    => config('app.name').' API',
            'message' => 'This is a base endpoint of v1 API.',
            'links'   => [
                [
                    'rel'  => 'self',
                    'href' => route(\Route::currentRouteName())
                ],
                [
                    'rel'  => 'api.v1.articles.index',
                    'href' => route('api.v1.articles.index')
                ],
                [
                    'rel'  => 'api.v1.tags.index',
                    'href' => route('api.v1.tags.index')
                ],
            ],
        ]);
    }
}
