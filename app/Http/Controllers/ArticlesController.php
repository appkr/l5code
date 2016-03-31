<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function cacheKeys()
    {
        return 'articles';
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param null $slug
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $slug = null)
    {
        $cacheKey = cache_key('articles.index');

        $query = $slug
            ? \App\Tag::whereSlug($slug)->firstOrFail()->articles()
            : new \App\Article;

        $query = $query->orderBy(
            $request->input('sort', 'created_at'),
            $request->input('order', 'desc')
        );

        if ($keyword = request()->input('q')) {
            $raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
            $query = $query->whereRaw($raw, [$keyword]);
        }

        $articles = $this->cache($cacheKey, 5, $query, 'paginate', 3);

        return $this->respondCollection($articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = new \App\Article;

        return view('articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ArticlesRequest|\Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\ArticlesRequest $request)
    {
        $payload = array_merge($request->all(), [
            'notification' => $request->has('notification'),
        ]);

        $article = $request->user()->articles()->create($payload);
//        $article = \App\User::find(1)->articles()->create($payload);

        if (! $article) {
            flash()->error(trans('forum.articles.error_writing'));

            return back()->withInput();
        }

        $article->tags()->sync($request->input('tags'));

        if ($request->has('attachments')) {
            $attachments = \App\Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->article()->associate($article);
                $attachment->save();
            });
        }

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
    public function show(\App\Article $article)
    {
        $article->view_count += 1;
        $article->save();

        $cacheKey = cache_key('articles.' . $article->id . '.comments');
        $query = $article->comments()->with('replies')->withTrashed()
            ->whereNull('parent_id')->latest();
        $comments = $this->cache($cacheKey, 5, $query, 'get');

        return $this->respondInstance($article, $comments);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Article $article)
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
    public function update(\App\Http\Requests\ArticlesRequest $request, \App\Article $article)
    {
        $payload = array_merge($request->all(), [
            'notification' => $request->has('notification'),
        ]);

        $article->update($payload);
        $article->tags()->sync($request->input('tags'));

        event(new \App\Events\ModelChanged(['articles']));

        return $this->respondUpdated($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Article $article
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, \App\Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();

        event(new \App\Events\ModelChanged(['articles']));

        return response()->json([], 204);
    }

    /* Response Methods */

    /**
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $articles
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function respondCollection(\Illuminate\Contracts\Pagination\LengthAwarePaginator $articles)
    {
        return view('articles.index', compact('articles'));
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated(\App\Article $article)
    {
        flash()->success(trans('forum.articles.success_writing'));

        return redirect(route('articles.show', $article->id));
    }

    /**
     * @param \App\Article $article
     * @param \Illuminate\Database\Eloquent\Collection $comments
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function respondInstance(\App\Article $article, \Illuminate\Database\Eloquent\Collection $comments)
    {
        return view('articles.show', compact('article', 'comments'));
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondUpdated(\App\Article $article)
    {
        flash()->success(trans('forum.articles.success_updating'));

        return redirect(route('articles.show', $article->id));
    }
}
