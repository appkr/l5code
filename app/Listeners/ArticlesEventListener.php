<?php

namespace App\Listeners;

//use App\Events\article.created;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticlesEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(\App\Events\ArticlesEvent $event)
    {
        //        14.5. 실용적인 이벤트 시스템
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

