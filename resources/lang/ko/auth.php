<?php

return [

    'failed' => '제출하신 로그인 정보가 정확하지 않습니다.',
    'throttle' => ':seconds초 후에 다시 시도하세요.',

    'form' => [
        'name' => '이름',
        'email' => '이메일',
        'password' => '비밀번호',
        'password_confirmation' => '비밀번호 확인',
        'password_new' => '새로운 비밀번호',
    ],

    'sessions' => [
        'title' => '로그인',
        'destroy' => '로그아웃',
        'description' => '깃허브 계정으로 로그인하세요. '.config('app.name'). '계정으로 로그인할 수도 있습니다.',
        'login_with_github' => '깃허브 계정으로 로그인하기',
        'remember' => '로그인 기억하기',
        'remember_help' => '(공용 컴퓨터에서는 사용하지 마세요!)',
        'send_login' => '로그인',
        'ask_registration' => '회원이 아니라면? <a href=":url"> 가입하세요. </a>',
        'ask_forgot' => '<a href=":url"> 비밀번호를 잊으셨나요? </a>',
        'caveat_for_social' => '깃허브 로그인 사용자는 따로 회원가입하실 필요없습니다. 이 분들은 비밀번호가 없습니다.',
        'error_social_user' => '회원가입하지 않으셨습니다. 지난번엔 깃허브로 로그인하셨어요.',
        'error_incorrect_credentials' => '이메일 또는 비밀번호가 맞지 않습니다.',
        'error_not_confirmed' => '가입확인해 주세요.',
        'info_welcome' => ':name님, 환영합니다.',
        'info_bye' => '또 방문해 주세요.',
    ],

    'users' => [
        'title' => '회원가입',
        'description' => '깃허브 계정으로 로그인하면 회원가입이 필요없습니다.',
        'send_registration' => '가입하기',
        'error_wrong_url' => 'URL이 정확하지 않습니다.',
        'info_welcome' => ':name님, 환영합니다.',
        'info_confirmed' => ':name님, 환영합니다. 가입 확인되었습니다.',
        'info_confirmation_sent' => '가입하신 메일 계정으로 가입확인 메일을 보내드렸습니다. 가입확인하시고 로그인해 주세요.',
    ],

    'passwords' => [
        'title_reminder' => '비밀번호 바꾸기 신청',
        'desc_reminder' => '회원가입한 이메일로 신청한 후, 메일박스를 확인하세요.',
        'send_reminder' => '비밀번호 바꾸기 메일 발송',
        'title_reset' => '비밀번호 바꾸기',
        'desc_reset' => '회원가입한 이메일을 입력하고 새로운 비밀번호를 입력하세요.',
        'send_reset' => '비밀번호 바꾸기',
        'sent_reminder' => '비밀번호를 바꾸는 방법을 담은 이메일을 발송했습니다. 메일박스를 확인하세요.',
        'error_wrong_url' => 'URL이 정확하지 않습니다.',
        'success_reset' => '비밀번호를 바꾸었습니다. 새로운 비밀번호로 로그인하세요.'
    ],

];
