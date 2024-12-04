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

// Handle adding medicine (POST request)
if (isset($_POST['add_medicine'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Insert into the database
    $addMedicineQuery = "INSERT INTO medicines (name, manufacturer, price, stock) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($addMedicineQuery);
    $stmt->bind_param("ssdi", $name, $description, $price, $stock);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Medicine added successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Medicine</title>
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

    header {
      text-align: center;
      margin-top: 50px;
    }

    h1 {
      font-size: 2.5em;
    }

    .form-container {
      background-color: rgba(0, 0, 0, 0.7);
      width: 50%;
      margin: 50px auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    label {
      display: block;
      margin: 10px 0 5px;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    button:hover {
      background-color: #45a049;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      font-size: 1.2em;
      text-decoration: none;
      color: white;
    }

    .back-link:hover {
      color: #ddd;
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

  <header>
    <h1>Add New Medicine</h1>
  </header>

  <div class="form-container">
    <form action="add_medicine.php" method="POST">
        <label for="name">Medicine Name</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price</label>
        <input type="number" id="price" name="price" required>

        <label for="stock">Stock Quantity</label>
        <input type="number" id="stock" name="stock" required>

        <button type="submit" name="add_medicine">Add Medicine</button>
    </form>
  </div>

  <a href="dashboard.php" class="back-link">Back to Dashboard</a>

</body>
</html>
