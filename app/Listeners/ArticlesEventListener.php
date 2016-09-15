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
        if ($event->action === 'created') {
            \Log::info(
                sprintf(
                    '새로운 포럼 글이 등록되었습니다.: %s',
                    $event->article->title
                )
            );
        }
    }
}
