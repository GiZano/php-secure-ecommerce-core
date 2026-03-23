<?php
    require_once __DIR__ . '/../model/data.php';
    
    // Data Retrieve on Submit

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['email'])){
        $data = getUserByEmail($_POST['email']);
        if($data['code'] == 200){
            $name = $data['data']['name'];
            $surname = $data['data']['surname'];
        }   
    }

    require_once __DIR__ . '/../view/find_user_by_email_view.php';

?>