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
        return json()->withCollection(
            \App\Tag::all(),
            new \App\Transformers\TagTransformer
        );
    }

    /**
     * @param LengthAwarePaginator $articles
     * @param string $cacheKey
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCollection(LengthAwarePaginator $articles, $cacheKey)
    {
        $reqEtag = request()->getETags();
        $genEtag = $this->etags($articles, $cacheKey);

        if (config('project.etag') and isset($reqEtag[0]) and $reqEtag[0] === $genEtag) {
            return json()->notModified();
        }

        return json()->setHeaders(['Etag' => $genEtag])->withPagination(
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
        $cacheKey = cache_key('articles.'.$article->id);
        $reqEtag = request()->getETags();
        $genEtag = $this->etag($article, $cacheKey);

        if (config('project.etag') and isset($reqEtag[0]) and $reqEtag[0] === $genEtag) {
            return json()->notModified();
        }

        return json()->setHeaders(['Etag' => $genEtag])->withItem(
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
        return json()->success('updated');
    }
}
