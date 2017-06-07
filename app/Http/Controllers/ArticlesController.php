<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\ArticlesRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use File;

class ArticlesController extends Controller implements Cacheable
{
    /**
     * ArticlesController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Specify the tags for caching.
     *
     * @return string
     */
    public function cacheTags()
    {
        return 'articles';
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|null $slug
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $slug = null) {
        $cacheKey = cache_key('articles.index');

        $query = $slug
            ? \App\Tag::whereSlug($slug)->firstOrFail()->articles()
            : new Article;

        $query = $query->orderBy(
            $request->input('sort', 'created_at'),
            $request->input('order', 'desc')
        );

        if ($keyword = request()->input('q')) {
            $raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
            $query = $query->whereRaw($raw, [$keyword]);
        }

        $articles = $this->cache($cacheKey, 5, $query, 'paginate', 3);

        return $this->respondCollection($articles, $cacheKey);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = new Article;

        return view('articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ArticlesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticlesRequest $request) {
        $user = $request->user();

        $article = $user->articles()->create(
            $request->getPayload()
        );

        if (! $article) {
            flash()->error(
                trans('forum.articles.error_writing')
            );

            return back()->withInput();
        }

        // 태그 싱크
        $article->tags()->sync($request->input('tags'));

        // 첨부파일 연결
        $request->getAttachments()->each(function ($attachment) use ($article) {
            $attachment->article()->associate($article);
            $attachment->save();
        });

        event(new \App\Events\ArticlesEvent($article));
        event(new \App\Events\ModelChanged(['articles']));

        return $this->respondCreated($article);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        if (! is_api_domain()) {
            $article->view_count += 1;
            $article->save();
        }

        $comments = $article->comments()
                            ->with('replies')
                            ->withTrashed()
                            ->whereNull('parent_id')
                            ->latest()->get();

        return $this->respondInstance($article, $comments);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $this->authorize('update', $article);

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ArticlesRequest $request
     * @param \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticlesRequest $request, Article $article)
    {
        $this->authorize('update', $article);

        $payload = array_merge($request->all(), [
            'notification' => $request->has('notification'),
        ]);

        $article->update($payload);
        $article->tags()->sync($request->input('tags'));

        event(new \App\Events\ModelChanged(['articles']));
        flash()->success(
            trans('forum.articles.success_updating')
        );

        return $this->respondUpdated($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $this->deleteAttachments($article->attachments);

        $article->delete();

        event(new \App\Events\ModelChanged(['articles']));

        return response()->json([], 204, [], JSON_PRETTY_PRINT);
    }

    public function deleteAttachments(Collection $attachments)
    {
        $attachments->each(function ($attachment) {
            $filePath = attachments_path($attachment->filename);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            return $attachment->delete();
        });
    }

    /* Response Methods */

    /**
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $articles
     * @param string|null $cacheKey
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function respondCollection(LengthAwarePaginator $articles, $cacheKey = null)
    {
        return view('articles.index', compact('articles'));
    }

    /**
     * @param $article
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated($article)
    {
        flash()->success(
            trans('forum.articles.success_writing')
        );

        return redirect(route('articles.show', $article->id));
    }

    /**
     * @param \App\Article $article
     * @param \Illuminate\Database\Eloquent\Collection $comments
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function respondInstance(Article $article, Collection $comments)
    {
        return view('articles.show', compact('article', 'comments'));
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondUpdated(Article $article)
    {
        flash()->success(trans('forum.articles.success_updating'));

        return redirect(route('articles.show', $article->id));
    }
}
