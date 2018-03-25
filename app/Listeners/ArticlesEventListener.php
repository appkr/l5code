<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticlesEventListener
{
    /**
     * Handle the event.
     *
     * @param \App\Events\ArticlesEvent $event
     */
    public function handle(\App\Events\ArticlesEvent $event)
    {
        $article = $event->article;

        if ($event->action === 'created') {
            $view = 'emails.'.app()->getLocale().'.articles.created';

            \Mail::send(
                $view,
                compact('article'),
                function ($message) {
                    $message->to(config('mail.from.address'));
                    $message->subject(trans('emails.articles.created'));
                }
            );
        }

//        if ($event->action === 'created') {
//            \Log::info(
//                sprintf(
//                    '새로운 포럼 글이 등록되었습니다.: %s',
//                    $event->article->title
//                )
//            );
//        }
    }
}
