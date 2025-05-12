<?php
// signup.php

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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Password match check
    if ($password !== $confirmPassword) {
        // Redirect with error message
        header("Location: signup.html?error=পাসওয়ার্ড মিলছে না!");
        exit();
    }

    // Hash password for storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Redirect with error message
        header("Location: signup.html?error=এই ইমেইল আইডি ইতিমধ্যে নিবন্ধিত!");
        exit();
    }

    // Insert user data into the database
    $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: signup.html?success=সাইন আপ সফল হয়েছে। এখন লগইন করতে পারেন।");
    } else {
        // Redirect with error message
        header("Location: signup.html?error=সাইন আপ সফল হয়নি। আবার চেষ্টা করুন।");
    }

    $stmt->close();
    $conn->close();
}
?>
