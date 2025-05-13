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
        $storedPassword = $user['password']; // plain password (for simplicity)

        // Password check (no hashing, plain text comparison)
        if ($storedPassword === $passwordInput) {
            // Login successful - set session
            $_SESSION['user_id'] = $user['user_id']; // Use correct 'user_id'
            $_SESSION['name'] = $user['name'];
            header("Location: dashboard.php"); // redirect to user dashboard
            exit();
        } else {
            header("Location: login.php?error=ভুল পাসওয়ার্ড");
            exit();
        }
    } else {
        header("Location: login.php?error=এই ইমেইলের কোন অ্যাকাউন্ট নেই");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="bn">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DailyBuddy Login</title>
    <link rel="stylesheet" href="login.css" />
    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>

  <body>
    <!-- Navbar -->
    <nav id="navbar">
      <div class="logo">
        <img src="assets/images/logo.png" alt="Logo" />
      </div>
      <a href="login.php">লগইন</a>
      <a href="signup.php">সাইন আপ</a>
    </nav>

    <style>
      /* Navbar Styling */
      nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: hsla(0, 0%, 85%, 0.8);
        border-bottom: 1px solid #ddd;
        display: flex;
        align-items: center;
        padding: 10px 20px;
        z-index: 1000;
        transform: translateY(0);
        transition: transform 0.3s ease-in-out;
      }

      nav .logo img {
        height: 60px;
        width: auto;
        margin-right: 20px;
      }

      nav a {
        color: black;
        padding: 14px 20px;
        text-decoration: none;
        text-align: center;
        font-weight: bold;
        position: relative;
        overflow: hidden;
      }

      nav a::before {
        content: "";
        position: absolute;
        width: 0;
        height: 2px;
        background-color: #2a73d3;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        transition: width 0.3s ease-in-out;
      }

      nav a:hover::before {
        width: 100%;
      }

      nav a:hover {
        color: #3378c7;
      }
    </style>

    <script>
      // JavaScript for dynamic navbar behavior
      let lastScrollY = window.scrollY;
      const navbar = document.getElementById('navbar');
      let isMouseNearTop = false;

      // Hide or show navbar on scroll
      window.addEventListener('scroll', () => {
        if (window.scrollY > lastScrollY && !isMouseNearTop) {
          navbar.style.transform = 'translateY(-100%)';
        } else {
          navbar.style.transform = 'translateY(0)';
        }
        lastScrollY = window.scrollY;
      });

      // Detect mouse near the top of the screen
      window.addEventListener('mousemove', (event) => {
        if (event.clientY <= 50) {
          navbar.style.transform = 'translateY(0)';
          isMouseNearTop = true;
        } else {
          isMouseNearTop = false;
        }
      });
    </script>

    <!-- Login Form -->
    <div class="login-container">
      <h2>লগইন</h2>
      <form action="loginBackend.php" method="POST">
        <input type="email" name="email" placeholder="ইমেইল আইডি" required />
        <input type="password" name="password" placeholder="পাসওয়ার্ড" required />
        <button type="submit" style="margin-top: 2px;">লগইন</button>
      </form>

      <div class="options" style="margin-top: 15px;">
        <p>অ্যাকাউন্ট নেই? <a href="signup.php">সাইন আপ করুন</a></p>
      </div>

      <!-- PHP error/success messages -->
      <?php if (isset($_GET['error'])): ?>
        <script>
          Swal.fire({
            icon: 'error',
            title: 'ত্রুটি',
            text: '<?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?>',
            confirmButtonText: 'ঠিক আছে'
          });
        </script>
      <?php elseif (isset($_GET['success'])): ?>
        <script>
          Swal.fire({
            icon: 'success',
            title: 'সফল!',
            text: '<?php echo htmlspecialchars($_GET['success'], ENT_QUOTES); ?>',
            confirmButtonText: 'ঠিক আছে'
          });
        </script>
      <?php endif; ?>
    </div>
  </body>
</html>
