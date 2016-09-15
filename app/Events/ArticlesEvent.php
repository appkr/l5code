<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ArticlesEvent
{
    use InteractsWithSockets, SerializesModels;

    public $article;
    public $action;

    /**
     * Create a new event instance.
     *
     * @param \App\Article $article
     * @param string $action
     */
    public function __construct(\App\Article $article, $action = 'created')
    {
        $this->article = $article;
        $this->action = $action;
    }
}
