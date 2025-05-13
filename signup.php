<!DOCTYPE html>
<html lang="bn">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DailyBuddy Signup</title>
    <link rel="stylesheet" href="login.css" />
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

      .message {
        text-align: center;
        margin-top: 15px;
        font-weight: bold;
      }

      .message.error {
        color: red;
      }

      .message.success {
        color: green;
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

    <!-- Signup Form -->
    <div class="login-container" style="margin-top: 100px;">
      <h2>সাইন আপ</h2>
      <form action="signupBackend.php" method="POST">
        <input type="text" name="name" placeholder="পূর্ণ নাম" required />
        <input type="email" name="email" placeholder="ইমেইল আইডি" required />
        <input type="password" name="password" placeholder="পাসওয়ার্ড" required />
        <input type="password" name="confirmPassword" placeholder="পাসওয়ার্ড নিশ্চিত করুন" required />
        <button type="submit">সাইন আপ</button>
      </form>

      <div class="options" style="margin-top: 15px;">
        <p>ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="login.php">লগইন করুন</a></p>
      </div>

      <!-- Error and Success messages -->
      <div class="message-container">
        <div class="message error">
          <?php if (isset($_GET['error'])) echo htmlspecialchars($_GET['error']); ?>
        </div>
        <div class="message success">
          <?php if (isset($_GET['success'])) echo htmlspecialchars($_GET['success']); ?>
        </div>
      </div>
    </div>
  </body>
</html>
