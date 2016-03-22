<?php

namespace App\Listeners;

use App\Events\CommentsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentsEventListener
{
    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     */
    public function __construct(\Illuminate\Contracts\Mail\Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  CommentsEvent  $event
     * @return void
     */
    public function handle(CommentsEvent $event)
    {
        $comment = $event->comment;
        $comment->load('commentable');
        $to = $this->recipients($comment);

        if (! $to) {
            return;
        }

        $this->mailer->send('emails.comments.created', compact('comment'), function ($message) use ($to) {
            $message->to($to);
            $message->subject(
                sprintf('[%s] 새로운 댓글이 등록되었습니다.', config('project.name'))
            );
        });
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
