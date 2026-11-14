<?php
    require("database.php");

    $execOther = $con->query("SELECT * FROM tbl_products
                              WHERE is_hidden = 0 AND is_available = 1 AND category <> 'Cookies'
                              ORDER BY product_id
                              LIMIT 4");

    $otherIcons = array(
        "Cakes" => "fa-cake-candles",
        "Bread" => "fa-bread-slice",
        "Chocolate Cravings" => "fa-cookie"
    );

    $pageTitle = "Your Cart";
    $pageDescription = "Dough LEYcious - Review your cart and checkout.";
    $activePage = "cart.php";
    include("site_header.php");
?>

<main>
  <header class="page-banner">
    <div class="container">
      <span class="eyebrow">Step 2</span>
      <h1>Your Cart</h1>
      <p>Review your order before checking out.</p>
    </div>
  </header>

  <section class="page-section cart-section">
    <div class="container layout">

      <div class="panel">
        <div class="panel__head">
          <h2>Order Summary</h2>
          <button type="button" class="btn btn--ghost btn--sm" onclick="clearCart()">
            <i class="fa-regular fa-trash-can"></i> Clear Cart
          </button>
        </div>

        <div id="cart-items">
          <div class="empty-cart">
            <div class="empty-cart-icon"><i class="fa-solid fa-bag-shopping"></i></div>
            <h3>Your cart is empty</h3>
            <p>Looks like you have not added any cookies yet.</p>
            <button type="button" class="btn btn--primary" onclick="location.href='menu.php'">Start Shopping</button>
          </div>
        </div>
      </div>

      <aside class="panel">
        <h2>Order Details</h2>

        <div class="summary-row">
          <span>Subtotal</span>
          <span id="subtotal-price">₱0</span>
        </div>

        <div class="summary-row">
          <span>Delivery Fee</span>
          <span id="delivery-fee">₱50</span>
        </div>

        <div class="summary-row summary-row--total">
          <span>Total</span>
          <span id="total-price">₱0</span>
        </div>

        <button type="button" class="btn btn--primary btn--block" onclick="checkout()">
          <i class="fa-solid fa-lock"></i> Checkout
        </button>
        <button type="button" class="btn btn--ghost btn--block" onclick="location.href='menu.php'">
          Continue Shopping
        </button>
      </aside>

    </div>
  </section>

  <?php if ($execOther->num_rows > 0) { ?>
    <section class="section section--tint">
      <div class="container">
        <div class="section__head" data-reveal>
          <span class="eyebrow">You might also like</span>
          <h2>Our Other Products</h2>
        </div>

        <div class="menu-grid">
          <?php while ($product = $execOther->fetch_assoc()) { ?>
            <?php
                $icon = isset($otherIcons[$product['category']]) ? $otherIcons[$product['category']] : "fa-cookie-bite";
                $jsName = htmlspecialchars(json_encode($product['product_name']), ENT_QUOTES);
            ?>
            <article class="menu-card menu-card--static" data-reveal>
              <div class="media menu-card__media"><i class="fa-solid <?php echo $icon; ?>"></i></div>
              <div class="menu-card__body">
                <h3 class="menu-card__title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                <div class="menu-card__foot">
                  <span class="menu-card__price">₱<?php echo number_format($product['price'], 2); ?></span>
                  <button type="button" class="btn btn--soft btn--sm" onclick="quickAddToCart(<?php echo $jsName; ?>, <?php echo (float) $product['price']; ?>, '')">
                    <i class="fa-solid fa-cart-plus"></i> Add
                  </button>
                </div>
              </div>
            </article>
          <?php } ?>
        </div>
      </div>
    </section>
  <?php } ?>
</main>

<?php include("site_footer.php"); ?>
