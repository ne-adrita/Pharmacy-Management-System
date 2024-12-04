<?php
// customers.php

// Include the database connection file
include 'db_connect.php';

// Check if a search is being performed
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Query to fetch customers, searching by name or phone number
$sql = "SELECT * FROM customers WHERE name LIKE ? OR phone_number LIKE ?";
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
    <title>Customers</title>
    <style>
        /* Background image */
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

        h1 {
            text-align: center;
            color: #fff;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            padding: 5px 10px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .search-container {
            margin: 20px 0;
            text-align: center;
        }

        .search-container input {
            padding: 10px;
            width: 50%;
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

        .add-customer {
            text-align: center;
            margin-top: 20px;
        }

        .add-customer a {
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .add-customer a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="doctors.php">Doctors</a>
    <a href="cart.php">Cart</a>
    <a href="index.php">Logout</a>
</div>

<!-- Main content -->
<div class="container">
    <h1>Customer List</h1>

    <!-- Search Form -->
    <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search by Name or Phone Number" value="<?php echo htmlspecialchars($search_query); ?>" />
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Customers Table -->
    <table>
        <thead>
            <tr>
                <th>Phone Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
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
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td>
                            <a href="edit_customer.php?phone_number=<?php echo htmlspecialchars($row['phone_number']); ?>">Edit</a> |
                            <a href="delete_customer.php?phone_number=<?php echo htmlspecialchars($row['phone_number']); ?>" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="5">No customers found</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Add Customer Button -->
    <div class="add-customer">
        <p><a href="add_customer.php">Add New Customer</a></p>
    </div>

</div>

<?php
// Close the connection
$stmt->close();
$conn->close();
?>

</body>
</html>
