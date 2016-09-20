<?php

namespace App\Http\Controllers\Api\v1;

use App\Article;
use App\Http\Controllers\ArticlesController as ParentController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ArticlesController extends ParentController
{
    /**
     * ArticlesController constructor.
     */
    public function __construct()
    {
//        HTTP 기본 인증으로 인증을 받은 후, 부모 컨트롤러 생성자의 인증 미들웨어를 타게 되므로
//        아래 로직은 정상적으로 작동해야 한다. 프레임워크 버그로 추정된다.
//        $this->middleware('auth.basic.once', ['except' => ['index', 'show', 'tags']]);
//        parent::__construct();

//        parent::__construct();
//        $this->middleware = [];
//        $this->middleware('auth.basic.once', ['except' => ['index', 'show', 'tags']]);

        parent::__construct();
        $this->middleware = [];
        $this->middleware('jwt.auth', ['except' => ['index', 'show', 'tags']]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function tags() {
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
     * @param $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated($article)
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
     * @return string
     */
    protected function respondInstance(Article $article, Collection $comments)
    {
        return $article->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUpdated(Article $article)
    {
        return response()->json([
            'success' => 'updated'
        ], 200, [], JSON_PRETTY_PRINT);
    }
}