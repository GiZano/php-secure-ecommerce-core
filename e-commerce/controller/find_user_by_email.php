<?php
    require_once __DIR__ . '/../model/data.php';

    // Input Form

    echo '<div align=center class="container mt-5">
            <h2 class="mb-5">Find User by E-mail</h2>
            <form action="" method="POST" class="mb-5">
                <input type="hidden" name="action" value="findUser">
                <label for="email">E-mail:</label>
                <input type="email"  name="email" required>
                <input class="btn btn-primary" type="submit">
            </form>
        </div>';
    
    // Data Retrieve on Submit

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['email'])){
        $data = getUserByEmail($_POST['email']);
        $name = $data['name'];
        if($name == 'Null'){
            echo '<p class="w-25 mx-auto mt-5" style="color:red">No user with email ' . $_POST['email'] . ' available!</p>';
        }else{
            $surname = $data['surname'];
        echo '
            <div align=center class="container mt-5">
                <h3>Retrieved data: </h3>
                <table class="table mx-auto w-25 mt-5">
                    <thead>
                        <th>Name</th>
                        <th>Surname</th>
                    </thead>
                    <tbody>
                        <td>' . $name . '</td>
                        <td>' . $surname . '</td>
                    </tbody>
                </table>
            </div>
        
        ';
        }

        
    }

?>