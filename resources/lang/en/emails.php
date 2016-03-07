<?php

return [

    'articles' => [
        'created' => sprintf('[%s] %s', config('project.name'), 'New article created.: :title'),
    ],

    'auth' => [
        'confirm' => sprintf('[%s] %s', config('project.name'), 'Confirm your registration.'),
    ],

    'comments' => [
        'created' => sprintf('[%s] %s', config('project.name'), 'New comment created.'),
    ],

    'passwords' => [
        'reset' => sprintf('[%s] %s', config('project.name'), 'Reset your password.'),
    ],

];
