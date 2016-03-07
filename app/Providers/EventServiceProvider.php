<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ArticlesEvent::class => [
            \App\Listeners\ArticlesEventListener::class,
        ],
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\UsersEventListener::class
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

//        $events->listen('article.created', function($article) {
//            var_dump('이벤트를 받았습니다. 받은 데이터(상태)는 다음과 같습니다.');
//            var_dump($article->toArray());
//        });

//        $events->listen(
//            'article.created',
//            \App\Listeners\ArticlesEventListener::class
//        );

//        $events->listen(
//            \App\Events\ArticleCreated::class,
//            \App\Listeners\ArticlesEventListener::class
//        );
    }
}
