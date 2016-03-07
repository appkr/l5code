<?php

Route::get('/', 'WelcomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('mail', function () {
    $article = App\Article::with('user')->find(1);

    return Mail::send(
        'emails.articles.created',
        compact('article'),
        function ($message) use ($article) {
            $message->to('yours@domain');
            $message->subject(sprintf('새 글이 등록되었습니다 - %s', $article->title));
        }
    );

//    return Mail::send(
//        'emails.articles.created',
//        compact('article'),
//        function ($message) use ($article) {
//            $message->from('yours1@domain', 'Your Name');
//            $message->to(['yours2@domain', 'yours3@domain']);
//            $message->subject(sprintf('새 글이 등록되었습니다 - %s', $article->title));
//            $message->cc('yours4@domain');
//            $message->attach(storage_path('elephant.png'));
//        }
//    );
//
//    return Mail::send(
//        ['text' => 'emails.articles.created-text'],
//        compact('article'),
//        function ($message) use ($article) {
//            $message->to('yours@domain');
//            $message->subject(sprintf('새 글이 등록되었습니다 - %s', $article->title));
//        }
//    );
//
//    return Mail::send(
//        'emails.articles.created',
//        compact('article'),
//        function ($message) use ($article) {
//            $message->to('yours@domain.com');
//            $message->subject(sprintf('새 글이 등록되었습니다 - %s', $article->title));
//        }
//    );
});
