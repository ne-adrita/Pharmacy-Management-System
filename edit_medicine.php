<?php
// edit_medicine.php
include 'db_connect.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['medicine_id'])) {
    $medicine_id = $_GET['medicine_id'];

    // Fetch the medicine details from the database
    $query = "SELECT * FROM medicines WHERE medicine_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $medicine_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the medicine exists
    if ($result->num_rows > 0) {
        $medicine = $result->fetch_assoc();
    } else {
        echo "Medicine not found!";
        exit();
    }

    $stmt->close();
} else {
    echo "No medicine ID provided!";
    exit();
}

if (isset($_POST['edit_medicine'])) {
    $name = $_POST['name'];
    $manufacturer = $_POST['manufacturer'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Update the medicine details in the database
    $updateQuery = "UPDATE medicines SET name = ?, manufacturer = ?, price = ?, stock = ?, updated_at = NOW() WHERE medicine_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssdii", $name, $manufacturer, $price, $stock, $medicine_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Medicine updated successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Medicine</title>
  <style>
      body {
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
          background-image: url('https://ezscrpt.com/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/11/AdobeStock_97410153.jpeg.webp');
          background-size: cover;
          background-position: center;
          color: white;
      }

      .container {
          width: 80%;
          margin: 0 auto;
          padding: 20px;
          background: rgba(0, 0, 0, 0.5);
          border-radius: 10px;
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

      form {
          width: 60%;
          margin: 0 auto;
          padding: 20px;
          background: rgba(0, 0, 0, 0.5);
          border-radius: 10px;
      }

      input, textarea {
          width: 100%;
          padding: 10px;
          margin: 10px 0;
          border: 1px solid #ccc;
          border-radius: 5px;
      }

      button {
          padding: 10px 20px;
          background-color: #007bff;
          color: white;
          border: none;
          cursor: pointer;
          border-radius: 5px;
      }

      button:hover {
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

<!-- Main content -->
<div class="container">
    <h1>Edit Medicine</h1>

    <!-- Edit Medicine Form -->
    <form action="edit_medicine.php?medicine_id=<?php echo $medicine['medicine_id']; ?>" method="POST">
        <input type="hidden" name="medicine_id" value="<?php echo $medicine['medicine_id']; ?>">

        <label for="name">Medicine Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($medicine['name']); ?>" required>
        <br><br>

        <label for="manufacturer">Manufacturer:</label>
        <input type="text" id="manufacturer" name="manufacturer" value="<?php echo htmlspecialchars($medicine['manufacturer']); ?>" required>
        <br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($medicine['price']); ?>" required>
        <br><br>

        <label for="stock">Stock Quantity:</label>
        <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($medicine['stock']); ?>" required>
        <br><br>

        <button type="submit" name="edit_medicine">Update Medicine</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
