<?php
    $pageTitle = "About Us";
    $pageDescription = "Dough LEYcious - Learn more about us and our story.";
    $activePage = "aboutus.php";
    include("site_header.php");
?>

<main>
  <header class="page-banner">
    <div class="container">
      <span class="eyebrow">Our story</span>
      <h1>About Us</h1>
      <p>Get to know the people behind every delicious bite.</p>
    </div>
  </header>

  <section class="section">
    <div class="container story">
      <div class="media story__media" data-reveal>
        <img src="images/logo.png" alt="DoughLEYcious">
      </div>

      <div class="story__text" data-reveal>
        <span class="eyebrow">Who we are</span>
        <h2>Our Story</h2>
        <p>
          The goal of our business is to offer freshly baked, affordable, and high-quality cookies that bring joy to customers.
          It aims to build a strong and trusted brand, attract a large customer base through quality and creativity, and expand its reach both online and in local communities.
        </p>
        <p>
          Our business solves the common problem of stale or mass-produced pastries by offering pre-order, freshly baked cookies made specifically for each customer.
          Many customers want high-quality treats that are fresh, reliable, and suitable for gifting or special occasions.
          By baking per order, we ensure better taste, consistent quality, and a more personalized experience.
        </p>
        <p>
          Our business has faced challenges with sudden short stocks when unexpected increases in orders occur. Because we operate on a pre-order system to ensure all cookies are freshly baked, high demand at short notice can make it difficult to fulfill every request while maintaining consistent quality and freshness. Other challenges include coordinating orders efficiently, managing production schedules, and ensuring timely delivery, especially during peak seasons or special occasions. Despite these hurdles, we continuously improve our processes to meet customer expectations and provide a reliable, high-quality pastry experience.
        </p>
        <a href="menu.php" class="btn btn--primary">Order Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </section>

  <section class="section section--tint">
    <div class="container">
      <div class="section__head" data-reveal>
        <span class="eyebrow">Our values</span>
        <h2>What We Stand For</h2>
      </div>

      <div class="grid grid--values">
        <article class="card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-leaf"></i></div>
          <h3>Fresh Ingredients</h3>
          <p>We only use the freshest and finest ingredients in every batch we bake.</p>
        </article>
        <article class="card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-heart"></i></div>
          <h3>Made with Love</h3>
          <p>Every cookie is handcrafted with passion, care, and a whole lot of love.</p>
        </article>
        <article class="card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-handshake"></i></div>
          <h3>Customer First</h3>
          <p>Your satisfaction is our top priority. We bake to make you smile.</p>
        </article>
        <article class="card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-gem"></i></div>
          <h3>Quality Always</h3>
          <p>We never compromise on quality. Every cookie meets our high standards.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section__head" data-reveal>
        <span class="eyebrow">The baker</span>
        <h2>Meet the Owner</h2>
      </div>

      <article class="card owner" data-reveal>
        <div class="media owner__media">
          <img src="images\Owner of DoughLEYcious.jpg" alt="Kirsten Holley Munsayac">
        </div>
        <div class="owner__body">
          <h3>Kirsten Holley Munsayac</h3>
          <p class="owner__role">Head Baker &amp; Founder</p>
          <p>The heart and soul behind Dough LEYcious. Ms. Kirsten turns simple ingredients into magic.</p>
        </div>
      </article>
    </div>
  </section>

  <section class="cta">
    <div class="container" data-reveal>
      <h2>Ready to taste the magic?</h2>
      <p>Order now and experience the deliciousness of Dough LEYcious.</p>
      <a href="menu.php" class="btn btn--primary">Shop Now <i class="fa-solid fa-arrow-right"></i></a>
    </div>
  </section>
</main>

<?php include("site_footer.php"); ?>
