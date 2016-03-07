<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ArticlesEvent extends Event
{
    use SerializesModels;

    /**
     * @var \App\Article
     */
    public $article;

    /**
     * @var string
     */
    public $action;

    /**
     * Create a new event instance.
     *
     * @param \App\Article $article
     * @param string       $action
     */
    public function __construct(\App\Article $article, $action = 'created')
    {
        $this->article = $article;
        $this->action = $action;
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
