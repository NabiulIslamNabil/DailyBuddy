<?php
// login.php

session_start();

// Database connection
$host = 'localhost';
$dbname = 'dailybuddy'; // Your DB name
$username = 'root'; // Your DB username
$password = 'root'; // Your DB password

// Create a new connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start session and set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            // Redirect to dashboard or home page
            header("Location: dashboard.php"); // Adjust to your homepage/dashboard
            exit();
        } else {
            header("Location: login.html?error=পাসওয়ার্ড ভুল।");
            exit();
        }
    } else {
        header("Location: login.html?error=ইমেইল আইডি পাওয়া যায়নি।");
        exit();
    }
}

$conn->close();
?>
