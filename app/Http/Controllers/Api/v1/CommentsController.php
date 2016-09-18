<?php

namespace App\Http\Controllers\Api\v1;

use App\Article;
use App\Comment;
use App\Http\Controllers\CommentsController as ParentController;

class CommentsController extends ParentController
{
    /**
     * CommentsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware = [];
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Article $article
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Article $article)
    {
        return json()->withCollection(
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
    public function show(Comment $comment)
    {
        return $comment->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * @param \App\Article $article
     * @param $comment
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated(Article $article, Comment $comment)
    {
        return json()->setHeaders([
            'Location' => route('api.v1.comments.show', $comment->id)
        ])->created('created');
    }

    /**
     * @param \App\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUpdated(Comment $comment)
    {
        return json()->success('updated');
    }
}