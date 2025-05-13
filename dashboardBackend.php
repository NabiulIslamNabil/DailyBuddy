<?php
require_once 'db.php'; // Database connection

$user_id = $_SESSION['user_id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
    $action = $_POST['action'];

    if ($action === 'add_task') {
        $title = $_POST['title'];
        $due_date = $_POST['due_date'];
        $priority = $_POST['priority'];
        $description = $_POST['description'] ?? '';
        $status = 'Pending';

        $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, due_date, priority, description, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $title, $due_date, $priority, $description, $status);
        $stmt->execute();
        $stmt->close();

        header("Location: dashboard.php");
        exit();
    }

    if ($action === 'add_expense') {
        $title = $_POST['title'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];
        $category = $_POST['category'];
        $notes = $_POST['notes'] ?? '';

        $stmt = $conn->prepare("INSERT INTO expenses (user_id, title, amount, date, category, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsss", $user_id, $title, $amount, $date, $category, $notes);
        $stmt->execute();
        $stmt->close();

        header("Location: dashboard.php");
        exit();
    }
}

// Fetch all tasks for a user
function fetchTasks($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tasks = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $tasks;
}

// Fetch all expenses for a user
function fetchExpenses($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY date DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $expenses = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $expenses;
}
?>
