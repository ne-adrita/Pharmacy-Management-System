<?php
include 'db_connect.php'; // Include your database connection

// Handle Sign Up
if (isset($_POST['signup'])) {
    $username = $_POST['signup-username'];
    $email = $_POST['signup-email'];
    $password = password_hash($_POST['signup-password'], PASSWORD_DEFAULT);  // Hash the password

    // Check if username or email already exists
    $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";  // Changed table name from sign_up to users
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username or Email already exists
        $signup_error = "Username or Email already exists!";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";  // Changed table name from sign_up to users
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $signup_success = "Signup successful! Please login.";
        } else {
            $signup_error = "Signup failed! Please try again.";
        }
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $username = $_POST['login-username'];
    $password = $_POST['login-password'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE username = ?";  // Changed table name from sign_up to users
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Login successful, start a session and redirect
        session_start();
        $_SESSION['user_id'] = $user['user_id'];  // Changed from 'id' to 'user_id'
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit(); // Ensure no further script execution after redirection
    } else {
        // Login failed, set an error message
        $login_error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Management System (PMS) - Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-image: url('https://ezscrpt.com/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/11/AdobeStock_97410153.jpeg.webp');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        h3 {
            color: #444;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 10px;
        }

        p a {
            color: #007BFF;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: rgba(106, 25, 25, 0.7);
            color: white;
            padding: 10px 20px;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Pharmacy Management System (PMS)</h2>
        <div class="form-container">
            <!-- Login Form -->
            <form method="POST" id="login-form">
                <h3>Login</h3>
                <input type="text" name="login-username" placeholder="Username" required>
                <input type="password" name="login-password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <p style="color: red;"><?php echo isset($login_error) ? $login_error : ''; ?></p>
                <p>Don't have an account? <a href="#" id="signup-link">Sign up</a></p>
            </form>

            <!-- Signup Form -->
            <form method="POST" id="signup-form" style="display:none;">
                <h3>Sign Up</h3>
                <input type="text" name="signup-username" placeholder="Username" required>
                <input type="email" name="signup-email" placeholder="Email" required>
                <input type="password" name="signup-password" placeholder="Password" required>
                <button type="submit" name="signup">Sign Up</button>
                <p style="color: green;"><?php echo isset($signup_success) ? $signup_success : ''; ?></p>
                <p>Already have an account? <a href="#" id="login-link">Login</a></p>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 Pharmacy Management System (PMS). All rights reserved.
    </footer>

    <!-- JavaScript to Toggle Forms -->
    <script>
        document.getElementById('signup-link').addEventListener('click', function() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('signup-form').style.display = 'block';
        });

        document.getElementById('login-link').addEventListener('click', function() {
            document.getElementById('signup-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        });
    </script>
</body>
</html>
