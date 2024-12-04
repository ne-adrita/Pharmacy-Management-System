<?php
// doctors.php

// Include the database connection file
include 'db_connect.php';

// Check if a search is being performed
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Check if the delete request is made
if (isset($_GET['delete_phone_number'])) {
    $delete_phone_number = $_GET['delete_phone_number'];

    // Delete the doctor with the given phone number
    $sql = "DELETE FROM doctors WHERE phone_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $delete_phone_number);

    if ($stmt->execute()) {
        // Redirect to the doctor list page after successful deletion
        header("Location: doctors.php");
        exit();
    } else {
        echo "Error deleting doctor: " . $stmt->error;
    }
}

// Query to fetch doctors, searching by name or phone number
$sql = "SELECT * FROM doctors WHERE name LIKE ? OR phone_number LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%" . $search_query . "%";
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
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

        .search-container {
            margin: 20px 0;
            text-align: center;
        }

        .search-container input {
            width: 300px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
            text-align: left;
            padding: 10px;
        }

        th {
            background-color: #333;
            color: white;
        }

        td {
            background-color: rgba(0, 0, 0, 0.5);
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .add-doctor {
            text-align: center;
            margin-top: 20px;
        }

        .add-doctor a {
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }

        .add-doctor a:hover {
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
    <h1>Doctor List</h1>

    <!-- Search Form -->
    <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search by Name or Phone Number" value="<?php echo htmlspecialchars($search_query); ?>" />
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Doctors Table -->
    <table>
        <thead>
            <tr>
                <th>Phone Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Specialist</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['specialist']); ?></td>
                        <td>
                            <a href="edit_doctor.php?phone_number=<?php echo htmlspecialchars($row['phone_number']); ?>">Edit</a> |
                            <a href="doctors.php?delete_phone_number=<?php echo htmlspecialchars($row['phone_number']); ?>" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="5">No doctors found</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Add Doctor Button -->
    <div class="add-doctor">
        <p><a href="add_doctor.php">Add New Doctor</a></p>
    </div>

</div>

<?php
// Close the connection
$stmt->close();
$conn->close();
?>

</body>
</html>
