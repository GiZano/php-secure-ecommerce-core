<?php

    if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action'])){
        require_once __DIR__ . '/controller/sorter.php';
    }else{
        require_once __DIR__ . '/view/home_view.php';
    }

?>