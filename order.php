<?php
    $pageTitle = "Choose Your Box";
    $pageDescription = "Dough LEYcious - Choose your cookie box size and order now.";
    $activePage = "";
    include("site_header.php");
?>

<main>
  <header class="page-banner">
    <div class="container">
      <span class="eyebrow">Step 1</span>
      <h1>Choose Your Box</h1>
      <p>Pick the perfect box size for you.</p>
    </div>
  </header>

  <section class="section">
    <div class="container">
      <div class="section__head" data-reveal>
        <h2>Select Your Box Size</h2>
        <p>All boxes are freshly baked and packed with care.</p>
      </div>

      <div class="grid grid--boxes">
        <article class="card box" onclick="selectOrder('6pcs')" data-reveal>
          <span class="box__tag">Starter</span>
          <div class="icon-badge"><i class="fa-solid fa-box-open"></i></div>
          <h3>Box of 6</h3>
          <p>Perfect for personal treats or a small gift for someone special.</p>
          <ul class="box__perks">
            <li><i class="fa-solid fa-check"></i> 6 freshly baked cookies</li>
            <li><i class="fa-solid fa-check"></i> Mix and match flavors</li>
            <li><i class="fa-solid fa-check"></i> Gift-ready packaging</li>
          </ul>
          <button type="button" class="btn btn--ghost btn--block">Select Box of 6</button>
        </article>

        <article class="card box box--featured" onclick="selectOrder('8pcs')" data-reveal>
          <span class="box__tag">Most Popular</span>
          <div class="icon-badge"><i class="fa-solid fa-gift"></i></div>
          <h3>Box of 8</h3>
          <p>Our most popular choice. Great for sharing with friends and family.</p>
          <ul class="box__perks">
            <li><i class="fa-solid fa-check"></i> 8 freshly baked cookies</li>
            <li><i class="fa-solid fa-check"></i> Mix and match flavors</li>
            <li><i class="fa-solid fa-check"></i> Gift-ready packaging</li>
          </ul>
          <button type="button" class="btn btn--soft btn--block">Select Box of 8</button>
        </article>

        <article class="card box" onclick="selectOrder('12pcs')" data-reveal>
          <span class="box__tag">Best Value</span>
          <div class="icon-badge"><i class="fa-solid fa-boxes-stacked"></i></div>
          <h3>Box of 12</h3>
          <p>The ultimate cookie experience. Perfect for parties and celebrations.</p>
          <ul class="box__perks">
            <li><i class="fa-solid fa-check"></i> 12 freshly baked cookies</li>
            <li><i class="fa-solid fa-check"></i> Mix and match flavors</li>
            <li><i class="fa-solid fa-check"></i> Gift-ready packaging</li>
            <li><i class="fa-solid fa-check"></i> Free greeting card (optional)</li>
          </ul>
          <button type="button" class="btn btn--ghost btn--block">Select Box of 12</button>
        </article>

        <article class="card box" onclick="selectOrder('per piece')" data-reveal>
          <span class="box__tag">Flexible</span>
          <div class="icon-badge"><i class="fa-solid fa-cookie-bite"></i></div>
          <h3>Per Piece</h3>
          <p>Order cookies individually. Perfect if you only want a few.</p>
          <ul class="box__perks">
            <li><i class="fa-solid fa-check"></i> No minimum quantity</li>
            <li><i class="fa-solid fa-check"></i> Choose any flavor</li>
            <li><i class="fa-solid fa-check"></i> Budget-friendly option</li>
          </ul>
          <button type="button" class="btn btn--ghost btn--block">Select Per Piece</button>
        </article>
      </div>
    </div>
  </section>

  <section class="section section--tint">
    <div class="container">
      <div class="section__head" data-reveal>
        <span class="eyebrow">Simple process</span>
        <h2>How It Works</h2>
      </div>

      <div class="grid grid--steps">
        <article class="card step" data-reveal>
          <span class="step__number">1</span>
          <div class="icon-badge"><i class="fa-solid fa-box-open"></i></div>
          <h3>Choose Your Box</h3>
          <p>Pick the box size that is right for you.</p>
        </article>
        <article class="card step" data-reveal>
          <span class="step__number">2</span>
          <div class="icon-badge"><i class="fa-solid fa-cookie-bite"></i></div>
          <h3>Pick Your Cookies</h3>
          <p>Select your favorite cookie flavors.</p>
        </article>
        <article class="card step" data-reveal>
          <span class="step__number">3</span>
          <div class="icon-badge"><i class="fa-solid fa-bag-shopping"></i></div>
          <h3>Add to Cart</h3>
          <p>Review your order and add to cart.</p>
        </article>
        <article class="card step" data-reveal>
          <span class="step__number">4</span>
          <div class="icon-badge"><i class="fa-solid fa-truck-fast"></i></div>
          <h3>Enjoy</h3>
          <p>We bake fresh and deliver to your door.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="note" data-reveal>
        <i class="fa-solid fa-circle-info"></i>
        <p><strong>Note:</strong> After selecting your box size, you will be taken to our menu to pick your cookie flavors. Mix and match as you like.</p>
      </div>
    </div>
  </section>
</main>

<?php include("site_footer.php"); ?>
