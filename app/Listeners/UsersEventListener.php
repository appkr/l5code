<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UsersEventListener
{
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
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe(\Illuminate\Events\Dispatcher $events)
    {
        // 코드 23-15
        // $events->listen() 구문을 여러 개 써서 이벤트와 처리 로직을 연결할 수 있습니다.
        $events->listen(
            \App\Events\UserCreated::class,
            __CLASS__ . '@onUserCreated'
        );

        // 코드 23-30
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
        $view = 'emails.'.app()->getLocale().'.auth.confirm';

        \Mail::send(
            $view,
            compact('user'),
            function ($message) use ($user) {
                $message->to($user->email);
                $message->subject(trans('emails.auth.confirm'));
            }
        );
    }

    public function onPasswordRemindCreated(\App\Events\PasswordRemindCreated $event) {
        $view = 'emails.'.app()->getLocale().'.passwords.reset';

        \Mail::send(
            $view,
            ['token' => $event->token],
            function ($message) use ($event) {
                $message->to($event->email);
                $message->subject(trans('emails.passwords.reset'));
            }
        );
    }
}
