<?php

    // Input Form

    echo '
        <div align=center class="container mt-5">
        <h2 class="mb-5"> Delete User </h2>
            <form action="" method="POST" >
                <label for="id">User ID: </label>
                <input type="number" name="id" required>
                <input type="hidden" name="action" value="deleteUser">
                <input class="btn btn-primary" type="submit">
            </form>
        </div>';

    // Deletion Logic

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])){
        $delete = True;
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }else{
            echo '<p style="color:red">Insert ID!</p>';
            $insert = False;
        }

        if($delete){
            try{
                $output = deleteUser($id);
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