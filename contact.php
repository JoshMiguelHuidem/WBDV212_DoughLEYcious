<?php
    $pageTitle = "Contact Us";
    $pageDescription = "Contact Dough LEYcious - Send us a message.";
    $activePage = "contact.php";
    include("site_header.php");
?>

<main>
  <header class="page-banner">
    <div class="container">
      <span class="eyebrow">Get in touch</span>
      <h1>Contact Us</h1>
      <p>We would love to hear from you.</p>
    </div>
  </header>

  <section class="section">
    <div class="container">
      <div class="section__head" data-reveal>
        <h2>Get In Touch</h2>
        <p>Have a question, custom order request, or just want to say hi? Reach out through any of the options below.</p>
      </div>

      <div class="grid grid--info">
        <article class="card info-card" data-reveal>
          <div class="icon-badge"><i class="fa-regular fa-envelope"></i></div>
          <div>
            <h3>Email Us</h3>
            <a href="mailto:k.munsayac1109@gmail.com">k.munsayac1109@gmail.com</a>
          </div>
        </article>

        <article class="card info-card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-location-dot"></i></div>
          <div>
            <h3>Delivery</h3>
            <p>Available for delivery in your area</p>
          </div>
        </article>

        <article class="card info-card" data-reveal>
          <div class="icon-badge"><i class="fa-regular fa-clock"></i></div>
          <div>
            <h3>Response Time</h3>
            <p>We reply within 24 hours</p>
          </div>
        </article>

        <article class="card info-card" data-reveal>
          <div class="icon-badge"><i class="fa-solid fa-share-nodes"></i></div>
          <div>
            <h3>Follow Us</h3>
            <div class="socials">
              <a href="https://www.facebook.com/profile.php?id=61567054065532" target="_blank" rel="noopener" aria-label="Facebook">
                <i class="fa-brands fa-facebook-f"></i> Facebook
              </a>
              <a href="https://www.instagram.com/dough_leycious/" target="_blank" rel="noopener" aria-label="Instagram">
                <i class="fa-brands fa-instagram"></i> Instagram
              </a>
            </div>
          </div>
        </article>
      </div>

      <div class="actions" data-reveal>
        <a href="mailto:k.munsayac1109@gmail.com" class="btn btn--primary">
          <i class="fa-regular fa-envelope"></i> Email Us Now
        </a>
        <a href="menu.php" class="btn btn--ghost">View Our Menu</a>
      </div>
    </div>
  </section>
</main>

<?php include("site_footer.php"); ?>
