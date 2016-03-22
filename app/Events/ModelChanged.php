<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ModelChanged extends Event
{
    use SerializesModels;

    /**
     * @var string|array
     */
    public $cacheTags;

    /**
     * Create a new event instance.
     *
     * @param string $cacheTags
     */
    public function __construct($cacheTags)
    {
        $this->cacheTags = $cacheTags;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
