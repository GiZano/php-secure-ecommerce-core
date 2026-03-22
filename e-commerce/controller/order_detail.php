<?php

    // Form Input

    echo '<div align=center class="container mt-5">
            <h2 class="mb-5">List Order Details</h2>
            <form action="" method="POST" class="mb-5">
                <label for="id">Order ID: </label>
                <input type="number" name="id" required>
                <input type="hidden" name="action" value="orderDetail">
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
                $output = getAllOrderDetails($id);
                if($output['code'] == 200){
                    echo '<div align=center>';
                    echo '<h2 class="mt-5 mb-5" >Detail of Order "' . $id . '":</h2>';
                    echo '<table class="table w-50 mx-auto">';
                    echo '<thead>';
                    echo '<th>Name</th>';
                    echo '<th>Amount</th>';
                    echo '<th>Unit Price</th>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ($output['data'] as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['amount'] . '</td>';
                        echo '<td>' . $row['price'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
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