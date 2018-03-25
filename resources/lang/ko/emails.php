<?php

return [

    'articles' => [
        'created' => sprintf('[%s] %s', config('app.name'), '새로운 포럼 글이 등록되었습니다.: :title'),
    ],

    'auth' => [
        'confirm' => sprintf('[%s] %s', config('app.name'), '회원가입을 확인해주세요.'),
    ],

    'comments' => [
        'created' => sprintf('[%s] %s', config('app.name'), '새로운 댓글이 등록되었습니다.'),
    ],

    'passwords' => [
        'reset' => sprintf('[%s] %s', config('app.name'), '비밀번호를 초기화하세요.'),
    ],

];

