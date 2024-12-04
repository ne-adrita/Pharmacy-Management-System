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

// Handle adding to the cart (database-based cart storage)
if (isset($_GET['add_to_cart'])) {
    $medicineId = $_GET['add_to_cart'];
    $userId = $_SESSION['user_id'];
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

    // Check if the medicine is available in stock
    $stockQuery = "SELECT stock FROM medicines WHERE medicine_id = ?";
    $stmt = $conn->prepare($stockQuery);
    $stmt->bind_param("i", $medicineId);
    $stmt->execute();
    $stockResult = $stmt->get_result();
    $medicine = $stockResult->fetch_assoc();

    // Check if the medicine is available in stock
    if ($medicine && $medicine['stock'] >= $quantity) {
        // Check if the medicine is already in the user's cart
        $checkQuery = "SELECT * FROM cart WHERE user_id = ? AND medicine_id = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ii", $userId, $medicineId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If the item is already in the cart, update the quantity
            $updateQuery = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND medicine_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("iii", $quantity, $userId, $medicineId);
            $stmt->execute();
        } else {
            // If the item is not in the cart, insert it into the cart table
            $insertQuery = "INSERT INTO cart (user_id, medicine_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iii", $userId, $medicineId, $quantity);
            $stmt->execute();
        }

        // Deduct the quantity from the stock
        $newStock = $medicine['stock'] - $quantity;
        $updateStockQuery = "UPDATE medicines SET stock = ? WHERE medicine_id = ?";
        $stmt = $conn->prepare($updateStockQuery);
        $stmt->bind_param("ii", $newStock, $medicineId);
        $stmt->execute();

        // Close the statement
        $stmt->close();

        // Redirect back to the dashboard page with a success message
        echo "<script>alert('Added to cart!'); window.location.href='dashboard.php';</script>";
    } else {
        // If no stock is available or insufficient quantity
        echo "<script>alert('Sorry, insufficient stock or invalid quantity!'); window.location.href='dashboard.php';</script>";
    }
    exit();
}

// Handle CRUD operations on medicines (Create, Update, Delete)

// Add Medicine (Create)
if (isset($_POST['add_medicine'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $addMedicineQuery = "INSERT INTO medicines (name, description, price, stock) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($addMedicineQuery);
    $stmt->bind_param("ssdi", $name, $description, $price, $stock);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Medicine added successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}

// Update Medicine (Edit)
if (isset($_POST['update_medicine'])) {
    $medicine_id = $_POST['medicine_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $updateQuery = "UPDATE medicines SET name = ?, description = ?, price = ?, stock = ? WHERE medicine_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssdis", $name, $description, $price, $stock, $medicine_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Medicine updated successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}

// Delete Medicine
if (isset($_GET['delete_medicine'])) {
    $medicine_id = $_GET['delete_medicine'];

    $deleteQuery = "DELETE FROM medicines WHERE medicine_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $medicine_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Medicine deleted successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}

// Handle search query
$searchQuery = '';
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $searchQuery = "%" . $searchQuery . "%"; // To match the search term partially
}

// Fetch medicines based on search query or all medicines
$query = "SELECT medicine_id, name, price, stock FROM medicines WHERE name LIKE ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $searchQuery);
$stmt->execute();
$result = $stmt->get_result();

// Close the database connection
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <style>
    /* Basic Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      color: #333;
      line-height: 1.6;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    header nav a {
      color: #fff;
      text-decoration: none;
      margin: 0 15px;
      font-size: 1.1em;
    }

    header nav a:hover {
      text-decoration: underline;
    }

    .cart-icon {
      position: absolute;
      top: 20px;
      right: 20px;
      cursor: pointer;
      font-size: 1.5em;
      background-color: #333;
      color: #fff;
      padding: 10px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .cart-count {
      position: absolute;
      top: 0;
      right: 0;
      background-color: red;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: 0.9em;
      text-align: center;
    }

    .search-container {
      display: flex;
      justify-content: center;
      margin: 20px 0;
    }

    #medicine-search {
      padding: 10px;
      font-size: 1em;
      width: 300px;
      margin: 0 10px;
    }

    .search-btn {
      padding: 10px 15px;
      background-color: #333;
      color: #fff;
      border: none;
      cursor: pointer;
    }

    .search-btn:hover {
      background-color: #555;
    }

    .medicine-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }

    .medicine-item {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 200px;
    }

    .medicine-item h3 {
      font-size: 1.2em;
      margin-bottom: 10px;
    }

    .medicine-item p {
      font-size: 1em;
      margin: 10px 0;
    }

    .add-to-cart-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      border-radius: 5px;
      text-align: center;
      display: block;
      width: 100%;
    }

    .add-to-cart-btn:hover {
      background-color: #218838;
    }

    .show-all-btn {
      margin-top: 20px;
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 1.1em;
      display: block;
      width: 100%;
    }

    .show-all-btn:hover {
      background-color: #0056b3;
    }

    .edit-btn, .delete-btn {
  display: inline-block;
  margin-top: 10px;
  padding: 5px 10px;
  font-size: 1em;
  color: white;
  text-align: center;
  text-decoration: none;
  border-radius: 5px;
}

.edit-btn {
  background-color: #007bff;
}

.edit-btn:hover {
  background-color: #0056b3;
}

.delete-btn {
  background-color: #dc3545;
}

.delete-btn:hover {
  background-color: #c82333;
}

body {
  font-family: Arial, sans-serif;
  background-image: url('https://ezscrpt.com/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/11/AdobeStock_97410153.jpeg.webp');
  background-size: cover; /* Ensure the image covers the entire page */
  background-position: center; /* Center the image */
  color: #333;
  line-height: 1.6;
  margin: 0;
  padding: 0;
}



  </style>
</head>
<body>

<header>
  <h1>Welcome to the Pharmacy Management System (PMS)</h1>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="doctors.php">Doctors</a>
    <a href="customers.php">Customers</a>
    <a href="add_medicine.php">Add Medicine</a>
    <a href="cart.php">Cart</a>
    <a href="index.php">Logout</a>
  </nav>
</header>

<div class="search-container">
  <form method="get" action="dashboard.php">
    <input type="text" id="medicine-search" name="query" placeholder="Search for a medicine" value="<?= htmlspecialchars($searchQuery) ?>" />
    <button type="submit" class="search-btn">Search</button>
  </form>
</div>

<div class="medicine-list">
  <?php while ($medicine = $result->fetch_assoc()) : ?>
    <div class="medicine-item">
      <h3><?= htmlspecialchars($medicine['name']) ?></h3>
      <p>Price: $<?= htmlspecialchars($medicine['price']) ?></p>
      <p>Stock: <?= htmlspecialchars($medicine['stock']) ?></p>
      <a href="javascript:void(0);" class="add-to-cart-btn" onclick="askQuantity(<?= $medicine['medicine_id'] ?>)">Add to Cart</a>
      <a href="edit_medicine.php?medicine_id=<?= $medicine['medicine_id'] ?>" class="edit-btn">Edit</a>
      <a href="dashboard.php?delete_medicine=<?= $medicine['medicine_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this medicine?');">Delete</a>
    </div>
  <?php endwhile; ?>
</div>

<script>
  function askQuantity(medicineId) {
    var quantity = prompt("Enter quantity:", "1");
    if (quantity != null && quantity > 0) {
      // Redirect to the same page with the quantity parameter
      window.location.href = "dashboard.php?add_to_cart=" + medicineId + "&quantity=" + quantity;
    }
  }
</script>

</body>
</html>
