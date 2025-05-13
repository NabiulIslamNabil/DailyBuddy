<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Safely fetch username from session
$username = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';

// Include backend functions
require_once 'dashboardBackend.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - DailyBuddy</title>
  <link rel="stylesheet" href="dashboard.css" />
</head>
<body>

<!-- ✅ NAVBAR -->
<div id="navbar">
  <div class="logo">
    <img src="assets/images/logo.png" alt="Logo">
  </div>

  <div class="nav-right">
    <div class="profile">
      👤 <?php echo htmlspecialchars($username); ?>
      <a href="logout.php">Logout</a>
    </div>
    <button id="dark-mode-toggle">🌓</button>
  </div>
</div>

<!-- Sidebar + Main Layout -->
<div class="container">

  <!-- Sidebar -->
  <div class="sidebar">
    <ul>
      <li><a href="#" data-section="tasks" class="active">Tasks</a></li>
      <li><a href="#" data-section="expenses">Expenses</a></li>
      <li><a href="#" data-section="analytics">Analytics</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">

    <!-- Task Section -->
    <div class="section active" id="tasks">
      <h2>Daily Tasks</h2>
      <form method="POST" action="add_task.php">
        <input type="text" name="task_name" placeholder="Enter task..." required>
        <textarea name="task_description" placeholder="Task description"></textarea>
        <button type="submit">Add Task</button>
      </form>

      <ul class="list">
        <!-- Example task -->
        <li><strong>Buy groceries</strong><br><em>Milk, Bread, Eggs</em></li>
        <!-- You can populate from database using PHP here -->
      </ul>
    </div>

    <!-- Expense Section -->
    <div class="section" id="expenses">
      <h2>Daily Expenses</h2>
      <form method="POST" action="add_expense.php">
        <input type="text" name="expense_name" placeholder="Expense name..." required>
        <input type="number" step="0.01" name="amount" placeholder="Amount (e.g. 20.50)" required>
        <select name="category">
          <option value="food">Food</option>
          <option value="transport">Transport</option>
          <option value="utilities">Utilities</option>
          <option value="other">Other</option>
        </select>
        <button type="submit">Add Expense</button>
      </form>

      <ul class="list">
        <!-- Example expense -->
        <li><strong>Lunch</strong> - $8.50<br><em>Category: Food</em></li>
        <!-- Populate from DB -->
      </ul>
    </div>

    <!-- Analytics Section -->
    <div class="section" id="analytics">
      <h2>Analytics</h2>
      <p>📊 <strong>শীঘ্রই আসছে!</strong></p>
    </div>

  </div>
</div>

<!-- Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('dark-mode-toggle');

    // 🌙 Dark Mode
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
    }

    toggleButton.addEventListener('click', () => {
      document.body.classList.toggle('dark-mode');
      if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
      } else {
        localStorage.removeItem('darkMode');
      }
    });

    // 📁 Sidebar Tab Switching
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    const sections = document.querySelectorAll('.section');

    sidebarLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();

        // Remove all active classes
        sidebarLinks.forEach(l => l.classList.remove('active'));
        sections.forEach(s => s.classList.remove('active'));

        // Add active to clicked
        link.classList.add('active');
        const target = link.getAttribute('data-section');
        document.getElementById(target).classList.add('active');
      });
    });
  });
</script>

</body>
</html>
