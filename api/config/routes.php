<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['v1/card'],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['v1/orders'],
        'tokens' => [
            '{orderNumber}' => '<orderNumber:\\w+>',
        ],
        'extraPatterns' => [
            'GET pending' => 'pending',
            'GET completed' => 'completed',
            'POST pay-credit-card' => 'pay-credit-card',
            'POST pay-with-saved-credit-card' => 'pay-with-saved-credit-card',
            'POST pay-ewallet' => 'pay-ewallet',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['v1/enrollment'],
        'tokens' => [
            '{id}' => '<id:\\w+>',
            '{type}' => '<type:\\w+>'
        ],
        'extraPatterns' => [
            'GET status' => 'status',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/user',
        'tokens' => [
            '{id}' => '<id:\\w+>'
        ],
        'extraPatterns' => [
            'GET view' => 'view',
        ]
    ],
];