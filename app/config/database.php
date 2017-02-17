<?php

return [
    'default'       => 'mysql',

    'connections'   => [

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => isset($_SERVER['DATABASE1_HOST']) ? $_SERVER['DATABASE1_HOST'] : 'localhost',
            'database'  => isset($_SERVER['DATABASE1_NAME']) ? $_SERVER['DATABASE1_NAME'] : 'SilexStarter',
            'username'  => isset($_SERVER['DATABASE1_USER']) ? $_SERVER['DATABASE1_USER'] : 'root',
            'password'  => isset($_SERVER['DATABASE1_PASS']) ? $_SERVER['DATABASE1_PASS'] : '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => APP_PATH.'storage/db/database.sqlite',
            'prefix'   => '',
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => 'localhost',
            'database' => 'database',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => '127.0.0.1',
            'database' => 'database',
            'username' => 'user',
            'password' => '',
            'prefix'   => '',
        ],

    ],
];