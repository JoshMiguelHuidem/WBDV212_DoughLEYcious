<?php
    $pageTitle = isset($pageTitle) ? $pageTitle : "Dashboard";
    $activePage = isset($activePage) ? $activePage : "";
    $pageSubtitle = isset($pageSubtitle) ? $pageSubtitle : "Manage your bakery from one place.";

    $adminNav = array(
        "admin_dashboard.php" => array("Orders", "fa-clipboard-list"),
        "admin_products.php" => array("Products", "fa-boxes-stacked")
    );
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <title><?php echo htmlspecialchars($pageTitle); ?> | Dough LEYcious Admin</title>
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
<body class="admin">

<header class="site-header">
  <nav class="navbar" aria-label="Admin navigation">
    <div class="navbar-inner">
      <a href="admin_dashboard.php" class="brand">
        <span class="brand__mark"><i class="fa-solid fa-cookie-bite"></i></span>
        <span class="brand__name">Dough <em>LEYcious</em></span>
      </a>

      <ul class="nav-links" id="nav-links">
        <?php foreach ($adminNav as $file => $item) { ?>
          <li>
            <a href="<?php echo $file; ?>"<?php if ($activePage == $file) { echo ' aria-current="page"'; } ?>><?php echo $item[0]; ?></a>
          </li>
        <?php } ?>
      </ul>

      <div class="nav-actions">
        <button type="button" class="theme-toggle" aria-label="Toggle dark mode" title="Toggle dark mode">
          <i class="fa-solid fa-moon"></i>
          <i class="fa-solid fa-sun"></i>
        </button>

        <a href="destroy_session.php" class="nav-btn">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span class="nav-cart__label">Logout</span>
        </a>

        <button class="nav-toggle-btn" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="nav-links">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>
    </div>
  </nav>
</header>

<main class="admin-main container">
  <section class="admin-hero">
    <div>
      <p class="admin-hero__date"><i class="fa-regular fa-calendar"></i> <?php echo date("l, F j, Y"); ?></p>
      <h1>Welcome, Kirsten!</h1>
      <p class="admin-hero__text"><?php echo $pageSubtitle; ?></p>
    </div>
    <i class="fa-solid fa-cookie-bite admin-hero__icon" aria-hidden="true"></i>
  </section>
