<?php
// Include database connection
include 'db_connect.php';

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$query = "SELECT c.cart_id, c.medicine_id, c.quantity, m.name, m.price, m.stock
          FROM cart c
          JOIN medicines m ON c.medicine_id = m.medicine_id
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Handle updating cart quantities
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $cartId => $quantity) {
        if ($quantity > 0) {
            // Fetch current quantity in cart and medicine stock
            $selectQuery = "SELECT quantity, medicine_id FROM cart WHERE cart_id = ?";
            $selectStmt = $conn->prepare($selectQuery);
            $selectStmt->bind_param("i", $cartId);
            $selectStmt->execute();
            $selectResult = $selectStmt->get_result();
            $cartItem = $selectResult->fetch_assoc();
            
            $currentQuantity = $cartItem['quantity'];
            $medicineId = $cartItem['medicine_id'];
            
            // Get current stock of the medicine
            $stockQuery = "SELECT stock FROM medicines WHERE medicine_id = ?";
            $stockStmt = $conn->prepare($stockQuery);
            $stockStmt->bind_param("i", $medicineId);
            $stockStmt->execute();
            $stockResult = $stockStmt->get_result();
            $stock = $stockResult->fetch_assoc()['stock'];

            // Calculate stock difference based on quantity change
            $stockDiff = $quantity - $currentQuantity;
            $newStock = $stock - $stockDiff;  // Decrease stock if quantity increased, increase stock if quantity decreased

            // Update stock in the medicines table
            $updateStockQuery = "UPDATE medicines SET stock = ? WHERE medicine_id = ?";
            $updateStockStmt = $conn->prepare($updateStockQuery);
            $updateStockStmt->bind_param("ii", $newStock, $medicineId);
            $updateStockStmt->execute();

            // Update quantity in the cart
            $updateQuery = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ii", $quantity, $cartId);
            $updateStmt->execute();
        }
    }
    // Redirect to cart page after updating
    header("Location: cart.php");
    exit();
}

// Handle removing items from the cart
if (isset($_GET['remove_item'])) {
    $cartId = $_GET['remove_item'];

    // Fetch quantity and medicine_id from cart
    $selectQuery = "SELECT quantity, medicine_id FROM cart WHERE cart_id = ?";
    $selectStmt = $conn->prepare($selectQuery);
    $selectStmt->bind_param("i", $cartId);
    $selectStmt->execute();
    $selectResult = $selectStmt->get_result();
    $cartItem = $selectResult->fetch_assoc();

    $quantityToRemove = $cartItem['quantity'];
    $medicineId = $cartItem['medicine_id'];

    // Get current stock of the medicine
    $stockQuery = "SELECT stock FROM medicines WHERE medicine_id = ?";
    $stockStmt = $conn->prepare($stockQuery);
    $stockStmt->bind_param("i", $medicineId);
    $stockStmt->execute();
    $stockResult = $stockStmt->get_result();
    $currentStock = $stockResult->fetch_assoc()['stock'];

    // Increase the stock by the quantity that was removed
    $newStock = $currentStock + $quantityToRemove;
    $updateStockQuery = "UPDATE medicines SET stock = ? WHERE medicine_id = ?";
    $updateStockStmt = $conn->prepare($updateStockQuery);
    $updateStockStmt->bind_param("ii", $newStock, $medicineId);
    $updateStockStmt->execute();

    // Remove the item from the cart
    $removeQuery = "DELETE FROM cart WHERE cart_id = ?";
    $removeStmt = $conn->prepare($removeQuery);
    $removeStmt->bind_param("i", $cartId);
    $removeStmt->execute();

    // Redirect to cart page after removing
    header("Location: cart.php");
    exit();
}

// Close the database connection
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('https://ezscrpt.com/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/11/AdobeStock_97410153.jpeg.webp');
      background-size: cover;
      background-position: center;
      color: white;
      margin: 0;
      padding: 0;
    }

    .navbar {
        background-color: #333;
        overflow: hidden;
    }

    .navbar a {
        float: left;
        display: block;
        color: white;
        padding: 14px 20px;
        text-align: center;
        text-decoration: none;
    }

    .navbar a:hover {
        background-color: #ddd;
        color: black;
    }

    h1 {
      text-align: center;
      margin-top: 20px;
    }

    .cart-item {
      background-color: rgba(0, 0, 0, 0.7);
      padding: 20px;
      margin: 10px 0;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .cart-item h3 {
      margin-bottom: 10px;
    }

    .cart-item p {
      margin: 5px 0;
    }

    .cart-item input {
      width: 50px;
      text-align: center;
    }

    .cart-item button {
      background-color: #f44336;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
    }

    .cart-item button:hover {
      background-color: #d32f2f;
    }

    .cart-summary {
      margin-top: 20px;
      text-align: right;
    }

    .update-cart-btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
    }

    .update-cart-btn:hover {
      background-color: #45a049;
    }

    .checkout-btn {
      background-color: #007bff;
      padding: 10px 20px;
      color: white;
      border: none;
      text-decoration: none;
      border-radius: 5px;
    }

    .checkout-btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="customers.php">Customers</a>
    <a href="cart.php">Cart</a>
    <a href="index.php">Logout</a>
</div>

  <h1>Your Cart</h1>

  <form method="post" action="cart.php">
    <?php
    if ($result->num_rows > 0) {
        $totalPrice = 0;
        while ($row = $result->fetch_assoc()) {
            $subtotal = $row['price'] * $row['quantity'];
            $totalPrice += $subtotal;
            ?>
            <div class="cart-item">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p>Price: $<?php echo htmlspecialchars($row['price']); ?></p>
                <p>Quantity: 
                    <input type="number" name="quantity[<?php echo $row['cart_id']; ?>]" value="<?php echo $row['quantity']; ?>" min="1">
                </p>
                <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                <a href="cart.php?remove_item=<?php echo $row['cart_id']; ?>" class="remove-item-btn">
                    <button type="button">Remove</button>
                </a>
            </div>
            <?php
        }
        ?>
        <div class="cart-summary">
            <p><strong>Total: $<?php echo number_format($totalPrice, 2); ?></strong></p>
            <button type="submit" name="update_cart" class="update-cart-btn">Update Cart</button>
        </div>
        <div class="cart-summary">
            <a href="checkout.php" class="checkout-btn">Checkout</a>
        </div>
        <?php
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    ?>
  </form>

</body>
</html>
