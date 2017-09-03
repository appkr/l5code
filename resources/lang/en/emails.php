<?php

return [

    'articles' => [
        'created' => sprintf('[%s] %s', config('app.name'), 'New article created.: :title'),
    ],

    'auth' => [
        'confirm' => sprintf('[%s] %s', config('app.name'), 'Confirm your registration.'),
    ],

    'comments' => [
        'created' => sprintf('[%s] %s', config('app.name'), 'New comment created.'),
    ],

    'passwords' => [
        'reset' => sprintf('[%s] %s', config('app.name'), 'Reset your password.'),
    ],

];
