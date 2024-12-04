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
$totalPrice = 0;

// Fetch cart items for the logged-in user
$query = "SELECT c.cart_id, c.medicine_id, c.quantity, m.name, m.price, m.stock
          FROM cart c
          JOIN medicines m ON c.medicine_id = m.medicine_id
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch doctors' names and specialties for dropdown
$doctorQuery = "SELECT `name`, `specialist` FROM `doctors`";
$doctorStmt = $conn->prepare($doctorQuery);
$doctorStmt->execute();
$doctors = $doctorStmt->get_result();

// Handle checkout and customer selection
if (isset($_POST['checkout'])) {
    $phoneNumber = $_POST['phone_number'];
    $doctorName = $_POST['doctor']; // Capturing selected doctor

    // Check if the customer exists
    $customerQuery = "SELECT * FROM customers WHERE phone_number = ?";
    $customerStmt = $conn->prepare($customerQuery);
    $customerStmt->bind_param("s", $phoneNumber);
    $customerStmt->execute();
    $customerResult = $customerStmt->get_result();

    if ($customerResult->num_rows > 0) {
        $customer = $customerResult->fetch_assoc(); // Customer found
        $customerName = $customer['name'];
        $customerEmail = $customer['email'];
        $customerAddress = $customer['address'];
        
        // Proceed with stock update and cart clearance
        $success = true;
        
        // Loop through cart items to update stock and remove from cart
        while ($row = $result->fetch_assoc()) {
            $medicineId = $row['medicine_id'];
            $quantity = $row['quantity'];
            $stock = $row['stock'];

            // Decrease stock based on quantity purchased
            $newStock = $stock - $quantity;

            // Check if there's enough stock to complete the checkout
            if ($newStock < 0) {
                $success = false; // Not enough stock
                break; // Exit the loop if not enough stock
            }

            // Update stock in the medicines table
            $updateStockQuery = "UPDATE medicines SET stock = ? WHERE medicine_id = ?";
            $updateStockStmt = $conn->prepare($updateStockQuery);
            $updateStockStmt->bind_param("ii", $newStock, $medicineId);
            $updateStockStmt->execute();
        }

        // If there is enough stock, proceed with cart clearance
        if ($success) {
            // Clear the user's cart after successful checkout
            $clearCartQuery = "DELETE FROM cart WHERE user_id = ?";
            $clearCartStmt = $conn->prepare($clearCartQuery);
            $clearCartStmt->bind_param("i", $userId);
            $clearCartStmt->execute();

            // Include TCPDF library for PDF generation
            require_once('tcpdf/tcpdf.php');

            // Create PDF
            $pdf = new TCPDF();
            $pdf->AddPage();

            // Add header
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'Recipt', 0, 1, 'C');

            // Add customer details
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(100, 10, 'Customer Name: ' . $customerName, 0, 1);
            $pdf->Cell(100, 10, 'Email: ' . $customerEmail, 0, 1);
            $pdf->Cell(100, 10, 'Phone Number: ' . $phoneNumber, 0, 1);
            $pdf->Cell(100, 10, 'Address: ' . $customerAddress, 0, 1);
            $pdf->Ln();

            // Add doctor details
            $pdf->Cell(100, 10, 'Refered by (Doctor): ' . $doctorName, 0, 1);
            $pdf->Ln();

            // Add medicine details
            $pdf->Cell(40, 10, 'Medicine', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Quantity', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Price', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Subtotal', 1, 1, 'C');

            $result->data_seek(0); // Reset result pointer to the start
            while ($row = $result->fetch_assoc()) {
                $subtotal = $row['price'] * $row['quantity'];
                $totalPrice += $subtotal;

                $pdf->Cell(40, 10, $row['name'], 1, 0, 'C');
                $pdf->Cell(40, 10, $row['quantity'], 1, 0, 'C');
                $pdf->Cell(40, 10, '$' . number_format($row['price'], 2), 1, 0, 'C');
                $pdf->Cell(40, 10, '$' . number_format($subtotal, 2), 1, 1, 'C');
            }

            // Add total price
            $pdf->Cell(120, 10, 'Total: $' . number_format($totalPrice, 2), 0, 1, 'C');

            // Output PDF
            $pdf->Output('invoice.pdf', 'I');
            exit();
        } else {
            $errorMessage = "Not enough stock available for your order.";
        }
    } else {
        // Customer not found, prompt to add new customer
        $errorMessage = "Customer not found. Please add a new customer.";
    }
}

// Close the database connection
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .checkout-form {
            background-color: white;
            padding: 20px;
            margin: 20px auto;
            width: 300px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .checkout-form input,
        .checkout-form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .checkout-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
        }

        .checkout-form button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url('https://ezscrpt.com/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/11/AdobeStock_97410153.jpeg.webp');
            background-size: cover;
            background-position: center;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

<h1>Checkout</h1>

<?php if (isset($errorMessage)) { ?>
    <p class="error-message"><?php echo $errorMessage; ?></p>
<?php } ?>

<form method="POST" action="checkout.php" class="checkout-form">
    <label for="phone_number">Customer Phone Number</label>
    <input type="text" name="phone_number" id="phone_number" required>

    <label for="doctor">Select Doctor</label>
    <select name="doctor" id="doctor" required>
        <option value="">--Select Doctor--</option>
        <?php
        // Loop through doctors and create options for the dropdown
        while ($doctor = $doctors->fetch_assoc()) {
            echo "<option value=\"{$doctor['name']}\">{$doctor['name']} ({$doctor['specialist']})</option>";
        }
        ?>
    </select>

    <button type="submit" name="checkout">Proceed to Checkout</button>
</form>

</body>
</html>
