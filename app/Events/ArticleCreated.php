<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ArticleCreated extends Event
{
    use SerializesModels;

    /**
     * @var \App\Article
     */
    public $article;

    /**
     * Create a new event instance.
     *
     * @param \App\Article $article
     */
    public function __construct(\App\Article $article)
    {
        $this->article = $article;
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
