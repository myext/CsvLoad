<?php

return [


    /*
    |--------------------------------------------------------------------------
    | File Path
    |--------------------------------------------------------------------------
    |
    | Here you may specify path (storage/public/zvg/csv)
    |
    */

    'file_path' => 'zvg/csv',

    /*
    |--------------------------------------------------------------------------
    | CSV fele's Delimiter
    |--------------------------------------------------------------------------
    |
    | Here you may specify delimiter thet you use in your csv file
    |
    */

    'delimeter' => ';',

    /*
    |--------------------------------------------------------------------------
    | Sanitize filters
    |--------------------------------------------------------------------------
    |
    | Here you may specify filters for sanitize your csv file before insert to DB
    |
    */

    'filter' => [FILTER_SANITIZE_STRING, FILTER_SANITIZE_EMAIL],

    /*
    |--------------------------------------------------------------------------
    | Records count
    |--------------------------------------------------------------------------
    |
    | Here you may specify records amount to insert into DB for one query.
    |
    |     insert into table
    |       (field1,field2,....,field...)
    |     values
    |        (val1.1, val1.2,....,val... ),
    |        (val2.1, val2.2,....,val... ),
    |         .....
    |         .....
    |        (val3000.1, val3000.2, val3000....) --- limit 3000
    */

    'limit' => 3000,

    /*
    |--------------------------------------------------------------------------
    | Insert mode
    |--------------------------------------------------------------------------
    |
    | If 'mode' => 'update' before insert db table will be truncated
    |
    */

    'mode' => 'update', // 'update' or 'add'


    /*
    |--------------------------------------------------------------------------
    | Save file
    |--------------------------------------------------------------------------
    |
    | If 'mode' => false csv file will be deleted after db inserting
    |
    */

    'save' => false

];
