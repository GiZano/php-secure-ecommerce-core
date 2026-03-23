<?php

    // Input Form

    echo '<div align=center class="container mt-5">
            <h2 class="mb-5">Retrieve User Total Spendings</h2>
            <form action="" method="POST" class="mx-auto w-25">
                <label for="id">User ID: </label>
                <input type="number" name="id" required>
                <input type="hidden" name="action" value="userSpendings">
                <input class="btn btn-primary" type="submit">
            </form>
        </div>';

    // Retrieve data

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])){
        $retrieve = True;
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }else{
            echo '<p style="color:red">Insert ID!</p>';
            $retrieve = False;
        }

        if($retrieve){
            try{
                $output = calculateUserSpending($id);
                if($output['code'] == 200){
                    $total = $output['data'];
                    echo '<p class="w-25 mx-auto mt-5" style="color:green"> User with id "' . $id . '" spent a total of ' . $total .'€</p>';
                }else{
                    echo '<p class="w-25 mx-auto mt-5" style="color:red">' . $output['message'] . '</p>';
                }    
            }catch(InvalidArgumentException $e){
                echo '<p class="w-25 mx-auto mt-5" style="color:red">' . $e->getMessage() . '</p>';
            }     
        }else{
                echo '<p class="w-25 mx-auto mt-5" style="color:red">' . $output['message'] . '</p>';
        }
    }


?>