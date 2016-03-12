<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UsersEventListener
{
    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     */
    public function __construct(\Illuminate\Contracts\Mail\Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->last_login = \Carbon\Carbon::now();

        return $event->user->save();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe(\Illuminate\Events\Dispatcher $events)
    {
        $events->listen(
            \App\Events\UserCreated::class,
            __CLASS__ . '@onUserCreated'
        );

        $events->listen(
            \App\Events\PasswordRemindCreated::class,
            __CLASS__ . '@onPasswordRemindCreated'
        );
    }

    /**
     * Handle the given event.
     *
     * @param \App\Events\UserCreated $event
     */
    public function onUserCreated(\App\Events\UserCreated $event)
    {
        $user = $event->user;

        $this->mailer->send('emails.auth.confirm', compact('user'), function ($message) use ($user){
            $message->to($user->email);
            $message->subject(
                sprintf('[%s] 회원가입을 확인해주세요.', config('project.name'))
            );
        });
    }

    /**
     * Handle the given event.
     *
     * @param \App\Events\PasswordRemindCreated $event
     */
    public function onPasswordRemindCreated(\App\Events\PasswordRemindCreated $event)
    {
        $this->mailer->send('emails.passwords.reset', ['token' => $event->token], function ($message) use ($event) {
            $message->to($event->email);
            $message->subject(
                sprintf('[%s] 비밀번호를 초기화하세요.', config('project.name'))
            );
        });
    }
}
