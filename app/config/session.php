<?php

return [
    'storage'    => 'database',

    'options'  => [
        'database'  => [
            'db_table'      => 'session',
            'db_id_col'     => 'session_id',
            'db_data_col'   => 'session_value',
            'db_time_col'   => 'session_time',
        ],

        'file'      => [

        ],
    ],
];
