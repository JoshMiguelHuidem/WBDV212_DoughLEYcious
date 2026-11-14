<?php
    $pageTitle = isset($pageTitle) ? $pageTitle : "Dough LEYcious";
    $pageDescription = isset($pageDescription) ? $pageDescription : "Freshly baked cookies, cakes and bread made to order.";
    $activePage = isset($activePage) ? $activePage : "";

    $navItems = array(
        "index.php" => "Home",
        "menu.php" => "Menu",
        "aboutus.php" => "About Us",
        "contact.php" => "Contact"
    );
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
  <title><?php echo htmlspecialchars($pageTitle); ?> | Dough LEYcious</title>
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
</head>
<body>

<header class="site-header">
  <nav class="navbar" aria-label="Main navigation">
    <div class="navbar-inner">
      <a href="index.php" class="brand">
        <span class="brand__mark"><i class="fa-solid fa-cookie-bite"></i></span>
        <span class="brand__name">Dough <em>LEYcious</em></span>
      </a>

      <ul class="nav-links" id="nav-links">
        <?php foreach ($navItems as $file => $label) { ?>
          <li>
            <a href="<?php echo $file; ?>"<?php if ($activePage == $file) { echo ' aria-current="page"'; } ?>><?php echo $label; ?></a>
          </li>
        <?php } ?>
      </ul>

      <div class="nav-actions">
        <button type="button" class="theme-toggle" aria-label="Toggle dark mode" title="Toggle dark mode">
          <i class="fa-solid fa-moon"></i>
          <i class="fa-solid fa-sun"></i>
        </button>

        <a href="cart.php" class="nav-cart" aria-label="View cart"<?php if ($activePage == "cart.php") { echo ' aria-current="page"'; } ?>>
          <i class="fa-solid fa-bag-shopping"></i>
          <span class="nav-cart__label">Cart</span>
          <span class="nav-cart__count" id="cart-count">0</span>
        </a>

        <button class="nav-toggle-btn" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="nav-links">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>
    </div>
  </nav>
</header>
