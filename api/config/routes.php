<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 1/22/2018
 * Time: 3:14 PM
 */

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/card',
            'post/<id:\d+>' => 'post/view',
            'order/<order_number:\w+>/pay/cc' => 'api/payCC'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/user',
        'extraPatterns' => [
            'POST sync' => 'sync',
            'POST token' => 'token',
            'POST revoke' => 'revoke',
            'POST update-login-time' => 'update-login-time',
            'GET auth' => 'auth',
            'OPTIONS <action>' => 'options',
            'POST social-login' => 'social-login'
        ]
    ],
];