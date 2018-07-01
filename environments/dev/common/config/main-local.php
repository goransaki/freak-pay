<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;unix_socket=/tmp/mysql.sock;port=3307;dbname=freak_pay',
            'username' => 'xyz',
            'password' => 'HCfW70FTFXhtu2zecqwS#9UUgH1B6NxHGAAH1DRm',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
