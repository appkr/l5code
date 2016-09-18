<?php

Route::group([
    'domain' => config('project.api_domain'),
    'namespace' => 'Api',
    'as' => 'api.',
], function () {
    /* api.v1 */
    Route::group([
        'prefix' => 'v1',
        'namespace' => 'v1',
        'as' => 'v1.',
    ], function () {
        /* 환영 메시지 */
        Route::get('/', [
            'as' => 'index',
            'uses' => 'WelcomeController@index',
        ]);

        /* 포럼 API */
        // 아티클
        Route::resource('articles', 'ArticlesController');

        // 태그별 아티클 (중첩 라우트)
        Route::get('tags/{slug}/articles', [
            'as' => 'tags.articles.index',
            'uses' => 'ArticlesController@index',
        ]);

        // 태그
        Route::get('tags', [
            'as' => 'tags.index',
            'uses' => 'ArticlesController@tags',
        ]);

        // 첨부 파일
        Route::resource('attachments', 'AttachmentsController', ['only' => ['store', 'destroy']]);

        // 아티클별 첨부 파일
        Route::resource('articles.attachments', 'AttachmentsController', ['only' => ['index']]);

        // 댓글
        Route::resource('comments', 'CommentsController', ['only' => ['show', 'update', 'destroy']]);

        // 아티클별 댓글
        Route::resource('articles.comments', 'CommentsController', ['only' => ['index', 'store']]);

        // 투표
        Route::post('comments/{comment}/votes', [
            'as' => 'comments.vote',
            'uses' => 'CommentsController@vote',
        ]);
    });
});