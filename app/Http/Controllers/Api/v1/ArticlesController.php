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
        $this->middleware('jwt.auth', ['except' => ['index', 'show', 'tags']]);
        parent::__construct();
    }

    public function tags()
    {
//        return \App\Tag::all();
        return json()->withCollection(
            \App\Tag::all(),
            new \App\Transformers\TagTransformer
        );
    }

    /**
     * @param LengthAwarePaginator $articles
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCollection(LengthAwarePaginator $articles)
    {
//        return $articles->toJson(JSON_PRETTY_PRINT);
//        return (new \App\Transformers\ArticleTransformerBasic)->withPagination($articles);
        return json()->withPagination(
            $articles,
            new \App\Transformers\ArticleTransformer
        );
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated(\App\Article $article)
    {
//        return response()->json(
//            ['success' => 'created'],
//            201,
//            ['Location' => route('api.v1.articles.show', $article->id)],
//            JSON_PRETTY_PRINT
//        );
        return json()->setHeaders([
            'Location' => route('api.v1.articles.show', $article->id)
        ])->created('created');
    }

    /**
     * @param \App\Article $article
     * @param \Illuminate\Database\Eloquent\Collection $comments
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondInstance(\App\Article $article, \Illuminate\Database\Eloquent\Collection $comments)
    {
//        return $article->toJson(JSON_PRETTY_PRINT);
//        return (new \App\Transformers\ArticlesTransformerBasic)->withItem($article);
        return json()->withItem(
            $article,
            new \App\Transformers\ArticleTransformer
        );
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUpdated(\App\Article $article)
    {
//        return response()->json([
//            'success' => 'updated'
//        ], 200, [], JSON_PRETTY_PRINT);
        return json()->success('updated');
    }
}
