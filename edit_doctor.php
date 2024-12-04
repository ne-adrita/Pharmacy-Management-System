<?php
// edit_doctor.php
include 'db_connect.php';

if (isset($_GET['phone_number'])) {
    $phone_number = $_GET['phone_number'];

    // Fetch doctor data
    $sql = "SELECT * FROM doctors WHERE phone_number=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();

    if (!$doctor) {
        echo "Doctor not found!";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get new values from the form
    $phone_number = $_POST['phone_number'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialist = $_POST['specialist'];

    // Update query
    $sql = "UPDATE doctors SET name=?, email=?, specialist=? WHERE phone_number=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $specialist, $phone_number);

    if ($stmt->execute()) {
        echo "Doctor updated successfully";
        header("Location: doctors.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Doctor</title>
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
    <h1>Edit Doctor</h1>

    <!-- Edit Doctor Form -->
    <form method="POST" action="">
        <input type="hidden" name="phone_number" value="<?php echo htmlspecialchars($doctor['phone_number']); ?>" />
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($doctor['name']); ?>" required />
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($doctor['email']); ?>" required />
        <br>
        <label for="specialist">Specialist:</label>
        <input type="text" name="specialist" value="<?php echo htmlspecialchars($doctor['specialist']); ?>" required />
        <br>
        <button type="submit">Update Doctor</button>
    </form>
</div>

<?php
// Close the connection
$stmt->close();
$conn->close();
?>

</body>
</html>
