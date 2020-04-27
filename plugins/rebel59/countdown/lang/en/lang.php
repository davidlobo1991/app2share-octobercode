<?php

return [
    'plugin' => [
        'name' => 'Countdown',
        'description' => 'A simple yet robust countdown for anything you can think of!',
    ],
    'components' => [
        'countdown' => [
            'name' => 'Countdown Component',
            'description' => 'Displays a countdown to a given date.',
            'properties' => [
                'date' => [
                    'title' => 'Date',
                    'description' => 'The date the countdown uses.',
                    'validationMessage' => 'Invalid datetime format. Format should be YYYY/MM/DD HH:MM:SS'
                ],
                'jquery' => [
                    'title' => 'Load jQuery',
                    'description' => 'Load the jQuery library'
                ],
                'countdown' => [
                    'title' => 'Load jQuery.Countdown',
                    'description' => 'Load the jQuery.Countdown library'
                ],
                'init' => [
                    'title' => 'Load standard init file',
                    'description' => 'Load the standard initialisation file that starts the countdown.'
                ],
                'css' => [
                    'title' => 'Load standard css file',
                    'description' => 'Load the standard css file that starts styles countdown.'
                ]

            ]
        ]
    ]
];
