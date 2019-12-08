<?php
    $variaeble = [
        'DB_NAME' => 'testFlip',
        'DB_USER' => '',
        'DB_PASS' => '#',
        'DB_HOST' => 'localhost',
        'BASE_URL_DISBURSEMENT' => 'https://nextar.flip.id',
        'HEADER_AUTH_FLIP' => '',
        'ENDPOINT_DISBURSE' => '/disburse',
    ];

    foreach ($variaeble as $key => $value){
        putenv("$key=$value");
    }

?>