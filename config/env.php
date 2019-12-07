<?php
    $variaeble = [
        'DB_NAME' => 'testFlip',
        'DB_USER' => 'kid1412d',
        'DB_PASS' => 'INTELcoreI321#',
        'DB_HOST' => 'localhost'
    ];

    foreach ($variaeble as $key => $value){
        putenv("$key=$value");
    }

?>