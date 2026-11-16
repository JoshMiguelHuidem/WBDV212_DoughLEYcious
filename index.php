<?php
    $pageTitle = "Home";
    $pageDescription = "Dough LEYcious - Freshly baked cookies made to order. Order now!";
    $activePage = "index.php";
    include("site_header.php");
?>

<main>
  <header class="hero">
    <div class="hero__slides" aria-hidden="true">
      <div class="slide active"><img src="images/header.jpg" alt="header image"></div>
      <div class="slide"><img src="images/header 2.png" alt="header image 2"></div>
      <div class="slide"><img src="images/header3.png" alt="header image 3"></div>
    </div>
    <div class="hero__overlay"></div>

    <div class="container hero__content">
      <span class="eyebrow">Freshly baked to order</span>
      <h1>Dough <em>LEYcious</em></h1>
      <p>Baked fresh, delivered with love. Cookies, cakes and bread made for every occasion.</p>
      <div class="hero__actions">
        <button type="button" class="btn btn--primary" onclick="goToOrderPage()">
          Start Ordering <i class="fa-solid fa-arrow-right"></i>
        </button>
        <a href="menu.php" class="btn btn--ghost">View Menu</a>
      </div>
    </div>
  </header>

  <div class="marquee" aria-hidden="true">
    <div class="marquee__track">
      <span>Baked fresh <i class="fa-solid fa-star"></i> Made to order <i class="fa-solid fa-star"></i> Delivered across NCR <i class="fa-solid fa-star"></i> Gift ready <i class="fa-solid fa-star"></i></span>
      <span>Baked fresh <i class="fa-solid fa-star"></i> Made to order <i class="fa-solid fa-star"></i> Delivered across NCR <i class="fa-solid fa-star"></i> Gift ready <i class="fa-solid fa-star"></i></span>
    </div>
  </div>

  <section class="section">
    <div class="container">
      <div class="section__head" data-reveal>
        <span class="eyebrow">Why choose us</span>
        <h2>Small batches, big flavor</h2>
        <p>Every order is baked fresh for you, never mass produced.</p>
      </div>

      <div class="grid grid--features">
        <article class="card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-cookie-bite"></i></div>
          <h3>Freshly Baked</h3>
          <p>Made fresh every day with the finest ingredients.</p>
        </article>
        <article class="card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-heart"></i></div>
          <h3>Made with Love</h3>
          <p>Every cookie is baked with passion and care.</p>
        </article>
        <article class="card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-truck-fast"></i></div>
          <h3>Fast Delivery</h3>
          <p>Get your cookies delivered straight to your door.</p>
        </article>
        <article class="card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-award"></i></div>
          <h3>Top Quality</h3>
          <p>Only the best ingredients make it into our cookies.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="cta">
    <div class="container" data-reveal>
      <h2>Ready to taste the difference?</h2>
      <p>Choose your box, pick your favorites and we will bake them fresh.</p>
      <button type="button" class="btn btn--primary" onclick="goToOrderPage()">
        Start Ordering <i class="fa-solid fa-arrow-right"></i>
      </button>
    </div>
  </section>
</main>

<?php include("site_footer.php"); ?>
