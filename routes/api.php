<?php

Route::group([
    'domain' => config('project.api_domain'),
    'namespace' => 'Api',
    'as' => 'api.',
    'middleware' => ['cors']
], function () {
    /* api.v1 */
    Route::group([
        'prefix' => 'v1',
        'namespace' => 'v1',
        'as' => 'v1.',
//        라라벨 5.3부터는 app/Providers/RouteServiceProvider.php 에서 이미 api 미들웨어 그룹을 적용하고 있다.
//        그리고 api 미들웨어 그룹에는 throttle 미들웨어가 포함되어 있다. 즉, 아래 구문은 필요없다.
//        라라벨 5.2를 이용한다면 이 구문을 명시적으로 써 줘야 한다.
//        라라벨 5.1을 사용한다면 graham-campbell/throttle를 설치하고 적용법은 문서를 참고한다.
//        'middleware' => ['throttle:60,1']
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


    /* 회원가입 */
    Route::post('auth/register', [
        'as' => 'users.store',
        'uses' => 'UsersController@store',
    ]);

    /**
     * 토큰 요청 및 리프레시
     *
     * 사용자가 확인되면 서버는 클라이언트에게 토큰을 반환한다.
     * 클라이언트는 토큰을 기억해야 한다.
     * 클라이언트는 리소스를 요청할 때 Authorization 헤더에 토큰을 달아서 보낸다.
     *
     * API 서비스는 세션을 유지하지 않기 때문에, 로그아웃이 필요없다.
     * 모든 API 요청은 Authorization 헤더값을 제시해야 하고,
     * 서버는 그 헤더값으로 사용자를 식별하여 인증/권한부여한다.
     */
    Route::post('auth/login', [
        'as' => 'sessions.store',
        'uses' => 'SessionsController@store',
    ]);

    Route::post('auth/refresh', [
        'middleware' => 'jwt.refresh',
        'as' => 'sessions.refresh',
        function () {
        },
    ]);

    /**
     * 소셜 로그인
     *
     * 소셜로그인은 클라이언트 측에서한다.
     * 클라이언트에서 소셜사용자가 확인되면 서버에 소셜사용자 정보를 던진다.
     * 서버는 받은 사용자 객체로 로그인인다. 없으면 만든다.
     * 로그인하면 서버는 클라이언트에게 토큰을 발급한다.
     */
    Route::post('social/{provider}', [
        'as' => 'social.login',
        'uses' => 'SocialController@store',
    ]);

    /**
     * 비밀번호 초기화
     *
     * 클라이언트가 비밀번호 바꾸기 요청을 하면 서버는 비밀번호 바꾸는 방법을 담은 메일을 보낸다.
     * 사용자가 메일에서 링크를 클릭하면 웹브라우저가 작동하고, 그 이후 모든 과정은 웹에서 이루어 진다.
     * 바꾼 비밀번호는 서버에 저장되어 있고, 다음번 클라이언트에서 바꾼 비밀번호로
     * 로그인을 시도하면 유효한 토큰을 발급 받을 수 있다.
     */
    Route::post('auth/remind', [
        'as' => 'remind.store',
        'uses' => 'PasswordsController@postRemind',
    ]);
});