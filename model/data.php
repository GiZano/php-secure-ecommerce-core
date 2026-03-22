<?php

    require_once __DIR__ . '/db_config.php';

    // Easy 1

    function getUserByEmail($email){
        $conn = getConnection();

        $sql = "SELECT nome, cognome FROM utenti WHERE email = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("s", $email);
        $stmt -> execute();
        $result = $stmt -> get_result();

        $row = $result -> fetch_assoc();

        if(isset($row['nome'])){
            $data['name'] = $row['nome'];
            $data['surname'] = $row['cognome'];
        }else{
            $data['name'] = 'Null';
        }

        return $data;
    }

    // Easy 2
    function addNewProduct($name, $description, $price, $quantity){
        if($price < 1){
            throw new InvalidArgumentException("Price must be 1 or higher!");
        }
        if($quantity < 0){
            throw new InvalidArgumentException("Amount must be a positive number!");
        }

        $conn = getConnection();

        $sql = "INSERT INTO prodotti (nome, descrizione, prezzo, quantita_magazzino) VALUES (?, ?, ?, ?)";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("ssdi", $name, $description, $price, $quantity);
        $stmt -> execute();

        if($stmt -> affected_rows > 0 && $stmt->error != " " ){
            return ["message" => 'Product ' . $name . ' added succesfully',
                    "code" => 200
                ];
        }else{
            return ["message" => 'Product insertion failed! ',
                    "code" => 400
                ];
        }
    }

    // Easy 3
    function updateProductPrice($id, $new_price){
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        if($new_price < 1){
            throw new InvalidArgumentException("Price must be 1 or higher!");
        }

        $conn = getConnection();

        $sql = "UPDATE prodotti SET prezzo = ? WHERE id = ?;";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("dd", $new_price, $id);
        $stmt -> execute();

        if($stmt->error != " " ){
            return ["message" => 'Product price updated succesfully',
                    "code" => 200
                ];
        }else{
            return ["message" => 'Product price update failed! ' . $stmt->error,
                    "code" => 400
                ];
        }
    }

    // Easy 4
    function deleteUser($id){
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        $conn = getConnection();

        $sql = "DELETE FROM utenti WHERE id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        if($stmt->error != " " ){
            return ["message" => 'User deleted succesfully',
                    "code" => 200
                ];
        }else{
            return ["message" => 'User deletion failed! ' . $stmt->error,
                    "code" => 400
                ];
        }
    }

    // Medium 1
    function findProductsByName($name){
        $new_name = "%" . $name . "%";

        $conn = getConnection();

        $sql = "SELECT * FROM prodotti WHERE nome LIKE ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("s", $new_name);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows > 0){
            $rows = [];
            while($row = $result -> fetch_assoc()){
                $data = [];
                $data['id']          = $row['id'];
                $data['name']        = $row['nome'];
                $data['description'] = $row['descrizione'];
                $data['price']       = $row['prezzo'];
                $data['amount']       = $row['quantita_magazzino'];
                $rows[] = $data;
            }
            return [
                'code' => 200,
                'data' => $rows
            ];
        }else{
            return [
                'code' => 300,
                'message' => 'No available names containing ' . $name . '!'
            ];
        }
    }

    // Medium 2
    function getUserOrders($id){
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        $conn = getConnection();

        $sql = "SELECT * FROM ordini WHERE id_utente = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows > 0){
            $rows = [];
            while($row = $result -> fetch_assoc()){
                $data = [];
                $data['id']     = $row['id'];
                $data['date']   = $row['data_ordine'];
                $data['total']  = $row['totale'];
                $data['status'] = $row['stato_ordine'];
                $rows[] = $data;
            }
            return [
                "code" => 200,
                "data" => $rows
            ];
        }else{
            return [
                'code' => 300,
                'message' => 'No available order from user with id "' . $id . '"!'
            ];
        }
    }

    // Medium 3
    function updateProductStocks($id, $sold){
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }
        
        if($sold < 1){
            throw new InvalidArgumentException("Sold stocks must be 1 or higher!");
        }

        $conn = getConnection();

        $sql = "UPDATE prodotti SET quantita_magazzino = quantita_magazzino - ? WHERE id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("dd", $id, $sold);
        $stmt -> execute();

        if($stmt -> affected_rows > 0){
            return [
                "code" => 200,
                "message" => "Amount updated succesfully"
            ];
        }else{
            return [
                "code" => 400,
                "message" => "Amount update failed! " . $stmt -> error
            ];
        }
    }

    // Advanced 1
    function calculateUserSpending($id){
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        $conn = getConnection();

        $sql = "SELECT SUM(totale) as total FROM ordini GROUP BY id HAVING id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows > 0){
            $row = $result -> fetch_assoc();
            return [
                "code" => 200,
                "data" => $row['total']
            ];
        }else{
            return [
                "code" => 300,
                'message' => 'No available order from user with id "' . $id . '"!'
            ];
        }
    }

    // Advanced 2
    function getAllOrderDetails($id){
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        $conn = getConnection();

        $sql = "SELECT * FROM dettagli_ordine JOIN ordini ON dettagli_ordine.id_ordine = ordini.id JOIN prodotti ON dettagli_ordine.id_prodotto = prodotti.id WHERE ordini.id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows > 0){
            $rows = [];
            while($row = $result -> fetch_assoc()){
                $data = [];
                $data['name']   = $row['nome'];
                $data['amount'] = $row['quantita'];
                $data['price']  = $row['prezzo_unitario'];
                $rows[] = $data;
            }
            return [
                "code" => 200,
                "data" => $rows
            ];
        }else{  
            return [
                "code" => 404,
                "message" => 'Order with ID "' . $id . '" not available!'
            ];
        }
    }

    // Advanced 3
    function getProductPrice($id){
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        $conn = getConnection();

        $sql = "SELECT prezzo FROM prodotti WHERE id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows != 1){
            throw new InvalidArgumentException('Product with ID "' . $id . '" not available!');
        }else{
            $data = $result -> fetch_assoc();
            return $data['prezzo'];
        }

    }

    function createNewOrder($user_id, $product_ids, $amounts){
        if($user_id < 1){
            throw new InvalidArgumentException("IDs must be 1 or higher!");
        } 

        $product_ids = "" . $product_ids;

        $products_id_list = explode(',', $product_ids);

        foreach($products_id_list as $id){
            if($id < 1){
                throw new InvalidArgumentException("IDs must be 1 or higher!");
            }
        }

        $amounts = "" . $amounts;
        
        $amounts_list = explode(',', $amounts);

        foreach($amounts_list as $amount){
            if($amount < 1){
                throw new InvalidArgumentException("Amounts must be 1 or higher!");
            }
        }

        if(count($products_id_list) != count($amounts_list)){
            throw new InvalidArgumentException("Product IDs and Amounts must be of the same length!");
        }

        $conn = getConnection();

        // Calculate total

        $prices = [];

        foreach($products_id_list as $product_id){
            $prices[] = getProductPrice($product_id);
        }

        $total = 0;

        for($i = 0; $i < count($prices); $i += 1){
            try{
                $total += intval($prices[$i]) * intval($amounts[$i]);
            }catch(Exception $e){
                throw new InvalidArgumentException("Price and Amount values must be integers!");            }
        }

        $conn -> begin_transaction();
        try{
            $sql_order = "INSERT INTO ordini (id_utente, totale) VALUES (?, ?)";

            $stmt_order = $conn -> prepare($sql_order);
            $stmt_order -> bind_param("dd", $user_id, $total);
            $stmt_order -> execute();

            if($stmt_order -> affected_rows > 0){
                $order_id = $stmt_order -> insert_id;

                $sql_details = "INSERT INTO dettagli_ordine (id_ordine, id_prodotto, quantita, prezzo_unitario) VALUES(?, ?, ?, ?)";

                $stmt_details = $conn -> prepare($sql_details);

                for($i = 0; $i < count($prices); $i += 1){
                    $stmt_details -> bind_param("dddd", $order_id, $products_id_list[$i], $amounts_list[$i], $prices[$i]);
                    $stmt_details -> execute();
                    if($stmt_details-> error != ""){
                        $conn -> rollback();
                        return [
                            "code" => 500,
                            "message" => "Order details insertion failed! " . $stmt_details -> error
                        ];
                    }
                }           
                $conn -> commit();
                return [
                        "code" => 200,
                        "message" => "Order added correctly!"
                    ];
            }else{
                $conn -> rollback();
                return [
                    "code" => 300,
                    "message" => "Order insertion failed! " . $stmt_order -> error
                ];
            }
        }catch(Exception $e){
            $conn -> rollback();
            return [
                "code" => 500,
                "message" => "Order details insertion failed! " . $stmt_details -> error
            ];
        }
    }
?>