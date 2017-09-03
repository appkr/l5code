<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PasswordRemindCreated
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $token;

    /**
     * Create a new event instance.
     *
     * @param $email
     * @param $token
     */
    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }
}
