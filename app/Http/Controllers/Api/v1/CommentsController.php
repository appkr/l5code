<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\CommentsController as ParentController;

class CommentsController extends ParentController
{
    /**
     * CommentsController constructor.
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Article $article
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(\App\Article $article)
    {
        return json()->withPagination(
            $article->comments,
            new \App\Transformers\CommentTransformer
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Comment $comment
     * @return \App\Comment
     */
    public function show(\App\Comment $comment)
    {
        return json()->withItem(
            $comment,
            new \App\Transformers\CommentTransformer
        );
    }

    /**
     * @param \App\Article $article
     * @param $comment
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated(\App\Article $article, $comment)
    {
        return json()->setHeaders([
            'Location' => route('api.v1.comments.show', $comment->id)
        ])->created('created');
    }

    /**
     * @param \App\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUpdated(\App\Comment $comment)
    {
        return json()->success('updated');
    }
}
