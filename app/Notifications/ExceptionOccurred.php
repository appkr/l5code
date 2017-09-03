<?php

namespace App\Notifications;

use Exception;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ExceptionOccurred extends Notification
{
    private $e;

    public function __construct(Exception $e)
    {
        $this->e = $e;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->error()
            ->content(sprintf(
                "%s \n\n%s \n%s:%d \n\n%s",
                get_class($this->e),
                $this->e->getMessage(),
                $this->e->getFile(),
                $this->e->getLine(),
                $this->e->getTraceAsString()
            ));
    }
}
