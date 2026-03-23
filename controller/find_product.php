<?php   

    require_once __DIR__ . '/../model/data.php';

    // Input Form

    echo '<div align=center class="container mt-5">
            <h2 class="mb-5"> Find Products By Name </h2>
            <form action="" method="POST" class="mb-5">
                <input type="hidden" name="action" value="findProduct">
                <label for="name">Name:</label>
                <input type="name"  name="name" required>
                <input class="btn btn-primary" type="submit">
            </form>
        </div>';

    // Data Retrieving

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['name'])){
        $search = True;
        if(isset($_POST['name'])){
            $name = $_POST['name'];
        }else{
            echo '<p style="color:red">Insert name!</p>';
            $search = False;
        }

        if($search){
            $output = findProductsByName($name);
            if($output['code'] == 200){
                echo '<table class="table w-50 mx-auto">';
                echo '<thead>';
                echo '<th>ID</th>';
                echo '<th>Name</th>';
                echo '<th>Description</th>';
                echo '<th>Price</th>';
                echo '<th>Amount</th>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($output['data'] as $row) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['description'] . '</td>';
                    echo '<td>' . $row['price'] . '</td>';
                    echo '<td>' . $row['amount'] . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            }else{
                echo '<p class="w-25 mx-auto mt-5" style="color:red">' . $output['message'] . '</p>';
            }         
        }else{
                echo '<p class="w-25 mx-auto mt-5" style="color:red">' . $output['message'] . '</p>';
        }
    }
?>