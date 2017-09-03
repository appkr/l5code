<?php

namespace App\Listeners;

use App\Events\ModelChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CacheHandler
{
    /**
     * Handle the event.
     *
     * @param  ModelChanged  $event
     * @return void
     */
    public function handle(ModelChanged $event)
    {
        if (! taggable()) {
            // 태깅이 불가능한 캐시 저장소를 사용한다.
            // 캐시를 전부 삭제한다.
            return \Cache::flush();
        }

        // 캐시 태그에 해당하는 캐시만 삭제한다.
        return \Cache::tags($event->cacheTags)->flush();
    }
}
