<?php
    $pageTitle = "Checkout";
    $pageDescription = "Dough LEYcious - Review your order and complete your purchase.";
    $activePage = "";
    include("site_header.php");
?>

<main>
  <header class="page-banner">
    <div class="container">
      <span class="eyebrow">Step 3</span>
      <h1>Checkout</h1>
      <p>Review your order and complete your purchase.</p>
    </div>
  </header>

  <section class="page-section">
    <div class="container layout">

      <div class="panel">
        <h2>Order Summary</h2>

        <div id="checkout-items">
          <div class="empty-cart-message" id="empty-cart-msg">
            <div class="empty-icon"><i class="fa-solid fa-bag-shopping"></i></div>
            <h3>Your cart is empty</h3>
            <p>Add some delicious cookies before checking out.</p>
            <a href="menu.php" class="btn btn--primary">Browse Menu</a>
          </div>
        </div>

        <div class="order-totals" id="order-totals">
          <div class="total-row">
            <span>Subtotal</span>
            <span id="subtotal">₱0.00</span>
          </div>
          <div class="total-row">
            <span>Delivery Fee</span>
            <span id="delivery-fee">₱50.00</span>
          </div>
          <div class="total-row total-final">
            <span>Total</span>
            <span id="order-total">₱0.00</span>
          </div>
        </div>
      </div>

      <aside class="panel">
        <h2>Payment Information</h2>

        <div class="payment-methods">
          <h3>Send payment to</h3>
          <div class="payment-option">
            <div class="icon-badge"><i class="fa-solid fa-mobile-screen-button"></i></div>
            <div>
              <strong>G-cash</strong>
              <p>09366352711</p>
              <p>Mylene n.</p>
            </div>
          </div>
        </div>

        <div class="payment-reminder">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <p>Please send the exact amount and keep your reference number for proof of payment.</p>
        </div>

        <button type="button" class="btn btn--primary btn--block" id="proceed-btn" onclick="openCheckoutModal()" disabled>
          Proceed to Checkout
        </button>
      </aside>

    </div>
  </section>
</main>

<div class="modal-overlay" id="terms-modal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="terms-title">
    <button type="button" class="modal__close" onclick="closeTermsModal()" aria-label="Close">
      <i class="fa-solid fa-xmark"></i>
    </button>

    <div class="modal__header">
      <div class="icon-badge"><i class="fa-solid fa-file-contract"></i></div>
      <h2 id="terms-title">Terms and Conditions</h2>
      <p>Please agree before proceeding to checkout.</p>
    </div>

    <div class="terms-text">
      <p><strong>1.</strong> Your order will be processed based on the items currently in your cart.</p>
      <p><strong>2.</strong> Be aware that the delivery is only available in NCR.</p>
      <p><strong>3.</strong> Confirmation of payment proof may be required to finalize your order.</p>
      <p><strong>For complete details:</strong> <a href="terms.php" target="_blank" rel="noopener">Read our full Terms</a></p>
    </div>

    <label class="terms-agree">
      <input type="checkbox" id="terms-agree-checkbox">
      <span>I agree to the Terms and Conditions</span>
    </label>

    <div class="terms-error" id="terms-error">
      <i class="fa-solid fa-triangle-exclamation"></i> Please check the box to agree.
    </div>

    <button type="button" class="btn btn--primary btn--block" onclick="agreeAndProceed()">
      <i class="fa-solid fa-check"></i> Agree and Proceed
    </button>
  </div>
</div>

<div class="modal-overlay" id="checkout-modal">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="checkout-form-title">
    <button type="button" class="modal__close" onclick="closeCheckoutModal()" aria-label="Close">
      <i class="fa-solid fa-xmark"></i>
    </button>

    <div class="modal__header">
      <div class="icon-badge"><i class="fa-solid fa-bag-shopping"></i></div>
      <h2 id="checkout-form-title">Complete Your Order</h2>
      <p>Fill in your delivery details below.</p>
    </div>

    <div class="modal-summary" id="modal-summary"></div>

    <form id="checkout-form" action="place_order.php" method="POST" onsubmit="submitOrder(event)">
      <div id="cart-fields"></div>

      <div class="form-group">
        <label for="checkout-name">Full Name <span class="required">*</span></label>
        <input type="text" id="checkout-name" name="customer_name" placeholder="e.g. Arvic Pelinta" required>
      </div>

      <div class="form-group">
        <label for="checkout-phone">Phone Number <span class="required">*</span></label>
        <input type="tel" id="checkout-phone" name="phone" placeholder="e.g. 09123456789" required>
      </div>

      <div class="form-group">
        <label for="checkout-address">Delivery Address <span class="required">*</span></label>
        <textarea id="checkout-address" name="address" placeholder="Complete address (street, barangay, city)" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="checkout-date">Delivery Date <span class="required">*</span></label>
          <input type="date" id="checkout-date" name="delivery_date" required>
        </div>
        <div class="form-group">
          <label for="checkout-time">Delivery Time <span class="required">*</span></label>
          <input type="time" id="checkout-time" name="delivery_time" required>
        </div>
      </div>

      <div class="form-group">
        <label for="checkout-proof">Payment Reference No. <span class="required">*</span></label>
        <input type="text" id="checkout-proof" name="proof_ref" placeholder="e.g. GCash reference no." required>
      </div>

      <div class="form-group">
        <label for="checkout-notes">Other Messages (Optional)</label>
        <textarea id="checkout-notes" name="notes" placeholder="Any special requests or notes..."></textarea>
      </div>

      <button type="submit" class="btn btn--primary btn--block">
        <i class="fa-solid fa-lock"></i> Place Order
      </button>
    </form>
  </div>
</div>

<?php include("site_footer.php"); ?>
