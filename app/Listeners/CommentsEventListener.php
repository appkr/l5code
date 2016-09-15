<?php

namespace App\Listeners;

use App\Events\CommentsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentsEventListener
{
    /**
     * Handle the event.
     *
     * @param  CommentsEvent  $event
     * @return void
     */
    public function handle(CommentsEvent $event)
    {
        $comment = $event->comment; $comment->load('commentable');
        $to = $this->recipients($comment);

        if (! $to) {
            return;
        }

        \Mail::send(
            'emails.comments.created',
            compact('comment'),
            function ($message) use($to) {
                $message->to($to);
                $message->subject(
                    sprintf(
                        '[%s] 새로운 댓글이 등록되었습니다.',
                        config('project.name')
                    )
                );
            }
        );
    }

    /**
     * Recursively find email address from the given comment
     * and push them to recipients list.
     *
     * @param \App\Comment $comment
     * @return array
     */
    private function recipients(\App\Comment $comment)
    {
        static $to = [];

        if ($comment->parent) {
            $to[] = $comment->parent->user->email;
            $this->recipients($comment->parent);
        }

        if ($comment->commentable->notification) {
            $to[] = $comment->commentable->user->email;
        }

        return array_unique($to);
    }
}
