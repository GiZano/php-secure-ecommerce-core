<?php

    require_once __DIR__ . '/../model/data.php';

    require_once __DIR__ . '/../view/home_view.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])){
        $action = $_POST['action'];

        switch($action){
            case 'findUser':
                require_once __DIR__ . '/./find_user_by_email.php';
                 break;
            case 'insertProduct':
                require_once __DIR__ . '/./insert_product.php';
                break;
            case 'updatePrice':
                require_once __DIR__ . '/./update_price.php';
                break;
            case 'deleteUser':
                require_once __DIR__ . '/./delete_user.php';
                break;
            case 'findProduct':
                require_once __DIR__ . '/./find_product.php';
                break;
            case 'listOrders':
                require_once __DIR__ . '/./list_orders.php';
                break;
            case 'updateStocks':
                require_once __DIR__ . '/./update_stocks.php';
                break;
            case 'userSpendings':
                require_once __DIR__ . '/./user_spendings.php';
                break;
            case 'orderDetail':
                require_once __DIR__ . '/./order_detail.php';
                break;
            case 'newOrder':
                require_once __DIR__ . '/./new_order.php';
                break;
        }
    }

    

?>