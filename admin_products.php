<?php
    session_start();
    require("database.php");

    if (!isset($_SESSION['username'])) {
        header("Location: admin_login.php");
        exit();
    }

    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);

    if (isset($_POST['update_price'])) {
        $product_id = (int) $_POST['product_id'];
        $price = (float) $_POST['price'];

        $con->query("UPDATE tbl_products SET price = $price WHERE product_id = $product_id");

        if ($isAjax) {
            echo "ok";
            exit();
        }
        header("Location: admin_products.php");
        exit();
    }

    if (isset($_POST['toggle_availability'])) {
        $product_id = (int) $_POST['product_id'];
        $available = ((int) $_POST['available'] == 1) ? 1 : 0;

        $con->query("UPDATE tbl_products SET is_available = $available WHERE product_id = $product_id");

        if ($isAjax) {
            echo "ok";
            exit();
        }
        header("Location: admin_products.php");
        exit();
    }

    if (isset($_POST['hide_product'])) {
        $product_id = (int) $_POST['product_id'];

        $con->query("UPDATE tbl_products SET is_hidden = 1 WHERE product_id = $product_id");
        header("Location: admin_products.php");
        exit();
    }

    if (isset($_POST['restore_product'])) {
        $product_id = (int) $_POST['product_id'];

        $con->query("UPDATE tbl_products SET is_hidden = 0 WHERE product_id = $product_id");
        header("Location: admin_products.php");
        exit();
    }

    if (isset($_POST['delete_product'])) {
        $product_id = (int) $_POST['product_id'];

        $con->query("DELETE FROM tbl_products WHERE product_id = $product_id");
        header("Location: admin_products.php");
        exit();
    }

    $execProducts = $con->query("SELECT * FROM tbl_products
                                 WHERE is_hidden = 0
                                 ORDER BY category, product_name");

    $execHidden = $con->query("SELECT * FROM tbl_products
                               WHERE is_hidden = 1
                               ORDER BY category, product_name");

    $products = array();
    $countAvailable = 0;
    $countUnavailable = 0;

    while ($row = $execProducts->fetch_assoc()) {
        $products[] = $row;
        if ((int) $row['is_available'] == 1) {
            $countAvailable++;
        } else {
            $countUnavailable++;
        }
    }

    $pageTitle = "Products";
    $pageSubtitle = "Set prices, switch items on or off, and control what customers see.";
    $activePage = "admin_products.php";
    include("admin_header.php");
?>

  <section class="stats" aria-label="Product summary">
    <article class="stat">
      <div class="icon-badge"><i class="fa-solid fa-boxes-stacked"></i></div>
      <div>
        <p>Menu items</p>
        <strong><?php echo count($products); ?></strong>
      </div>
    </article>
    <article class="stat">
      <div class="icon-badge"><i class="fa-solid fa-circle-check"></i></div>
      <div>
        <p>Available</p>
        <strong id="count-available"><?php echo $countAvailable; ?></strong>
      </div>
    </article>
    <article class="stat">
      <div class="icon-badge"><i class="fa-solid fa-ban"></i></div>
      <div>
        <p>Unavailable</p>
        <strong id="count-unavailable"><?php echo $countUnavailable; ?></strong>
      </div>
    </article>
    <article class="stat">
      <div class="icon-badge"><i class="fa-regular fa-eye-slash"></i></div>
      <div>
        <p>Hidden</p>
        <strong><?php echo $execHidden->num_rows; ?></strong>
      </div>
    </article>
  </section>

  <section class="panel">
    <div class="panel__bar">
      <h2 class="panel__title">Product Management <span class="count"><?php echo count($products); ?></span></h2>

      <label class="search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="search" id="product-search" placeholder="Search products" aria-label="Search products">
      </label>
    </div>

    <div class="pm-grid">
      <?php foreach ($products as $product) { ?>
        <?php
            $available = ((int) $product['is_available'] == 1);
            $name = htmlspecialchars($product['product_name']);
            $searchText = htmlspecialchars(strtolower($product['product_name'] . " " . $product['category']));
        ?>
        <article class="pm-card<?php if (!$available) { echo ' pm-card--off'; } ?>" data-search="<?php echo $searchText; ?>">
          <header class="pm-card__head">
            <h3><?php echo $name; ?></h3>
            <span class="chip"><?php echo htmlspecialchars($product['category']); ?></span>
          </header>

          <p class="pm-card__desc" title="<?php echo htmlspecialchars($product['description']); ?>"><?php echo htmlspecialchars($product['description']); ?></p>

          <div class="pm-card__controls">
            <form method="POST" action="admin_products.php" class="pm-field" data-ajax="price">
              <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
              <input type="hidden" name="update_price" value="1">
              <label for="price-<?php echo $product['product_id']; ?>">Price</label>
              <div class="field">
                <span>₱</span>
                <input type="number" class="input" id="price-<?php echo $product['product_id']; ?>" name="price" step="0.01" min="0" value="<?php echo $product['price']; ?>" data-autosubmit required>
              </div>
            </form>

            <form method="POST" action="admin_products.php" class="pm-field" data-ajax="toggle">
              <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
              <input type="hidden" name="toggle_availability" value="1">
              <input type="hidden" name="available" value="<?php echo $available ? 0 : 1; ?>">
              <span class="pm-field__label">Availability</span>
              <button type="submit" class="switch" role="switch" aria-checked="<?php echo $available ? 'true' : 'false'; ?>" aria-label="Toggle availability for <?php echo $name; ?>">
                <span class="switch__track"><span class="switch__knob"></span></span>
                <span class="switch__label"><?php echo $available ? 'Available' : 'Unavailable'; ?></span>
              </button>
            </form>
          </div>

          <footer class="pm-card__foot">
            <form method="POST" action="admin_products.php"
                  onsubmit="return confirm('Hide this item? You can restore it later.');">
              <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
              <button type="submit" name="hide_product" value="1" class="btn btn--ghost btn--sm">
                <i class="fa-regular fa-eye-slash"></i> Hide
              </button>
            </form>
            <form method="POST" action="admin_products.php"
                  onsubmit="return confirm('PERMANENTLY delete this item? This cannot be undone!');">
              <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
              <button type="submit" name="delete_product" value="1" class="btn btn--soft btn--sm">
                <i class="fa-regular fa-trash-can"></i> Delete
              </button>
            </form>
          </footer>
        </article>
      <?php } ?>
    </div>

    <div class="empty-state" id="product-empty"<?php if (count($products) > 0) { echo ' hidden'; } ?>>
      <i class="fa-regular fa-face-meh"></i>
      <p><?php echo count($products) > 0 ? "No products match your search." : "No products found."; ?></p>
    </div>
  </section>

  <section class="panel">
    <h2 class="panel__title">Hidden Products <span class="count"><?php echo $execHidden->num_rows; ?></span></h2>

    <?php if ($execHidden->num_rows > 0) { ?>
      <div class="hidden-list">
        <?php while ($product = $execHidden->fetch_assoc()) { ?>
          <article class="hidden-item">
            <div class="hidden-item__info">
              <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
              <p><?php echo htmlspecialchars($product['category']); ?> &middot; ₱<?php echo number_format($product['price'], 2); ?></p>
            </div>
            <div class="actions-cell">
              <form method="POST" action="admin_products.php">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button type="submit" name="restore_product" value="1" class="btn btn--primary btn--sm">
                  <i class="fa-solid fa-rotate-left"></i> Restore
                </button>
              </form>
              <form method="POST" action="admin_products.php"
                    onsubmit="return confirm('PERMANENTLY delete this item? This cannot be undone!');">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button type="submit" name="delete_product" value="1" class="btn btn--soft btn--sm">
                  <i class="fa-regular fa-trash-can"></i> Delete
                </button>
              </form>
            </div>
          </article>
        <?php } ?>
      </div>
    <?php } else { ?>
      <div class="empty-state">
        <i class="fa-regular fa-eye"></i>
        <p>No hidden products.</p>
      </div>
    <?php } ?>
  </section>

<?php include("admin_footer.php"); ?>
