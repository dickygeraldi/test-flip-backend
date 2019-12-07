<?php
    include './config/env.php';

    function env($key, $default=null){
        $value = getenv($key);

        if($value === false){
            return $default;
        }

        return $value;
    }

?>