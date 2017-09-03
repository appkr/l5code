<?php

namespace App\Http\Controllers\Api\v1;

use App\Article;
use App\EtagTrait;
use App\Http\Controllers\ArticlesController as ParentController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ArticlesController extends ParentController
{
    use EtagTrait;

    /**
     * ArticlesController constructor.
     */
    public function __construct()
    {
//        HTTP 기본 인증으로 인증을 받은 후, 부모 컨트롤러 생성자의 인증 미들웨어를 타게 되므로
//        아래 로직은 정상적으로 작동해야 한다. 프레임워크 버그로 추정된다.
//        $this->middleware('auth.basic.once', ['except' => ['index', 'show', 'tags']]);
//        parent::__construct();

        parent::__construct();
        $this->middleware = [];
        $this->middleware('jwt.auth', ['except' => ['index', 'show', 'tags']]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function tags() {
        return json()->withCollection(
            \App\Tag::all(),
            new \App\Transformers\TagTransformer
        );
    }

    /**
     * @param LengthAwarePaginator $articles
     * @param string|null $cacheKey
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCollection(LengthAwarePaginator $articles, $cacheKey = null)
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
     * @param \Illuminate\Database\Eloquent\Collection $comments
     * @return string
     */
    protected function respondInstance(Article $article, Collection $comments)
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
     * @param $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated($article)
    {
        return json()->setHeaders([
            'Location' => route('api.v1.articles.show', $article->id),
        ])->created('created');
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUpdated(Article $article)
    {
        return json()->success('updated');
    }
}