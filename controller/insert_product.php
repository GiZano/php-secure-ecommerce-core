<?php

    require_once __DIR__ . '/../model/data.php';

    // Input Form

    echo '<div align=center class="container w-25">
            <h2 class="mb-5">Insert New Product</h2>
            <form action="" method="POST" class="mb-5">
                <div class="d-flex flex-row justify-content-between">
                    <label for="name">Name: </label>
                    <input type="text" name="name" placeholder="Laptop Pro" required>
                </div>
                <br><br>
                <div class="d-flex flex-row justify-content-between">
                    <label for="description">Description: </label>
                    <input type="textarea" name="description" placeholder="Notebook potente per sviluppatori" required>
                </div>  
                <br><br>
                <div class="d-flex flex-row justify-content-between">
                    <label for="price">Price: </label>
                    <input type="number" name="price" step = "0.01" placeholder="1299.00" required>
                </div>
                <br><br>
                <div class="d-flex flex-row justify-content-between">
                    <label for="amount">Amount: </label>
                    <input type="number" name="amount" placeholder="20" required>
                </div>  
                <br><br>
                <input type="hidden" name="action" value="insertProduct">
                <input type="submit" class="btn btn-primary">
            </form>
        </div>';

    // Data Insertion on Submit
    
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['name'])){
        $insert = True;
        if(isset($_POST['name'])){
            $name = $_POST['name'];
        }else{
            echo '<p style="color:red">Insert name!</p>';
            $insert = False;
        }

        if(isset($_POST['description'])){
            $description = $_POST['description'];
        }else{
            echo '<p style="color:red">Insert description!</p>';
            $insert = False;
        }

        if(isset($_POST['price'])){
            $price = $_POST['price'];
        }else{
            echo '<p style="color:red">Insert price!</p>';
            $insert = False;
        }

        if(isset($_POST['amount'])){
            $amount = $_POST['amount'];
        }else{
            echo '<p style="color:red">Insert amount!</p>';
            $insert = False;
        }

        if($insert){
            try{
                $output = addNewProduct($name, $description, $price, $amount);
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