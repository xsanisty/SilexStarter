<?php 

return [
    "default" => "mysql",
    "connections" => [
        "mysql" => [
            "driver" => "mysql",
            "host" => "localhost",
            "database" => "silexstarter",
            "username" => "root",
            "password" => "",
            "charset" => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix" => ""
        ],
        "sqlite" => [
            "driver" => "sqlite",
            "database" => "/Volumes/htdocs/www/github/SilexStarter/app/storage/db/database.sqlite",
            "prefix" => ""
        ],
        "pgsql" => [
            "driver" => "pgsql",
            "host" => "localhost",
            "database" => "database",
            "username" => "root",
            "password" => "",
            "charset" => "utf8",
            "prefix" => "",
            "schema" => "public"
        ],
        "sqlsrv" => [
            "driver" => "sqlsrv",
            "host" => "127.0.0.1",
            "database" => "database",
            "username" => "user",
            "password" => "",
            "prefix" => ""
        ]
    ]
];
