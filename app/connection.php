<?php 
    include_once 'config/autoload.php';

    // Create connection database
    function database() {
        $dbHost = env("DB_HOST");
        $dbUser = env("DB_USER");
        $dbPass = env("DB_PASS");
        $dbName = env("DB_NAME");

        $connection = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
        
        if($connection->connect_error){
            die ("Gagal Koneksi: %s\n". $connection->error);
        }
        
        return $connection;
    }

    // close connection
    function closeDb($connection) {
        $connection->close();
    }
?>