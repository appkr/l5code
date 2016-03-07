<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticlesEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
//    public function handle(\App\Article $article)
//    public function handle(\App\Events\ArticleCreated $event)
    public function handle(\App\Events\ArticlesEvent $event)
    {
//        var_dump('이벤트를 받았습니다. 받은 데이터(상태)는 다음과 같습니다.');
//        var_dump($event->article->toArray());

        if ($event->action === 'created') {
            \Log::info(sprintf(
                '새로운 포럼 글이 등록되었습니다.: %s',
                $event->article->title
            ));
        }
    }
}
