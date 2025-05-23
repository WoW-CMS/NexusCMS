<?php

return [
    'Trinity' => [
        'account_table' => 'account',
        'fields' => [
            'username' => 'username',
            'password' => 'sha_pass_hash',
            'email' => 'email',
            'joindate' => 'joindate',
            'expansion' => 'expansion',
        ],
    ],
    'AzerothCore' => [
        'account_table' => 'account',
        'fields' => [
            'username' => 'username',
            'password' => 'sha_pass_hash',
            'email' => 'email',
            'joindate' => 'joindate',
            'expansion' => 'expansion',
        ],
    ],
    'Mangos' => [
        'account_table' => 'accounts',
        'fields' => [
            'username' => 'login',
            'password' => 'password',
            'email' => 'email',
            'joindate' => 'joindate',
        ],
    ],
];