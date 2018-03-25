<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ModelChanged
{
    use InteractsWithSockets, SerializesModels;

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
}
