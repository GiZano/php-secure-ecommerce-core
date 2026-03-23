<?php

    echo '<div align=center class="container w-25 mt-5">
          <h2 class="mb-5">Update Product Price</h2>
            <form action="" method="POST" class="mb-5">
                <div class="d-flex flex-row justify-content-between">
                    <label for="id">Product ID: </label>
                    <input type="number" name="id" placeholder="1" required>
                </div>  
                <br><br>
                <div class="d-flex flex-row justify-content-between">
                    <label for="price">Price: </label>
                    <input type="number" name="price" step = "0.01" placeholder="1299.00" required>
                </div>
                <br><br>
                <input type="hidden" name="action" value="updatePrice">
                <input type="submit" class="btn btn-primary">
            </form>
        </div>';

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])){
        $update = True;
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }else{
            echo '<p style="color:red">Insert id!</p>';
            $update = False;
        }

        if(isset($_POST['price'])){
            $price = $_POST['price'];
        }else{
            echo '<p style="color:red">Insert price!</p>';
            $update = False;
        }

        if($update){
            try{
                $output = updateProductPrice($id, $price);
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