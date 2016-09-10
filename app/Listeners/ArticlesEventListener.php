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
    //    public function handle(\App\Article $article)
    //    {
    ////        14.3. 이벤트 리스너 클래스 이용
    //        var_dump('이벤트를 받았습니다. 받은 데이터(상태)는 다음과 같습니다.');
    //        var_dump($article->toArray());
    //    }

//    public function handle(\App\Events\ArticleCreated $event)
//    {
//        //        14.4. 이벤트 클래스 이용
//        dump('이벤트를 받았습니다. 받은 데이터(상태)는 다음과 같습니다.');
//        dump($event->article->toArray());
//    }

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

