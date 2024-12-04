<?php
// add_doctor.php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get new doctor values from the form
    $phone_number = $_POST['phone_number'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialist = $_POST['specialist'];

    // Insert new doctor into the database
    $sql = "INSERT INTO doctors (phone_number, name, email, specialist) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $phone_number, $name, $email, $specialist);

    if ($stmt->execute()) {
        echo "<script>alert('Doctor added successfully!'); window.location.href='doctors.php';</script>";
    } else {
        echo "Error adding doctor: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Doctor</title>
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
    <h1>Add New Doctor</h1>

    <!-- Add Doctor Form -->
    <form method="POST" action="">
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" required />
        <br>
        <label for="name">Name:</label>
        <input type="text" name="name" required />
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" required />
        <br>
        <label for="specialist">Specialist:</label>
        <input type="text" name="specialist" required />
        <br>
        <button type="submit">Add Doctor</button>
    </form>
</div>

</body>
</html>
