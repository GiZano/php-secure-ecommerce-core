<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Commerce Exercises</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body>

        <div class="container mt-5">
            <h1 class="mx-auto w-25">E-Commerce Management</h1>
            <br><br>
            <form action="./index.php" method="POST" class="p-4 border rounded shadow-sm w-50 mx-auto">
            
                <div class="mb-3">
                    <label for="action" class="form-label">Operations</label>

                    <select class="form-select" name="action" required>
                        <option value="" selected disabled>Select an option...</option>

                        <option value="findUser">Find user by e-mail</option>
                        <option value="insertProduct">Insert new product</option>
                        <option value="updatePrice">Update product price</option>
                        <option value="deleteUser">Delete user</option>
                        <option value="findProduct">Find products by name</option>
                        <option value="listOrders">List user orders</option>
                        <option value="updateStocks">Update product stocks (Sell)</option>
                        <option value="userSpendings">Calculate user spendings</option>
                        <option value="orderDetail">View all order details</option>
                        <option value="newOrder">Add a new order</option>
                    </select>
                </div>

                <input class="btn btn-primary" type="submit">

            </form>

        </div> 

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>