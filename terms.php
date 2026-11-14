<?php
    $pageTitle = "Terms and Conditions";
    $pageDescription = "Dough LEYcious - Our policies for a smooth ordering experience.";
    $activePage = "";
    include("site_header.php");
?>

<main>
  <header class="page-banner">
    <div class="container">
      <span class="eyebrow">Policies</span>
      <h1>Terms and Conditions</h1>
      <p>Read our policies to ensure a smooth and delightful experience.</p>
    </div>
  </header>

  <section class="section">
    <div class="container">
      <div class="grid grid--terms">
        <article class="card" data-reveal>
          <span class="card__index">01</span>
          <h3>Orders &amp; Payment</h3>
          <p>All orders placed on this website are subject to availability and confirmation. <strong>Full payment is required before processing and preparing your cookies.</strong></p>
        </article>

        <article class="card" data-reveal>
          <span class="card__index">02</span>
          <h3>Delivery</h3>
          <p>We deliver within <strong>selected areas only</strong>. Delivery schedules may vary depending on location and order volume. We are not responsible for delays caused by weather, traffic, or other unforeseen events.</p>
        </article>

        <article class="card" data-reveal>
          <span class="card__index">03</span>
          <h3>Product Quality</h3>
          <p>All cookies are <strong>freshly baked and made to order</strong>. Since products are handmade, slight differences in size, shape, or appearance may occur but <strong>quality is always maintained</strong>.</p>
        </article>

        <article class="card" data-reveal>
          <span class="card__index">04</span>
          <h3>Refunds &amp; Cancellations</h3>
          <p>Orders <strong>cannot be cancelled once preparation has started</strong>. Refunds are only applicable for wrong items delivered or damaged products, reported within <strong>24 hours of receipt</strong>.</p>
        </article>
      </div>

      <div class="actions">
        <a href="menu.php" class="btn btn--primary">Continue Shopping <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </section>
</main>

<?php include("site_footer.php"); ?>
