<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB config
$host = 'localhost';
$dbname = 'dailybuddy';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $passwordInput = mysqli_real_escape_string($conn, $_POST['password']);

    // Find user by email
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $storedPassword = $user['password']; // stored plain password

        // Password check (plain text comparison)
        if ($storedPassword === $passwordInput) {
            // Login successful - set session
            $_SESSION['user_id'] = $user['user_id']; // Correct key
            $_SESSION['name'] = $user['name'];
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            // Wrong password
            header("Location: login.php?error=ভুল পাসওয়ার্ড");
            exit();
        }
    } else {
        // No user found
        header("Location: login.php?error=এই ইমেইলের কোন অ্যাকাউন্ট নেই");
        exit();
    }
}

$conn->close();
?>
