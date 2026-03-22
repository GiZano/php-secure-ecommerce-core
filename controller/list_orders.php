<?php

    // Input form

    echo '<div align=center class="container mt-5">
            <h2 class="mb-5">List User Orders</h2>
            <form action="" method="POST" class="mb-5"">
                <label for="id">User ID: </label>
                <input type="number" name="id" required>
                <input type="hidden" name="action" value="listOrders">
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
                $output = getUserOrders($id);
                if($output['code'] == 200){
                    echo '<table class="table w-50 mx-auto">';
                    echo '<thead>';
                    echo '<th>ID</th>';
                    echo '<th>Date</th>';
                    echo '<th>Total</th>';
                    echo '<th>Status</th>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ($output['data'] as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['id'] . '</td>';
                        echo '<td>' . $row['date'] . '</td>';
                        echo '<td>' . $row['total'] . '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
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