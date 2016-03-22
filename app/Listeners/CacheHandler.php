<?php

namespace App\Listeners;

use App\Events\ModelChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CacheHandler
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
     *
     * @param  ModelChanged  $event
     * @return void
     */
    public function handle(ModelChanged $event)
    {
        if (! taggable()) {
            // Remove all cache store
            return \Cache::flush();
        }

        // Remove only cache that has the given tag(s)
        return \Cache::tags($event->cacheTags)->flush();
    }
}
