<?php
    $variaeble = [
        'DB_NAME' => '',
        'DB_USER' => '',
        'DB_PASS' => '',
        'DB_HOST' => 'localhost',
        'BASE_URL_DISBURSEMENT' => 'https://nextar.flip.id',
        'HEADER_AUTH_FLIP' => 'HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41',
        'ENDPOINT_DISBURSE' => '/disburse',
    ];

    foreach ($variaeble as $key => $value){
        putenv("$key=$value");
    }

?>