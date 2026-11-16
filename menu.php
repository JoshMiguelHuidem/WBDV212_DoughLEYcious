<?php
    require("database.php");

    $categoryOrder = array("Cookies", "Cakes", "Bread", "Chocolate Cravings");
    $categoryIcons = array(
        "Cookies" => "fa-cookie-bite",
        "Cakes" => "fa-cake-candles",
        "Bread" => "fa-bread-slice",
        "Chocolate Cravings" => "fa-cookie"
    );

    $exec = $con->query("SELECT * FROM tbl_products WHERE is_hidden = 0 ORDER BY product_id");

    $grouped = array();
    while ($row = $exec->fetch_assoc()) {
        $grouped[$row['category']][] = $row;
    }

    $categories = array();
    foreach ($categoryOrder as $name) {
        if (isset($grouped[$name])) {
            $categories[] = $name;
        }
    }
    foreach (array_keys($grouped) as $name) {
        if (!in_array($name, $categories)) {
            $categories[] = $name;
        }
    }

    $pageTitle = "Menu";
    $pageDescription = "Dough LEYcious - Freshly baked cookies, cakes and bread made with love.";
    $activePage = "menu.php";
    include("site_header.php");
?>

<main>
  <header class="page-banner">
    <div class="container">
      <span class="eyebrow">Freshly baked</span>
      <h1>Our Menu</h1>
      <p>Select any item to see the details and add it to your cart.</p>
    </div>
  </header>

  <div class="container">
    <section class="menu__intro" data-reveal>
      <h2>Explore Our Bakes</h2>
      <p>Freshly baked, made with the finest ingredients.</p>
      <a href="cart.php" class="btn btn--ghost btn--sm"><i class="fa-solid fa-bag-shopping"></i> View Cart</a>
    </section>

    <?php if (count($categories) == 0) { ?>
      <p class="menu__empty">Our menu is being updated. Please check back soon.</p>
    <?php } ?>

    <?php foreach ($categories as $category) { ?>
      <?php
          $icon = isset($categoryIcons[$category]) ? $categoryIcons[$category] : "fa-cookie-bite";
          $total = count($grouped[$category]);
      ?>
      <section class="menu__category">
        <h3 class="category-title" data-reveal>
          <i class="fa-solid <?php echo $icon; ?>"></i>
          <?php echo htmlspecialchars($category); ?>
          <span><?php echo $total . ($total == 1 ? " flavor" : " flavors"); ?></span>
        </h3>

        <div class="menu-grid">
          <?php foreach ($grouped[$category] as $product) { ?>
            <?php
                $unavailable = ((int) $product['stock'] <= 0);
                $name = htmlspecialchars($product['product_name'], ENT_QUOTES);
                $description = htmlspecialchars($product['description'] ?? '', ENT_QUOTES);
                $priceText = number_format($product['price'], 2);
                $img = htmlspecialchars($product['image'] ?? '', ENT_QUOTES);
            ?>
            <article class="menu-card<?php if ($unavailable) { echo ' menu-card--off'; } ?>"<?php if (!$unavailable) { echo ' onclick="openIngredientModal(this)"'; } ?> data-reveal
              data-name="<?php echo $name; ?>"
              data-tagline=""
              data-price="₱<?php echo $priceText; ?>"
              data-img="<?php echo $img; ?>"
              data-description="<?php echo $description; ?>">
              <div class="media menu-card__media">
                <?php if (!empty($img)) { ?>
                  <img src="<?php echo $img; ?>" alt="<?php echo $name; ?>" style="width:100%; height:100%; object-fit:cover;">
                <?php } else { ?>
                  <i class="fa-solid <?php echo $icon; ?>"></i>
                <?php } ?>
              </div>
              <div class="menu-card__body">
                <h3 class="menu-card__title"><?php echo $name; ?></h3>
                <p class="menu-card__desc"><?php echo $description; ?></p>
                <div class="menu-card__foot">
                  <span class="menu-card__price">₱<?php echo $priceText; ?></span>
                  <?php if ($unavailable) { ?>
                    <button type="button" class="btn btn--soft btn--sm" disabled>Unavailable</button>
                  <?php } else { ?>
                    <button type="button" class="btn btn--soft btn--sm" onclick="event.stopPropagation(); openIngredientModal(this.closest('.menu-card'))">View Details</button>
                  <?php } ?>
                </div>
              </div>
            </article>
          <?php } ?>
        </div>
      </section>
    <?php } ?>
  </div>
</main>

<div class="modal-overlay" id="ingredientModal">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalName">
    <button type="button" class="modal-close-btn" onclick="closeIngredientModal()" aria-label="Close">
      <i class="fa-solid fa-xmark"></i>
    </button>

    <div class="media modal__media">
      <i class="fa-solid fa-cookie-bite"></i>
      <img alt="" class="modal-cookie-img" id="modalImg" hidden>
    </div>

    <h2 id="modalName"></h2>
    <p class="modal-tagline" id="modalTagline"></p>
    <span id="modalPrice"></span>
    <p id="modalDescription"></p>

    <ul class="modal-ingredients-list" id="modalIngredients"></ul>

    <div class="quantity">
      <button type="button" class="minus" aria-label="Decrease quantity">&minus;</button>
      <input type="number" value="0" min="0" aria-label="Quantity">
      <button type="button" class="plus" aria-label="Increase quantity">+</button>
    </div>

    <div class="modal__actions">
      <button type="button" class="btn btn--primary btn--block add-to-cart-btn" onclick="addModalToCart()">
        <i class="fa-solid fa-cart-plus"></i> Add to Cart
      </button>
      <button type="button" class="btn btn--ghost btn--block modal-close-action" onclick="closeIngredientModal()">
        <i class="fa-solid fa-arrow-left"></i> Back to Menu
      </button>
    </div>
  </div>
</div>

<?php include("site_footer.php"); ?>
