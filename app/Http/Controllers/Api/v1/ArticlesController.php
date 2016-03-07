<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ArticlesController as ParentController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticlesController extends ParentController
{
    /**
     * ArticlesController constructor.
     */
    public function __construct()
    {
//        $this->middleware('auth.basic.once', ['except' => ['index', 'show']]);
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
        parent::__construct();
    }

    public function tags()
    {
        return \App\Tag::all();
    }

    /**
     * @param LengthAwarePaginator $articles
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCollection(LengthAwarePaginator $articles)
    {
        return $articles->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated(\App\Article $article)
    {
        return response()->json(
            ['success' => 'created'],
            201,
            ['Location' => route('api.v1.articles.show', $article->id)],
            JSON_PRETTY_PRINT
        );
    }

    /**
     * @param \App\Article $article
     * @param \Illuminate\Database\Eloquent\Collection $comments
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondInstance(\App\Article $article, \Illuminate\Database\Eloquent\Collection $comments)
    {
        return $article->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUpdated(\App\Article $article)
    {
        return response()->json([
            'success' => 'updated'
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
