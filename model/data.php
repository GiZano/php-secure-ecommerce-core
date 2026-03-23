<?php

    require_once __DIR__ . '/db_config.php';

    /**
     * Get user data from DB by e-mail.
     *
     * Retrieve user data (name and surname) of an user based on given email.
     * If no user is found, return data with name set as "Null".
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  404: Not Found,
     *  501: DB Connection error
     *
     * @param string $email User email
     *
     * @return array {code: int, message?: string, data?: mixed}
    */

    function getUserByEmail(string $email): array{

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $sql = "SELECT name, surname FROM users WHERE email = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("s", $email);
        $stmt -> execute();
        $result = $stmt -> get_result();

        $row = $result -> fetch_assoc();

        if(isset($row['name'])){
            $data['name'] = $row['name'];
            $data['surname'] = $row['surname'];
            $code = 200;
        }else{
            $data['name'] = 'Null';
            $code = 404;
        }

        return [
            "code" => $code,
            "data" => $data
        ];
    }

    /**
     * Insert new product in DB.
     *
     * Add new product to DB table 'products' using given values.
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  500: Fail,
     *  501: DB Connection error
     *
     * @param string $name New product name
     * @param string $description New product description
     * @param float  $price New product price
     * @param int    $amount New product available stocks
     * 
     * @throws InvalidArgumentException price lower than 1 | negative amount
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function addNewProduct(string $name, string $description, float $price, int $amount): array{
        if($price < 1){
            throw new InvalidArgumentException("Price must be 1 or higher!");
        }
        if($amount < 0){
            throw new InvalidArgumentException("Amount must be a positive number!");
        }

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $conn -> begin_transaction();

        $sql = "INSERT INTO products (name, description, price, available_stocks) VALUES (?, ?, ?, ?)";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("ssdi", $name, $description, $price, $amount);
        $stmt -> execute();

        if($stmt -> affected_rows > 0 && $stmt->error != " " ){
            $conn -> commit();
            return ["message" => 'Product ' . $name . ' added successfully',
                    "code" => 200
                ];
        }else{
            $conn -> rollback();
            return ["message" => 'Product insertion failed! ',
                    "code" => 500
                ];
        }
    }

    /**
     * Update product price in DB.
     *
     * Update product price of given product (by id).
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  404: Not Found,
     *  501: DB connection error
     *
     * @param int $id Product id
     * @param string $new_price Price to set
     * 
     * @throws InvalidArgumentException id or price lower than 1
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function updateProductPrice(int $id, float $new_price): array{
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        if($new_price < 1){
            throw new InvalidArgumentException("Price must be 1 or higher!");
        }

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $conn -> begin_transaction();

        $sql = "UPDATE products SET price = ? WHERE id = ?;";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("dd", $new_price, $id);
        $stmt -> execute();

        if($stmt->error != " " ){
            $conn -> commit();
            return ["message" => 'Product price updated successfully',
                    "code" => 200
                ];
        }else{
            $conn -> rollback();
            return ["message" => 'Product price update failed! ' . $stmt->error,
                    "code" => 400
                ];
        }
    }

    /**
     * Delete user in DB.
     *
     * Delete user based on DB.
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  404: Not Found,
     *  501: DB connection error
     *
     * @param int $id User id
     * 
     * @throws InvalidArgumentException id lower than 1
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function deleteUser(int $id): array{
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $sql = "DELETE FROM users WHERE id = ?";

        $conn -> begin_transaction();

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        if($stmt->error == "" ){
            $conn -> commit();
            return ["message" => 'User deleted successfully',
                    "code" => 200
                ];
        }else{
            $conn -> rollback();
            return ["message" => 'User deletion failed! ' . $stmt->error,
                    "code" => 404
                ];
        }
    }

    /**
     * Find products by name in DB.
     *
     * Find products containing given string in their name.
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  404: Not Found,
     *  501: DB connection error
     *
     * @param string $name String to check in products names.
     * 
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function findProductsByName(string $name): array{
        $new_name = '%' . $name . '%';

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $sql = "SELECT * FROM products WHERE name LIKE ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("s", $new_name);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows > 0){
            $rows = [];
            while($row = $result -> fetch_assoc()){
                $data = [];
                $data['id']          = $row['id'];
                $data['name']        = $row['name'];
                $data['description'] = $row['description'];
                $data['price']       = $row['price'];
                $data['amount']      = $row['available_stocks'];
                $rows[] = $data;
            }
            return [
                'code' => 200,
                'data' => $rows
            ];
        }else{
            return [
                'code' => 404,
                'message' => 'No available names containing ' . $name . '!'
            ];
        }
    }

    /**
     * Retrieve all user orders.
     *
     * Get all orders linked to user (by user id).
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  404: Not Found,
     *  501: DB connection error
     *
     * @param int $id User id
     * 
     * @throws InvalidArgumentException id lower than 1
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function getUserOrders(int $id): array{
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $sql = "SELECT * FROM orders WHERE user_id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows > 0){
            $rows = [];
            while($row = $result -> fetch_assoc()){
                $data = [];
                $data['id']     = $row['id'];
                $data['date']   = $row['order_date'];
                $data['total']  = $row['total'];
                $data['status'] = $row['order_status'];
                $rows[] = $data;
            }
            return [
                "code" => 200,
                "data" => $rows
            ];
        }else{
            return [
                'code' => 404,
                'message' => 'No available order from user with id "' . $id . '"!'
            ];
        }
    }

    /**
     * Retrieve product stocks in DB.
     *
     * Retrieve available stocks of given product (by id).
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  404: Not Found,
     *  501: DB connection error
     *
     * @param int $id Product id
     * 
     * @throws InvalidArgumentException id lower than 1
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function getProductStocks(int $id): array{
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }
        
        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $sql = "SELECT available_stocks FROM products WHERE id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows > 0){
            $row = $result -> fetch_assoc();
            return [
                "code" => 200,
                "data" => $row['available_stocks']
            ];
        }else{
            return [
                "code" => 404,
                "message" => 'Product with id "' . $id . '" not available!'
            ];
        }
    }

    /**
     * Update product stocks in DB.
     *
     * Retrieve available stocks using getProductStocks and, if enough are available,
     * update stocks by removing sold stocks to the available amount.
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  400: Not Found,
     *  500: Update fail,
     *  501: DB Connection error
     *
     * @param int $id Product id
     * @param int $sold Sold stocks
     * 
     * @throws InvalidArgumentException id or sold lower than 1
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function updateProductStocks(int $id, int $sold): array{
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }
        
        if($sold < 1){
            throw new InvalidArgumentException("Sold stocks must be 1 or higher!");
        }

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $stocks_data = getProductStocks($id);

        if($stocks_data['code'] == 200 ){
            $stocks = $stocks_data['data'];
            if($sold > $stocks){
                return [
                    "code" => 400,
                    "message" => "Can't sell " . $sold . " items! (Max $stocks)"
                ];
            }
        }else{
            return [
                "code" => 404,
                "message" => $stocks_data['message']
            ];
        }
        
        $conn -> begin_transaction();

        $sql = "UPDATE products SET available_stocks = available_stocks - ? WHERE id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("dd", $sold, $id);
        $stmt -> execute();

        if($stmt -> affected_rows > 0){
            $conn -> commit();
            return [
                "code" => 200,
                "message" => "Amount updated successfully"
            ];
        }else{
            $conn -> rollback();
            return [
                "code" => 500,
                "message" => "Amount update failed! " . $stmt -> error
            ];
        }
    }

    /**
     * Calculate user total spendings based on his orders.
     *
     * Retrieve user orders and calculate the total using the SUM SQL function.
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  404: Not Found,
     *  501: DB connection error
     *
     * @param int $id User id
     * 
     * @throws InvalidArgumentException id lower than 1
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function calculateUserSpending(int $id): array{
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $sql = "SELECT SUM(total) as total FROM orders GROUP BY id HAVING id = ?";

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
                "code" => 404,
                'message' => 'No available order from user with id "' . $id . '"!'
            ];
        }
    }

    /**
     * Retrieve all details of given order.
     *
     * Retrieve product info of given order (by order id).
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  400: Not Found,
     *  501: DB connection error
     *
     * @param int $id Order id
     * 
     * @throws InvalidArgumentException id lower than 1
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function getAllOrderDetails(int $id): array{
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $sql = "SELECT * FROM order_details JOIN orders ON order_details.order_id = orders.id JOIN products ON order_details.product_id = products.id WHERE orders.id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows > 0){
            $rows = [];
            while($row = $result -> fetch_assoc()){
                $data = [];
                $data['name']   = $row['name'];
                $data['amount'] = $row['amount'];
                $data['price']  = $row['unit_price'];
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

    /**
     * Retrieve price of given product
     *
     * Retrieve price of given product by id
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  400: Not Found,
     *  501: DB connection error
     *
     * @param int $id Product id
     * 
     * @throws InvalidArgumentException id lower than 1 | product not available
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function getProductPrice(int $id): array{
        if($id < 1){
            throw new InvalidArgumentException("ID must be 1 or higher!");
        }

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        $sql = "SELECT price FROM products WHERE id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("d", $id);
        $stmt -> execute();

        $result = $stmt -> get_result();

        if($result -> num_rows != 1){
            throw new InvalidArgumentException('Product with ID "' . $id . '" not available!');
        }else{
            $data = $result -> fetch_assoc();
            return [
                "code" => 200,
                "data" => $data['price']
            ];
        }

    }

    /**
     * Create new order
     *
     * Create new order using linked products and bought amounts.
     * Get ids and amounts in a csv format, using ',' as a separator.
     * Explode the data and check the length and limits to match the data.
     * Depending on the outcome, return appropriate code:
     *  200: Success,
     *  300: Insertion fail,
     *  500: Insertion fail,
     *  501: DB connection error
     *
     * @param int $user_id User id
     * @param string $product_ids Products id (separated by ',')
     * @param string $amounts Bought amounts (separated by ',')
     * 
     * @throws InvalidArgumentException ids or amounts lower than 1 | product ids and amounts don't match | product not available | product id or amount not integer
     *
     * @return array {code: int, message?: string, data?: mixed}
    */
    function createNewOrder(int $user_id, string $product_ids, string $amounts): array{
        if($user_id < 1){
            throw new InvalidArgumentException("IDs must be 1 or higher!");
        } 

        $products_id_list = explode(',', $product_ids);

        foreach($products_id_list as $id){
            if($id < 1){
                throw new InvalidArgumentException("IDs must be 1 or higher!");
            }
        }
        
        $amounts_list = explode(',', $amounts);

        foreach($amounts_list as $amount){
            if($amount < 1){
                throw new InvalidArgumentException("Amounts must be 1 or higher!");
            }
        }

        if(count($products_id_list) != count($amounts_list)){
            throw new InvalidArgumentException("Product IDs and Amounts must be of the same length!");
        }

        try{
            $conn = getConnection();
        }catch(Exception $e){
            return [
                "code" => 501,
                "message" => 'Database not available!'
            ];
        }

        // Calculate total

        $prices = [];

        foreach($products_id_list as $product_id){
            $found_price_data = getProductPrice($product_id);
            if($found_price_data['code'] == 200){
                $prices[] = $found_price_data['data'];
            }
        }

        $total = 0;

        for($i = 0; $i < count($prices); $i += 1){
            try{
                $total += intval($prices[$i]) * intval($amounts_list[$i]);
            }catch(Exception $e){
                throw new InvalidArgumentException("Price and Amount values must be integers!");            
            }
        }

        $conn -> begin_transaction();
        try{
            $sql_order = "INSERT INTO orders (user_id, total) VALUES (?, ?)";

            $stmt_order = $conn -> prepare($sql_order);
            $stmt_order -> bind_param("dd", $user_id, $total);
            $stmt_order -> execute();

            if($stmt_order -> affected_rows > 0){
                $order_id = $stmt_order -> insert_id;

                $sql_details = "INSERT INTO order_details (order_id, product_id, amount, unit_price) VALUES(?, ?, ?, ?)";

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