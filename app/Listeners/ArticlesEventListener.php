<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticlesEventListener
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
     * @param \App\Events\ArticlesEvent $event
     */
    public function handle(\App\Events\ArticlesEvent $event)
    {
        $article = $event->article;

        if ($event->action === 'created') {
            $view = 'emails.'.app()->getLocale().'.articles.created';

            $this->mailer->send($view, compact('article'), function ($message) {
                $message->to(config('mail.from.address'));
                $message->subject(trans('emails.articles.created'));
            });

//            \Log::info(trans('emails.articles.created', ['title' => $event->article->title]));
        }
    }
}
