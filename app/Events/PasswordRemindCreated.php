<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PasswordRemindCreated extends Event
{
    use SerializesModels;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $token;

    /**
     * PasswordRemindCreated constructor.
     *
     * @param string $email
     * @param string $token
     */
    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
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
