<?php

    require_once __DIR__ . '/../model/data.php';

    // Input Form

    echo '<div align=center class="container mt-5">
            <h2 class="mb-5">Create New Order</h2>
            <form action="" method="POST" class="w-25">
                <div class="d-flex flex-row justify-content-between">
                    <label for="user_id">User ID: </label>
                    <input type="number" name="user_id" step="1" placeholder="1" required>
                </div>
                <br><br>
                <div class="d-flex flex-row justify-content-between">
                    <label for="products_id">Products ID: </label>
                    <input type="text" name="products_id" placeholder="(1,2,3,...,n" required>
                </div>
                <br><br>
                <div class="d-flex flex-row justify-content-between">
                    <label for="products_id">Amounts: </label>
                    <input type="text" name="amounts" placeholder="(1,2,3,...,n" required>
                </div>
                <input type="hidden" name="action" value="newOrder">
                <br><br>
                <input class="btn btn-primary" type="submit">
            </form>
        </div>';

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['user_id'])){
        $insert = True;
        if(isset($_POST['user_id'])){
            $user_id = $_POST['user_id'];
        }else{
            echo '<p style="color:red">Insert user ID!</p>';
            $insert = False;
        }

        if(isset($_POST['products_id'])){
            $products_id = $_POST['products_id'];
        }else{
            echo '<p style="color:red">Insert product IDs!</p>';
            $insert = False;
        }

        if(isset($_POST['amounts'])){
            $amounts = $_POST['amounts'];
        }else{
            echo '<p style="color:red">Insert amounts!</p>';
            $insert = False;
        }

        if($insert){
            try{
                $output = createNewOrder($user_id, $products_id, $amounts);
                if($output['code'] == 200){
                    echo '<p class="w-25 mx-auto mt-5" style="color:green">' . $output['message'] . '</p>';
                }else{
                    echo '<p class="w-25 mx-auto mt-5" style="color:red">' . $output['message'] . '</p>';
                }
            }catch(InvalidArgumentException $e){
                echo '<p class="w-25 mx-auto mt-5" style="color:red">' . $e->getMessage() . '</p>';
            }
            
        }
    }

?>