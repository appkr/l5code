<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CommentsController extends Controller
{
    /**
     * CommentsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CommentsRequest $request
     * @param \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\CommentsRequest $request, \App\Article $article)
    {
        $comment = $article->comments()->create(array_merge(
            $request->all(),
            ['user_id' => $request->user()->id]
        ));

        flash()->success('작성하신 댓글을 저장했습니다.');

        return redirect(
            route('articles.show', $article->id) . '#comment_' . $comment->id
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\CommentsRequest $request
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\CommentsRequest $request, \App\Comment $comment)
    {
        $comment->update($request->all());

//        event(new \App\Events\ModelChanged(['articles']));

        return redirect(
            route('articles.show', $comment->commentable->id) . '#comment_' . $comment->id
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Comment $comment)
    {
        $comment->delete();

        return response()->json([], 204, [], JSON_PRETTY_PRINT);
    }

    /**
     * Vote up or down for the given comment.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request, \App\Comment $comment)
    {
        $this->validate($request, [
            'vote' => 'required|in:up,down',
        ]);

        if ($comment->votes()->whereUserId($request->user()->id)->exists()) {
            return response()->json(['error' => 'already_voted'], 409);
        }

        $up = $request->input('vote') == 'up' ? true : false;

        $comment->votes()->create([
            'user_id'  => $request->user()->id,
            'up'       => $up,
            'down'     => ! $up,
            'voted_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        return response()->json([
            'voted' => $request->input('vote'),
            'value' => $comment->votes()->sum($request->input('vote')),
        ], 201, [], JSON_PRETTY_PRINT);
    }
}
