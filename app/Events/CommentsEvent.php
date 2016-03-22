<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentsEvent extends Event
{
    use SerializesModels;

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
