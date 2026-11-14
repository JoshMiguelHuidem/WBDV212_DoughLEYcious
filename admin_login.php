<?php
    session_start();

    if (isset($_SESSION['username'])) {
        header("Location: admin_dashboard.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <title>Admin Log In | Dough LEYcious</title>
  <script>
    try {
      var savedTheme = localStorage.getItem("theme");
      if (!savedTheme && window.matchMedia("(prefers-color-scheme: dark)").matches) {
        savedTheme = "dark";
      }
      if (savedTheme) {
        document.documentElement.setAttribute("data-theme", savedTheme);
      }
    } catch (e) {}
  </script>
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Ccircle cx='16' cy='16' r='16' fill='%23111111'/%3E%3Ccircle cx='16' cy='16' r='7' fill='%23fddde8'/%3E%3C/svg%3E">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="admin.css">
</head>
<body class="auth">
  <main class="auth__card panel">
    <a href="index.php" class="brand auth__brand">
      <span class="brand__mark"><i class="fa-solid fa-cookie-bite"></i></span>
      <span class="brand__name">Dough <em>LEYcious</em></span>
    </a>

    <h1>Admin Log In</h1>
    <p>Sign in to manage orders, products and inventory.</p>

    <form action="login_process.php" method="POST">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" autocomplete="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>
      </div>

      <button type="submit" class="btn btn--primary btn--block">
        <i class="fa-solid fa-right-to-bracket"></i> Log In
      </button>
    </form>
  </main>
</body>
</html>
