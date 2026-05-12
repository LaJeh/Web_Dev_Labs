<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍕 Pizza Ordering System - Student Template</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #FF6B6B 0%, #FFA500 50%, #FFD700 100%); min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { text-align: center; color: white; margin-bottom: 40px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        h1 { font-size: 3.5em; margin-bottom: 10px; text-transform: uppercase; }
        .card { background: white; border-radius: 20px; padding: 35px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); border: 3px solid #FF6B6B; margin-bottom: 30px; }
        .card h2 { color: #FF6B6B; border-bottom: 3px solid #FFA500; padding-bottom: 10px; margin-bottom: 20px; }
        .content { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .full-width { grid-column: 1 / -1; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
        input[type="text"], input[type="number"] { padding: 12px; border: 2px solid #FF6B6B; border-radius: 10px; }
        .radio-group, .checkbox-group { display: flex; flex-direction: column; gap: 10px; }
        .radio-item, .checkbox-item { display: flex; align-items: center; padding: 10px; border-radius: 8px; cursor: pointer; transition: background-color 0.3s;}
        .radio-item:hover, .checkbox-item:hover { background-color: #fff5f5; }
        .radio-item input, .checkbox-item input { margin-right: 10px; width: 18px; height: 18px; accent-color: #FF6B6B; cursor: pointer;}
        .radio-item label, .checkbox-item label { cursor: pointer; flex: 1; display: flex; justify-content: space-between; }
        .price { color: #FFA500; font-weight: 700; }
        button { padding: 15px; background: linear-gradient(135deg, #FF6B6B, #FFA500); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; width: 100%; text-transform: uppercase; font-size: 1.1em;}
        .summary-item { display: flex; justify-content: space-between; padding: 10px; background: #fff5f5; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #FFA500; }
        .total { font-weight: bold; color: white; font-size: 1.5em; background: linear-gradient(135deg, #FF6B6B, #FFA500); padding: 20px; border-radius: 10px; text-align: center; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; }
        .menu-item { background: #FFF5E6; padding: 15px; border-radius: 12px; border: 2px solid #FFA500; text-align: center; }
        .menu-item-name { font-weight: bold; color: #FF6B6B; margin-bottom: 5px; }
        .order-counter { background: linear-gradient(135deg, #FF6B6B, #FFA500); color: white; }
        .order-item { background: rgba(255,255,255,0.2); padding: 10px; border-radius: 8px; margin-bottom: 5px; font-weight: 500; }
        .section-title { color: #FF6B6B; margin: 20px 0 10px; text-transform: uppercase; grid-column: 1 / -1; font-size: 1.2em;}
    </style>
</head>
<body>

    <?php
        // Pre-defined Associative Arrays for the Menu
        $pizzaMenu = [
            "Cheese" => 150,
            "Margherita" => 160,
            "Veggie" => 170,
            "Pepperoni" => 180,
            "Four Cheese" => 190,
            "Hawaiian" => 200,
            "BBQ Chicken" => 220,
            "Meat Lovers" => 250
        ];

        $toppingsMenu = [
            "Onions" => 15,
            "Green Peppers" => 15,
            "Mushroom" => 20,
            "Jalapenos" => 20,
            "Olives" => 25,
            "Pineapple" => 25,
            "Extra Cheese" => 30,
            "Sausage" => 35,
            "Bacon" => 40
        ];
        
        function computePizzaTotal($pizzaMenu, $toppingsMenu, $pizzaChoice, $toppingsSelected, $qty){
            $pizzaCost = $pizzaMenu[$pizzaChoice];
            $toppingsTotal = 0;

            if(!empty($toppingsSelected)){
                foreach ($toppingsSelected as $topping){
                    $toppingsTotal += $toppingsMenu[$topping];
                }
            }

            $total = $qty * ($pizzaCost + $toppingsTotal);
            return $total;
        }
    ?>

    <div class="container">
        <header>
            <h1><span>🍕</span> Pizza Bites Express</h1>
            <p>Laboratory Exercise: PHP Control Structures & Functions</p>
        </header>

        <div class="content">
            <div class="card">
                <h2>🍴 Order Now</h2>
                <form method="post">
                    <div class="form-group">
                        <label>Customer Name</label>
                        <input type="text" name="customer" required>
                    </div>

                    <div class="form-group">    
                        <label>Select Your Pizza</label>
                        <div class="radio-group">
                            <?php
                                foreach ($pizzaMenu as $pizzaName => $pizzaPrice) {
                                    echo '<div class="radio-item">';
                                    echo '<input type="radio" name="pizza" value="'.$pizzaName.'" required>';
                                    echo '<label>'.$pizzaName.'<span class="price">₱'.$pizzaPrice.'</span></label>';
                                    echo '</div>';
                                }
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Add Toppings</label>
                        <div class="checkbox-group">
                            <?php
                                foreach ($toppingsMenu as $toppingName => $toppingPrice) {
                                    echo '<div class="checkbox-item">';
                                    echo '<input type="checkbox" name="toppings[]" value="'.$toppingName.'">';
                                    echo '<label>'.$toppingName.' <span class="price">₱'.$toppingPrice.'</span></label>';
                                    echo '</div>';
                                }
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="qty" min="1" value="1">
                    </div>

                    <button type="submit" name="order">🚀 Place Order</button>
                </form>
            </div>

            <div class="card">
                <h2>📋 Order Summary</h2>
                <?php
                    if (isset($_POST['order'])) {
                        $customerName = $_POST["customer"];
                        $pizzaChoice = $_POST["pizza"];
                        $toppingsSelected = isset($_POST['toppings']) ? $_POST['toppings'] : [];
                        $qty = $_POST['qty'];
                        
                        $grandTotal = computePizzaTotal($pizzaMenu, $toppingsMenu, $pizzaChoice, $toppingsSelected, $qty);
                        
                        $toppingsDisplay = empty($toppingsSelected) ? "None" : implode(", ", $toppingsSelected);
                        
                        echo "<div class='summary-item'><span>Customer:</span><span>$customerName</span></div>";
                        echo "<div class='summary-item'><span>Pizza:</span><span>$pizzaChoice</span></div>";
                        echo "<div class='summary-item'><span>Toppings:</span><span>$toppingsDisplay</span></div>";
                        echo "<div class='summary-item'><span>Quantity:</span><span>$qty</span></div>";
                        echo "<div class='total'>Grand Total: ₱$grandTotal</div>";

                    } else {
                        echo "<p style='text-align: center; color: #999;'>Place an order to see summary</p>";
                    }
                ?>
            </div>

            <div class="card full-width">
                <h2>📚 Menu Price List</h2>
                <div class="menu-grid">
                    <?php
                    foreach ($pizzaMenu as $pizzaName => $pizzaPrice) {
                        echo '<div class="menu-item">';
                        echo '<div class="menu-item-name">'.$pizzaName.'</div>';
                        echo '<div class="price">₱'.$pizzaPrice.'</div>';
                        echo '</div>';
                    }
                    foreach ($toppingsMenu as $toppingName => $toppingPrice) {
                        echo '<div class="menu-item">';
                        echo '<div class="menu-item-name">'.$toppingName.'</div>';
                        echo '<div class="price">₱'.$toppingPrice.'</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <div class="card full-width order-counter">
                <h2>🎯 Order Counter</h2>
                <?php
                    if (isset($_POST['order'])) {
                        for ($i = 1; $i <= $qty; $i++) {
                            echo "<div class='order-item'>Order #$i: $pizzaChoice with ".(empty($toppingsSelected) ? "no toppings" : implode(", ", $toppingsSelected))."</div>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
