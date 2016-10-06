<?php

$_fn=realpath(__DIR__."/../../db")."/main.db";

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite:'.$_fn,
        ],
    ],
];
