<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentsEvent
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Comment
     */
    public $comment;

    /**
     * Create a new event instance.
     *
     * @param \App\Comment $comment
     */
    public function __construct(\App\Comment $comment)
    {
        $this->comment = $comment;
    }
}
