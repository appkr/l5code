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
        return $article->comments()->paginate(3)->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Comment $comment
     * @return \App\Comment
     */
    public function show(\App\Comment $comment)
    {
        return $comment->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * @param \App\Article $article
     * @param $comment
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated(\App\Article $article, $comment)
    {
        return response()->json(
            ['success' => 'created'],
            201,
            ['Location' => route('api.v1.comments.show', $comment->id)],
            JSON_PRETTY_PRINT
        );
    }

    /**
     * @param \App\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUpdated(\App\Comment $comment)
    {
        return response()->json([
            'success' => 'updated'
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
