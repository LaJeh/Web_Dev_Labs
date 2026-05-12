<?php
    $conn = mysqli_connect("localhost", "root", "", "pizza");
    if (!$conn) {
        die("no connection");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["add_pizza"])) {
            $name = mysqli_real_escape_string($conn, $_POST["name"]);
            $price = $_POST["price"];
            mysqli_query($conn, "INSERT INTO pizzas (name, price) VALUES ('$name', '$price')");
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }

        if (isset($_POST["update_pizza"])) {
            $id = (int) $_POST["item_id"];
            $newPrice = $_POST["new_price"];
            mysqli_query($conn, "UPDATE pizzas SET price = '$newPrice' WHERE id = $id");
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }

        if (isset($_POST["delete_pizza"])) {
            $id = (int) $_POST["item_id"];
            mysqli_query($conn, "DELETE FROM pizzas WHERE id = $id");
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }

        if (isset($_POST["add_topping"])) {
            $name = mysqli_real_escape_string($conn, $_POST["name"]);
            $price = $_POST["price"];
            mysqli_query($conn, "INSERT INTO toppings (name, price) VALUES ('$name', '$price')");
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }

        if (isset($_POST["update_topping"])) {
            $id = (int) $_POST["item_id"];
            $newPrice = $_POST["new_price"];
            mysqli_query($conn, "UPDATE toppings SET price = '$newPrice' WHERE id = $id");
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }

        if (isset($_POST["delete_topping"])) {
            $id = (int) $_POST["item_id"];
            mysqli_query($conn, "DELETE FROM toppings WHERE id = $id");
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }

        if (isset($_POST["create_order"])) {
            $pizzaId = (int) $_POST["pizza_id"];
            $qty = (int) $_POST["qty"];
            if ($qty < 1) {
                $qty = 1;
            }

            $res = mysqli_query($conn, "SELECT name, price FROM pizzas WHERE id = $pizzaId");
            $pizzaRow = mysqli_fetch_assoc($res);

            $toppingsTotal = 0;
            $toppingList = "";
            if (isset($_POST["toppings"])) {
                foreach ($_POST["toppings"] as $topId) {
                    $topId = (int) $topId;
                    $r = mysqli_query($conn, "SELECT name, price FROM toppings WHERE id = $topId");
                    $t = mysqli_fetch_assoc($r);
                    if ($t) {
                        $toppingsTotal = $toppingsTotal + $t["price"];
                        $toppingList = $toppingList . $t["name"] . ", ";
                    }
                }
            }
            $toppingList = rtrim($toppingList, ", ");

            if ($pizzaRow) {
                $pizzaName = mysqli_real_escape_string($conn, $pizzaRow["name"]);
                $pizzaPrice = $pizzaRow["price"];
                $grand = ($pizzaPrice + $toppingsTotal) * $qty;
                $customer = mysqli_real_escape_string($conn, $_POST["customer"]);
                $topsForDb = mysqli_real_escape_string($conn, $toppingList);

                mysqli_query($conn, "INSERT INTO orders (customer, pizza, toppings, qty, total, status) VALUES ('$customer', '$pizzaName', '$topsForDb', $qty, '$grand', 'Pending')");
            }

            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }

        if (isset($_POST["update_status"])) {
            $oid = (int) $_POST["order_id"];
            mysqli_query($conn, "UPDATE orders SET status = 'Completed' WHERE id = $oid");
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }

        if (isset($_POST["delete_order"])) {
            $oid = (int) $_POST["order_id"];
            mysqli_query($conn, "DELETE FROM orders WHERE id = $oid");
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🍕 Pizza Master Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #FF6B6B 0%, #FFA500 100%); min-height: 100vh; padding: 40px 20px; color: #333;}
        .container { max-width: 1200px; margin: 0 auto; }
        header { text-align: center; color: white; margin-bottom: 40px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        h1 { font-size: 3em; margin-bottom: 10px; }
        
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;}
        .full-width { grid-column: 1 / -1; }
        @media(max-width: 800px) { .grid-layout { grid-template-columns: 1fr; } }
        
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        .card h2 { color: #FF6B6B; border-bottom: 3px solid #FFA500; padding-bottom: 10px; margin-bottom: 20px; }
        
        .form-group { display: flex; gap: 10px; margin-bottom: 20px; align-items: flex-end; }
        .form-stack { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
        input[type="text"], input[type="number"] { padding: 10px; border: 2px solid #FF6B6B; border-radius: 8px; width: 100%; }
        
        .radio-group, .checkbox-group { display: flex; flex-direction: column; gap: 10px; }
        .selection-item { display: flex; align-items: center; padding: 10px; border-radius: 8px; cursor: pointer; background: #fff5f5;}
        .selection-item:hover { background-color: #ffe8e8; }
        .selection-item input { margin-right: 10px; width: 18px; height: 18px; accent-color: #FF6B6B; }
        .price { color: #FFA500; font-weight: bold; }
        
        button { padding: 10px 15px; background: #FF6B6B; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        button:hover { background: #FFA500; }
        .btn-large { width: 100%; padding: 15px; font-size: 1.1em; }
        .btn-update { background: #4CAF50; padding: 6px 12px; font-size: 0.9em; }
        .btn-delete { background: #f44336; padding: 6px 12px; font-size: 0.9em; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ecf0f1; }
        th { background-color: #FFF5E6; color: #FF6B6B; }
        .price-input { width: 90px !important; padding: 6px !important; margin-right: 5px; border: 1px solid #ccc !important;}
        
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; color: white; }
        .bg-pending { background-color: #FFA500; }
        .bg-completed { background-color: #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🍕 Pizza Master Dashboard</h1>
            <p>Admin Menu Management & Live Ordering System</p>
        </header>

        <div class="grid-layout">
            
            <div class="card">
                <h2>⚙️ Manage Pizzas</h2>
                <form method="post" class="form-group">
                    <div style="flex: 2;"><input type="text" name="name" placeholder="New Pizza Name" required></div>
                    <div style="flex: 1;"><input type="number" name="price" step="0.01" min="0" placeholder="Price" required></div>
                    <button type="submit" name="add_pizza">Add</button>
                </form>
                <table>
                    <tbody>
                        <?php
                            $pizzaResult = mysqli_query($conn, "SELECT * FROM pizzas");
                            if ($pizzaResult && mysqli_num_rows($pizzaResult) > 0) {
                                while ($p = mysqli_fetch_assoc($pizzaResult)) {
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($p["name"]); ?></strong></td>
                                <td>
                                    <form method="post" style="display:flex;">
                                        <input type="hidden" name="item_id" value="<?php echo $p["id"]; ?>">
                                        <input type="number" name="new_price" value="<?php echo $p["price"]; ?>" step="0.01" class="price-input" required>
                                        <button type="submit" name="update_pizza" class="btn-update">Save</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="item_id" value="<?php echo $p["id"]; ?>">
                                        <button type="submit" name="delete_pizza" class="btn-delete">✖</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='3'><em>No pizzas yet.</em></td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h2>⚙️ Manage Toppings</h2>
                <form method="post" class="form-group">
                    <div style="flex: 2;"><input type="text" name="name" placeholder="New Topping Name" required></div>
                    <div style="flex: 1;"><input type="number" name="price" step="0.01" min="0" placeholder="Price" required></div>
                    <button type="submit" name="add_topping">Add</button>
                </form>
                <table>
                    <tbody>
                        <?php
                            $topResult = mysqli_query($conn, "SELECT * FROM toppings");
                            if ($topResult && mysqli_num_rows($topResult) > 0) {
                                while ($t = mysqli_fetch_assoc($topResult)) {
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($t["name"]); ?></strong></td>
                                <td>
                                    <form method="post" style="display:flex;">
                                        <input type="hidden" name="item_id" value="<?php echo $t["id"]; ?>">
                                        <input type="number" name="new_price" value="<?php echo $t["price"]; ?>" step="0.01" class="price-input" required>
                                        <button type="submit" name="update_topping" class="btn-update">Save</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="item_id" value="<?php echo $t["id"]; ?>">
                                        <button type="submit" name="delete_topping" class="btn-delete">✖</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='3'><em>No toppings yet.</em></td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="max-width: 800px; margin: 0 auto 30px auto;">
            <h2>🛒 Place New Order</h2>
            <form method="post">
                <div class="form-stack">
                    <label><strong>Customer Name</strong></label>
                    <input type="text" name="customer" required>
                </div>

                <div class="grid-layout" style="gap: 20px; margin-bottom: 0;">
                    
                    <div class="form-stack">
                        <label><strong>Select Pizza</strong></label>
                        <div class="radio-group">
                            <?php
                                $orderPizzas = mysqli_query($conn, "SELECT * FROM pizzas");
                                if ($orderPizzas && mysqli_num_rows($orderPizzas) > 0) {
                                    while ($op = mysqli_fetch_assoc($orderPizzas)) {
                            ?>
                                <label class="selection-item">
                                    <input type="radio" name="pizza_id" value="<?php echo $op["id"]; ?>" required>
                                    <?php echo htmlspecialchars($op["name"]); ?>
                                    <span class="price">₱<?php echo $op["price"]; ?></span>
                                </label>
                            <?php
                                    }
                                } else {
                                    echo "<label class='selection-item'><em>Add pizzas first.</em></label>";
                                }
                            ?>
                        </div>
                    </div>

                    <div class="form-stack">
                        <label><strong>Select Toppings</strong></label>
                        <div class="checkbox-group">
                            <?php
                                $orderTops = mysqli_query($conn, "SELECT * FROM toppings");
                                if ($orderTops && mysqli_num_rows($orderTops) > 0) {
                                    while ($ot = mysqli_fetch_assoc($orderTops)) {
                            ?>
                                <label class="selection-item">
                                    <input type="checkbox" name="toppings[]" value="<?php echo $ot["id"]; ?>">
                                    <?php echo htmlspecialchars($ot["name"]); ?>
                                    <span class="price">+₱<?php echo $ot["price"]; ?></span>
                                </label>
                            <?php
                                    }
                                } else {
                                    echo "<label class='selection-item'><em>No toppings yet.</em></label>";
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-stack" style="margin-top: 15px;">
                    <label><strong>Quantity</strong></label>
                    <input type="number" name="qty" min="1" value="1" required>
                </div>

                <button type="submit" name="create_order" class="btn-large">🚀 Submit Order</button>
            </form>
        </div>

        <div class="card full-width">
            <h2>📋 Live Kitchen Orders</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Customer</th><th>Order Details</th><th>Total</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $ordResult = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
                            if ($ordResult && mysqli_num_rows($ordResult) > 0) {
                                while ($o = mysqli_fetch_assoc($ordResult)) {
                                    $tops = $o["toppings"];
                                    if (empty($tops)) {
                                        $tops = "None";
                                    }
                        ?>
                            <tr>
                                <td><?php echo $o["id"]; ?></td>
                                <td><?php echo htmlspecialchars($o["customer"]); ?></td>
                                <td>
                                    <?php echo $o["qty"]; ?> x <?php echo htmlspecialchars($o["pizza"]); ?>
                                    <br><small>Toppings: <?php echo htmlspecialchars($tops); ?></small>
                                </td>
                                <td>₱<?php echo number_format($o["total"], 2); ?></td>
                                <td>
                                    <?php if ($o["status"] == "Pending") { ?>
                                        <span class="badge bg-pending">Pending</span>
                                    <?php } else { ?>
                                        <span class="badge bg-completed"><?php echo htmlspecialchars($o["status"]); ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($o["status"] == "Pending") { ?>
                                        <form method="post" style="display:inline-block;margin-right:6px;">
                                            <input type="hidden" name="order_id" value="<?php echo $o["id"]; ?>">
                                            <button type="submit" name="update_status" class="btn-update">✔</button>
                                        </form>
                                    <?php } ?>
                                    <form method="post" style="display:inline-block;">
                                        <input type="hidden" name="order_id" value="<?php echo $o["id"]; ?>">
                                        <button type="submit" name="delete_order" class="btn-delete">✖</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' style='text-align:center;'><em>No orders yet.</em></td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
</html>