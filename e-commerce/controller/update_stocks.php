<?php

    // Input Form

    echo '<div align=center class="container mt-5">
            <h2 class="mb-5">Sell Product Stocks</h2>
            <form action="" method="POST" class="w-25 mb-5">
                <div class="d-flex flex-row justify-content-between">
                    <label for="id">Product ID: </label>
                    <input type="number" name="id" required>
                </div>
                <br><br>
                <div class="d-flex flex-row justify-content-between">
                    <label for="id">Sold Amount: </label>
                    <input type="number" name="amount" step="1" required>
                </div>
                <input type="hidden" name="action" value="updateStocks">
                <br><br>
                <input class="btn btn-primary" type="submit">
            </form>
        </div>';


    // Data update

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])){
        $update = True;
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }else{
            echo '<p style="color:red">Insert id!</p>';
            $update = False;
        }

        if(isset($_POST['amount'])){
            $amount = $_POST['amount'];
        }else{
            echo '<p style="color:red">Insert amount!</p>';
            $update = False;
        }

        if($update){
            try{
                $output = updateProductStocks($id, $amount);
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