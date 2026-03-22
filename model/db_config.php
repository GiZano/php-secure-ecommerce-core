<?php

    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', '5h_26_negozio_online');
    define('DB_PORT', 3306);

    function getConnection(){
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

        // if it fails, interrupt
        if( $conn -> connect_error) {
            die("Connection error" . $conn -> connect_error);
        }
        
        return $conn;
    }

?>