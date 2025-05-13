<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$dbname = 'dailybuddy';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all fields exist
    if (!isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['confirmPassword'])) {
        die("Form fields missing.");
    }

    // Read user input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

    // Check passwords match
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);
    if ($result && $result->num_rows > 0) {
        die("Email already exists.");
    }

    // Insert data without hashing
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
    // Redirect to login.html with a success message
    header("Location: login.php?success=অ্যাকাউন্ট তৈরি সফল");
    exit();
    } else {
    echo "Error: " . $conn->error;
}

}

$conn->close();
?>
