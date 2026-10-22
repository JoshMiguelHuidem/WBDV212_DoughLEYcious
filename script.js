// ================= cart counter =================
// kinukuha nito yung cart data sa localStorage, binibilang total quantity ng items.
function updateCartCount() {

  // Read cart items from localStorage.
  const cart = JSON.parse(localStorage.getItem("cart")) || [];

  // Compute total quantity by summing each item's qty.
  const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);

  const counters = document.querySelectorAll("#cart-count");
  counters.forEach((counter) => {
    counter.textContent = totalQty;
  });
}


// ================= carousel (header)=================
let slides = document.querySelectorAll(".slide");
let current = 0;

function showSlide(index) {
  slides.forEach((slide) => slide.classList.remove("active"));
  slides[index].classList.add("active");
}

function nextSlide() {
  current = (current + 1) % slides.length;
  showSlide(current);
}

setInterval(nextSlide, 3000);

// ================= Open ingredient modal (for menu) =================
let selectedModalCard = null;

function openIngredientModal(card) {
  const modal = document.getElementById("ingredientModal");
  if (!modal) return;

  selectedModalCard = card;

  const name = card.getAttribute("data-name");
  const tagline = card.getAttribute("data-tagline");
  const price = card.getAttribute("data-price");
  const img = card.getAttribute("data-img");
  const description = card.getAttribute("data-description");
  const ingredients = [];


  document.getElementById("modalImg").src = img;
  document.getElementById("modalImg").alt = name;
  document.getElementById("modalName").textContent = name;
  document.getElementById("modalTagline").textContent = tagline;
  document.getElementById("modalPrice").textContent = price;
  document.getElementById("modalDescription").textContent = description;

  const qtyInput = modal.querySelector(".quantity input[type='number']");
  if (qtyInput) qtyInput.value = 0;

  const list = document.getElementById("modalIngredients");
  list.innerHTML = "";
  ingredients.forEach((item) => {
    const li = document.createElement("li");
    li.textContent = item.trim();
    list.appendChild(li);
  });

  modal.classList.add("active");
  document.body.style.overflow = "hidden";
}


// ================= Close ingredient modal (for menu) =================
function closeIngredientModal() {
  const modal = document.getElementById("ingredientModal");
  if (!modal) return;
  modal.classList.remove("active");
  document.body.style.overflow = "";
}

// ================= Modal quantity and add to cart =================
function addModalToCart() {
  const modal = document.getElementById("ingredientModal");
  if (!modal) return;
  if (!selectedModalCard) {
    showToast("⚠️ Please select an item first!");
    return;
  }

  const qtyInput = modal.querySelector(".quantity input[type='number']");
  const qty = Math.max(0, parseInt(qtyInput ? qtyInput.value : "0", 10) || 0);

  if (qty <= 0) {
    showToast("⚠️ Please select a quantity first!");
    return;
  }

  const name = selectedModalCard.getAttribute("data-name");
  const priceText = selectedModalCard.getAttribute("data-price") || "₱0";
  const price = parseFloat(priceText.replace(/[^0-9.]/g, "")) || 0;
  const img = selectedModalCard.getAttribute("data-img") || "";

  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let existing = cart.find((item) => item.name === name);

  const maxQty = getMaxQtyForOrderType();
  if (maxQty !== Infinity) {
    const currentQty = getCartTotalQty();
    if (currentQty + qty > maxQty) {
      showBoxLimitToast();
      showToast(`Cannot add more than ${maxQty} cookies for this box.`);
      return;
    }
  }

  if (existing) {
    existing.qty += qty;
  } else {
    cart.push({ name, price, qty, imgSrc: img });
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();


  if (document.getElementById("cart-items")) {
    displayCart();
  }

  if (qtyInput) qtyInput.value = 0;

  showToast(`Added to cart: ${name}`, { type: "success" });
}


// ================= notification =================
function showToast(message, options = {}) {
  let toast = document.getElementById("toast");

  if (!toast) {
    toast = document.createElement("div");
    toast.id = "toast";
    toast.className = "toast";
    document.body.appendChild(toast);
  }

  const isSuccess = options?.type === "success";

  toast.classList.remove("toast-success");
  toast.classList.add("toast");

  if (isSuccess) {
    toast.classList.add("toast-success");
    toast.innerHTML = `<span class="toast-success-text">✅ ${message}</span>`;
  } else {
    toast.textContent = message;
  }

  toast.classList.add("show");

  setTimeout(() => {
    toast.classList.remove("show");
  }, 3000);
}


// ================= confirmation toast =================
function showConfirmToast(message, onConfirm) {
  const existing = document.getElementById("confirm-toast");
  if (existing) existing.remove();

  const overlay = document.createElement("div");
  overlay.id = "confirm-toast";
  overlay.className = "confirm-toast-overlay";

  overlay.innerHTML = `
    <div class="confirm-toast-box">
      <p class="confirm-toast-message">${message}</p>

      <div class="confirm-toast-buttons">
        <button class="confirm-btn confirm-yes" id="confirm-yes">Yes, clear it</button>
        <button class="confirm-btn confirm-no" id="confirm-no">Cancel</button>
      </div>
    </div>
  `;

  document.body.appendChild(overlay);

  requestAnimationFrame(() => {
    overlay.classList.add("active");
  });

  document.getElementById("confirm-yes").addEventListener("click", () => {
    overlay.classList.remove("active");
    setTimeout(() => overlay.remove(), 300);
    onConfirm();
  });

  document.getElementById("confirm-no").addEventListener("click", () => {
    overlay.classList.remove("active");
    setTimeout(() => overlay.remove(), 300);
  });

  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) {
      overlay.classList.remove("active");
      setTimeout(() => overlay.remove(), 300);
    }
  });
}

// ================= quick add to cart =================
function quickAddToCart(name, price, imgSrc) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let existing = cart.find((item) => item.name === name);

  const maxQty = getMaxQtyForOrderType();
  if (maxQty !== Infinity) {
    const currentQty = getCartTotalQty();
    const extraQty = 1;
    if (currentQty + extraQty > maxQty) {
      showBoxLimitToast();
      showToast(`Cannot add more than ${maxQty} cookies for this box.`);
      return;
    }
  }

  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ name, price, qty: 1, imgSrc });
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();

  if (document.getElementById("cart-items")) {
    displayCart();
  }

  showToast(`Added to cart: ${name}`, { type: "success" });

  const cartSection = document.querySelector(".cart-section");
  if (cartSection) {
    cartSection.scrollIntoView({ behavior: "smooth", block: "start" });
  }
}

// ================= order page =================
function selectOrder(type) {
  localStorage.setItem("orderType", type);

  const messages = {
    "6pcs": "Box of 6 selected! 🍪",
    "8pcs": "Box of 8 selected! 🍪🍪",
    "12pcs": "Box of 12 selected! 🍪🍪🍪",
    "per piece": "Per piece order selected! 🍪",
  };

  showToast(messages[type] || "Order selected!");

  setTimeout(() => {
    window.location.href = "menu.php";
  }, 1000);
}

// ================= ORDER LIMITS =================
function getMaxQtyForOrderType() {
  const orderType = localStorage.getItem("orderType") || "";

  if (orderType === "6pcs") return 6;
  if (orderType === "8pcs") return 8;
  if (orderType === "12pcs") return 12;

  return Infinity;
}

function getCartTotalQty() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  return cart.reduce((sum, item) => sum + (item.qty || 0), 0);
}

function canAddQty(extraQty) {
  const maxQty = getMaxQtyForOrderType();
  if (maxQty === Infinity) return true;

  const currentQty = getCartTotalQty();
  return currentQty + extraQty <= maxQty;
}

function showBoxLimitToast() {
  const orderType = localStorage.getItem("orderType") || "";
  const limits = {
    "6pcs": 6,
    "8pcs": 8,
    "12pcs": 12,
  };

  const maxQty = limits[orderType];
  if (!maxQty) return;

  showToast(`You can only order up to ${maxQty} cookies for this box.`);
}

// ================= navigation =================
function goToShop() {
  window.location.href = "menu.php";
}

function goToOrderPage() {
  window.location.href = "order.php";
}

// ================= cart page =================
const deliveryRates = {
  ncr: { base: 50, minOrder: 300 },
};

function displayCart() {
  const cartContainer = document.getElementById("cart-items");
  const subtotalDisplay = document.getElementById("subtotal-price");
  const totalDisplay = document.getElementById("total-price");

  const deliveryFee = 50;

  if (!cartContainer) return;

  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  if (cart.length === 0) {
    cartContainer.innerHTML = `
      <div class="empty-cart">
<div class="empty-cart-icon">🛒</div>

        <h3>Your cart is empty!</h3>
        <p>Looks like you haven't added any cookies yet.</p>

        <button class="hero-btn" onclick="location.href='menu.php'">
          🍪 Start Shopping
        </button>
      </div>
    `;

    if (subtotalDisplay) subtotalDisplay.textContent = "₱0";
    if (totalDisplay) totalDisplay.textContent = "₱" + deliveryFee;
    updateCartCount();
    return;
  }

  let subtotal = 0;
  cartContainer.innerHTML = "";

  cart.forEach((item, index) => {
    subtotal += item.price * item.qty;

    cartContainer.innerHTML += `
      <div class="cart-item">

        <img src="${item.imgSrc || "images/choco chip.jpg"}" 
             alt="${item.name}"
             onerror="this.src='images/choco chip.jpg'">
        <div class="cart-item-info">
          <h3>${item.name}</h3>
          <p>₱${item.price} x ${item.qty}</p>
          <div class="cart-item-qty">
            <button class="qty-btn" onclick="decreaseQty(${index})">−</button>
            <span>${item.qty}</span>
            <button class="qty-btn" onclick="increaseQty(${index})">+</button>
          </div>
        </div>
        <div class="cart-item-price">₱${(item.price * item.qty).toFixed(2)}</div>
        <button class="remove-btn" onclick="removeItem(${index})" 
                title="Remove item">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    `;
  });

  let total = subtotal + deliveryFee;

  if (subtotalDisplay) subtotalDisplay.textContent = "₱" + subtotal.toFixed(2);
  if (totalDisplay) totalDisplay.textContent = "₱" + total.toFixed(2);

  updateCartCount();
}

// ================= INCREASE QTY =================
function increaseQty(index) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  const extraQty = 1;
  if (!canAddQty(extraQty)) {
    showBoxLimitToast();
    return;
  }

  cart[index].qty += extraQty;
  localStorage.setItem("cart", JSON.stringify(cart));
  displayCart();
  updateCartCount();
}

// ================= DECREASE QTY =================
function decreaseQty(index) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  if (cart[index].qty > 1) {
    cart[index].qty -= 1;
  } else {
    cart.splice(index, 1);
    showToast("Item removed from cart!");
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  displayCart();
  updateCartCount();
}

// ================= remove item =================
function removeItem(index) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  const removedName = cart[index].name;
  cart.splice(index, 1);
  localStorage.setItem("cart", JSON.stringify(cart));
  displayCart();
  updateCartCount();
  showToast(`🗑️ ${removedName} removed from cart!`);
}

// ================= for clear cart =================
function clearCart() {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  if (cart.length === 0) {
    showToast("⚠️ Your cart is already empty!");
    return;
  }

  showConfirmToast("Are you sure you want to clear your cart?", () => {
    localStorage.removeItem("cart");
    localStorage.removeItem("orderType");
    showToast("🗑️ Cart cleared!");

    setTimeout(() => {
      location.reload();
    }, 1000);
  });
}

// ================= checkout button (cart page) =================
function checkout() {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  if (cart.length === 0) {
    showToast("⚠️ Your cart is empty! Add some cookies first 🍪");
    return;
  }

  window.location.href = "form.php"; // or checkout.php depending on your filename
}

// ================= CHECKOUT PAGE FUNCTIONS  =================
function displayCheckoutItems() {
  const checkoutContainer = document.getElementById("checkout-items");
  const orderTotals = document.getElementById("order-totals");
  const proceedBtn = document.getElementById("proceed-btn");
  const emptyMsg = document.getElementById("empty-cart-msg");

  if (!checkoutContainer) return;

  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  if (cart.length === 0) {
    if (emptyMsg) emptyMsg.style.display = "block";
    if (orderTotals) orderTotals.style.display = "none";
    if (proceedBtn) proceedBtn.disabled = true;
    return;
  }

  if (emptyMsg) emptyMsg.style.display = "none";
  if (orderTotals) orderTotals.style.display = "block";
  if (proceedBtn) proceedBtn.disabled = false;

  let subtotal = 0;
  let orderType = localStorage.getItem("orderType") || "";
  let itemsHTML = "";

if (orderType) {
  let cookieList = cart.map(item => item.name).join(", ");

  itemsHTML += `
    <div class="box-summary">
      <p><strong>
        ${orderType === "per piece" 
          ? "🍪 Your selected cookies per piece:" 
          : `🍪 Your box of ${orderType} contains:`}
      </strong></p>
      <p>${cookieList}</p>
      <hr>
    </div>
  `;
}
  cart.forEach((item) => {
    let itemTotal = item.price * item.qty;
    subtotal += itemTotal;

    itemsHTML += `
      <div class="checkout-item">
        <img src="${item.imgSrc || "images/choco chip.jpg"}" 
             alt="${item.name}"
             class="checkout-item-img"
             onerror="this.src='images/choco chip.jpg'">
        <div class="checkout-item-info">
          <h4>${item.name}</h4>
          <p>₱${item.price.toFixed(2)} × ${item.qty}</p>
        </div>
        <div class="checkout-item-total">
          <span>₱${itemTotal.toFixed(2)}</span>
        </div>
      </div>
    `;
  });

  checkoutContainer.innerHTML = itemsHTML;

  let deliveryFee = 50;
  let total = subtotal + deliveryFee;

  const subtotalEl = document.getElementById("subtotal");
  const deliveryEl = document.getElementById("delivery-fee");
  const totalEl = document.getElementById("order-total");

  if (subtotalEl) subtotalEl.textContent = "₱" + subtotal.toFixed(2);
  if (deliveryEl) deliveryEl.textContent = "₱" + deliveryFee.toFixed(2);
  if (totalEl) totalEl.textContent = "₱" + total.toFixed(2);
}

// ================= OPEN CHECKOUT MODAL  =================
function openCheckoutModal() {
  const alreadyAgreed = sessionStorage.getItem("termsAgreed") === "true";
  if (!alreadyAgreed) {
    openTermsModal();
    return;
  }

  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  if (cart.length === 0) {
    showToast("⚠️ Your cart is empty! Add some cookies first 🍪");
    return;
  }

  let subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  let deliveryFee = 50;
  let total = subtotal + deliveryFee;

  const summaryContainer = document.getElementById("modal-summary");
  if (summaryContainer) {
    let orderType = localStorage.getItem("orderType") || "";

    let summaryHTML = `<h4>📋 Order Summary</h4>`;

    if (orderType) { 
      summaryHTML += `
      <p class="box-message">
      🍪 Your box of ${orderType} contains:
      </p>
      `;
    }
    let cookieList = cart.map(item => item.name).join(", ");

    if (orderType && cookieList) {
      summaryHTML += `
        <p class="box-summary-text">
          (${cookieList})
        </p>
      `;
    }

    cart.forEach((item) => {
      summaryHTML += `
        <div class="modal-summary-item">
          <span>${item.name} × ${item.qty}</span>
          <span>₱${(item.price * item.qty).toFixed(2)}</span>
        </div>
      `;
    });

    summaryHTML += `
      <div class="modal-summary-item">
        <span>Delivery Fee</span>
        <span>₱${deliveryFee.toFixed(2)}</span>
      </div>
      <div class="modal-summary-total">
        <span><strong>Total</strong></span>
        <span><strong>₱${total.toFixed(2)}</strong></span>
      </div>
    `;

    summaryContainer.innerHTML = summaryHTML;
  }

  const dateInput = document.getElementById("checkout-date");
  if (dateInput) {
    const today = new Date().toISOString().split("T")[0];
    dateInput.setAttribute("min", today);
  }

  const deliveryLocationEl = document.getElementById("delivery-location");
  const deliveryEl = document.getElementById("delivery-fee");
  const orderTotalEl = document.getElementById("order-total");
  const checkoutAddressEl = document.getElementById("checkout-address");

  const cartForFee = JSON.parse(localStorage.getItem("cart")) || [];
  const baseSubtotal = cartForFee.reduce((sum, item) => sum + item.price * item.qty, 0);

  const addressContainsMarikina = () => {
    const address = String(checkoutAddressEl?.value || "").toLowerCase();
    return address.includes("marikina");
  };

  const resolvedDeliveryFeeFromLocation = (locationValue) => {
    const location = String(locationValue || "ncr").toLowerCase();
    const rates = {
      ncr: { base: 50, minOrder: 300 },
    };

    const chosen = rates[location] || rates.ncr;
    
    if (baseSubtotal >= chosen.minOrder) return 0;
    return chosen.base;
  };

  const updateTotals = (fee) => {
    const safeFee = Number(fee) || 0;
    if (deliveryEl) deliveryEl.textContent = "₱" + safeFee.toFixed(2);
    if (orderTotalEl) {
      const total = baseSubtotal + safeFee;
      orderTotalEl.textContent = "₱" + total.toFixed(2);
    }
  };

  const refreshDeliveryFee = () => {
    if (addressContainsMarikina()) {
      updateTotals(0);
      return;
    }

    if (deliveryLocationEl) {
      const fee = resolvedDeliveryFeeFromLocation(deliveryLocationEl.value);
      updateTotals(fee);
      return;
    }

    updateTotals(50);
  };

  refreshDeliveryFee();

  if (deliveryLocationEl) {
    deliveryLocationEl.addEventListener("change", refreshDeliveryFee);
  }
  if (checkoutAddressEl) {
    checkoutAddressEl.addEventListener("input", refreshDeliveryFee);
    checkoutAddressEl.addEventListener("change", refreshDeliveryFee);
  }

  const modal = document.getElementById("checkout-modal");

  if (modal) {
    modal.classList.add("active");
    document.body.style.overflow = "hidden";
  }
}

// ================= close checkout modal =================
function closeCheckoutModal() {
  const modal = document.getElementById("checkout-modal");
  if (modal) {
    modal.classList.remove("active");
    document.body.style.overflow = "auto";
  }
}

// ================= TERMS MODAL =================
function openTermsModal() {
  const modal = document.getElementById("terms-modal");
  if (!modal) return;

  const checkbox = document.getElementById("terms-agree-checkbox");
  const errorEl = document.getElementById("terms-error");

  if (errorEl) errorEl.style.display = "none";
  if (checkbox) checkbox.checked = false;

  modal.classList.add("active");
  modal.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
}

function closeTermsModal() {
  const modal = document.getElementById("terms-modal");
  if (!modal) return;
  modal.classList.remove("active");
  modal.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "";
}

function agreeAndProceed() {
  sessionStorage.setItem("termsAgreed", "true");

  const checkbox = document.getElementById("terms-agree-checkbox");
  const errorEl = document.getElementById("terms-error");

  if (!checkbox) return;

  if (!checkbox.checked) {
    if (errorEl) errorEl.style.display = "block";
    showToast("⚠️ Please agree to the Terms and Conditions.");
    return;
  }

  closeTermsModal();
  openCheckoutModal();
}

// ================= close success modal =================
function closeSuccessModal() {
  const modal = document.getElementById("success-modal");
  if (modal) {
    modal.classList.remove("active");
    document.body.style.overflow = "auto";
  }
}

// ================= show file name =================
function showFileName(input) {
  const fileNameDisplay = document.getElementById("file-name-display");
  if (input.files && input.files[0] && fileNameDisplay) {
    fileNameDisplay.textContent = "📄 " + input.files[0].name;
    fileNameDisplay.classList.add("show");
  }
}

// ================= INPUT VALIDATION =================
function validateName(name) {
  const nameRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;
  return nameRegex.test(name);
}

function validatePhone(phone) {
  const phoneClean = phone.replace(/[\s\-]/g, "");
  const phoneRegex = /^\d{11}$/;
  return phoneRegex.test(phoneClean);
}

function containsNumbers(str) {
  return /\d/.test(str);
}

function containsLetters(str) {
  return /[A-Za-z]/.test(str);
}

// ================= submit order =================
function submitOrder(event) {
  // DO NOT prevent default at the start. We will only prevent it if validation fails.
  
  const name = document.getElementById("checkout-name").value.trim();
  const phone = document.getElementById("checkout-phone").value.trim();
  const address = document.getElementById("checkout-address").value.trim();

  const ncrKeywords = [
    "ncr", "metro manila", "manila", "quezon city", "makati", "pasig",
    "taguig", "muntinlupa", "paranaque", "las piñas", "las pinas",
    "valenzuela", "malabon", "navotas", "caloocan", "marikina",
    "pasay", "pateros", "antipolo", "angono",
  ];

  const isNcr = (() => {
    const addr = String(address || "").toLowerCase();
    return ncrKeywords.some((k) => addr.includes(k));
  })();

  if (!isNcr) {
    event.preventDefault(); // Stop form submission
    showToast("❌ Delivery is available within NCR only. Please enter an NCR address.");
    document.getElementById("checkout-address").focus();
    return;
  }

  const date = document.getElementById("checkout-date").value;
  const time = document.getElementById("checkout-time").value;
  const proof = document.getElementById("checkout-proof").files[0];
  const notes = document.getElementById("checkout-notes") ? document.getElementById("checkout-notes").value.trim() : "";

  if (!name || !phone || !address || !date || !time || !proof) {
    event.preventDefault(); // Stop form submission
    showToast("⚠️ Please fill in all required fields!");
    return;
  }

  if (containsNumbers(name)) {
    event.preventDefault();
    showToast("⚠️ Name should contain letters only, no numbers!");
    document.getElementById("checkout-name").focus();
    return;
  }

  if (!validateName(name)) {
    event.preventDefault();
    showToast("⚠️ Please enter a valid name (letters only)!");
    document.getElementById("checkout-name").focus();
    return;
  }

  if (containsLetters(phone)) {
    event.preventDefault();
    showToast("⚠️ Phone number should contain numbers only!");
    document.getElementById("checkout-phone").focus();
    return;
  }

  const phoneClean = phone.replace(/\D/g, "");

  if (phoneClean.length !== 11) {
    event.preventDefault();
    showToast(`⚠️ Phone number must be exactly 11 digits! (currently ${phoneClean.length})`);
    document.getElementById("checkout-phone").focus();
    return;
  }

  if (!validatePhone(phone)) {
    event.preventDefault();
    showToast("⚠️ Please enter a valid 11-digit phone number!");
    document.getElementById("checkout-phone").focus();
    return;
  }

  // ================= VALIDATION PASSED =================
  // We do NOT prevent default here. The form will submit to PHP.
  
  // Put the cart data into the hidden inputs so PHP can read them
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  
  // Calculate delivery fee
  let deliveryFee = address.toLowerCase().includes("marikina") ? 0 : 50;
  let total = subtotal + deliveryFee;

  document.getElementById("hidden-subtotal").value = subtotal;
  document.getElementById("hidden-delivery-fee").value = deliveryFee;
  document.getElementById("hidden-total").value = total;
  document.getElementById("hidden-cart-items").value = JSON.stringify(cart);

  // Let the form submit normally! (The page will refresh)
}

// ================= receipt printing =================
function openReceiptModal() {
  const modal = document.getElementById("receipt-modal");
  if (!modal) return;

  modal.classList.add("active");
  modal.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
}

function closeReceiptModal() {
  const modal = document.getElementById("receipt-modal");
  if (!modal) return;
  modal.classList.remove("active");
  modal.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "auto";
}

function printReceipt() {
  const receiptEl = document.getElementById("receipt");
  if (!receiptEl) return;

  const originalTitle = document.title;
  try {
    document.title = "Dough LEYcious Receipt";
    window.print();
  } finally {
    document.title = originalTitle;
  }
}

// ================= total =================
function getTotal() {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  return cart.reduce((sum, item) => sum + item.price * item.qty, 0);
}

// ================= Back to top button =================
(function initBackToTop() {
  const btn = document.getElementById("backToTop");
  if (!btn) return;

  const toggle = () => {
    if (window.scrollY > 300) btn.classList.add("show");
    else btn.classList.remove("show");
  };

  window.addEventListener("scroll", toggle, { passive: true });
  toggle();

  btn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
})();

  // ================= hamburger menu =================
  document.querySelectorAll(".navbar").forEach((navbar) => {
    const btn = navbar.querySelector(".nav-toggle-btn");
    const links = navbar.querySelector(".nav-links");
    if (!btn || !links) return;

    links.classList.remove("nav-open");
    btn.setAttribute("aria-expanded", "false");

    const toggle = () => {
      const isOpen = links.classList.toggle("nav-open");
      btn.setAttribute("aria-expanded", String(isOpen));
    };

    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      toggle();
    });

    links.querySelectorAll("a").forEach((a) => {
      a.addEventListener("click", () => {
        links.classList.remove("nav-open");
        btn.setAttribute("aria-expanded", "false");
      });
    });

    document.addEventListener("click", (e) => {
      if (!navbar.contains(e.target)) {
        links.classList.remove("nav-open");
        btn.setAttribute("aria-expanded", "false");
      }
    });
  });
  
  updateCartCount();

  if (document.getElementById("cart-items")) {
    displayCart();
  }

  if (document.getElementById("checkout-items")) {
    displayCheckoutItems();
  }

  // ================= cookie modal listeners =================
  const ingredientModal = document.getElementById("ingredientModal");
  if (ingredientModal) {
    ingredientModal.addEventListener("click", function (e) {
      if (e.target === this) {
        closeIngredientModal();
      }
    });

    const qtyInput = ingredientModal.querySelector(".quantity input[type='number']");
    const plusBtn = ingredientModal.querySelector(".quantity .plus");
    const minusBtn = ingredientModal.querySelector(".quantity .minus");

    const getQty = () => Math.max(0, parseInt(qtyInput?.value, 10) || 0);
    const setQty = (v) => {
      if (!qtyInput) return;
      qtyInput.value = String(Math.max(0, v));
    };

    if (plusBtn) {
      plusBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        setQty(getQty() + 1);
      });
    }

    if (minusBtn) {
      minusBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        setQty(getQty() - 1);
      });
    }
  }

  // ================= shop page product card listeners =================
  const productCards = document.querySelectorAll(".product-card");

  if (productCards.length > 0) {
    productCards.forEach((card) => {
      const input = card.querySelector("input");
      const plus = card.querySelector(".plus");
      const minus = card.querySelector(".minus");
      const addBtn = card.querySelector(".add-to-cart-btn");

      if (plus) {
        plus.addEventListener("click", (e) => {
          e.stopPropagation();
          input.value = (parseInt(input.value) || 0) + 1;
        });
      }

      if (minus) {
        minus.addEventListener("click", (e) => {
          e.stopPropagation();
          if (parseInt(input.value) > 0) {
            input.value = parseInt(input.value) - 1;
          }
        });
      }

      if (addBtn) {
        addBtn.addEventListener("click", (e) => {
          e.stopPropagation();

          let qty = parseInt(input.value);

          if (qty <= 0) {
            showToast("⚠️ Please select a quantity first!");
            return;
          }

          const name = card.querySelector("h3").textContent.trim();
          const priceText = card.querySelector(".price").textContent;
          const price = parseFloat(priceText.replace("₱", "").trim());

          const img = card.querySelector("img");
          const imgSrc = img ? img.src : "";

          const maxQty = getMaxQtyForOrderType();

          if (maxQty !== Infinity) {
            const currentQty = getCartTotalQty();
            if (currentQty + qty > maxQty) {
              showBoxLimitToast();
              showToast(`Cannot add more than ${maxQty} cookies for this box.`);
              return;
            }
          }

          let cart = JSON.parse(localStorage.getItem("cart")) || [];
          let existing = cart.find((item) => item.name === name);

          if (existing) {
            existing.qty += qty;
          } else {
            cart.push({ name, price, qty, imgSrc });
          }

          localStorage.setItem("cart", JSON.stringify(cart));

          updateCartCount();

          input.value = 0;

          showToast(`Added to cart: ${name}`, { type: "success" });

        });
      }
    });
  }

  // ================= checkout modal listeners =================
  document.addEventListener("click", function (e) {
    const checkoutModal = document.getElementById("checkout-modal");
    const successModal = document.getElementById("success-modal");
    const termsModal = document.getElementById("terms-modal");

    if (e.target === checkoutModal) {
      closeCheckoutModal();
    }
    if (e.target === successModal) {
      closeSuccessModal();
    }
    if (termsModal && e.target === termsModal) {
      closeTermsModal();
    }
  });

  // ================= ESC key closes all modals =================
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeIngredientModal();
      closeCheckoutModal();
      closeTermsModal();
      closeSuccessModal();
      closeReceiptModal();
    }
  });

  // ================= Full Name block numbers in real-time =================
  const nameInput = document.getElementById("checkout-name");

  if (nameInput) {
    nameInput.addEventListener("keypress", function (e) {
      if (/\d/.test(e.key)) {
        e.preventDefault();
        showToast("⚠️ Name should contain letters only, no numbers!");
      }
    });

    nameInput.addEventListener("input", function () {
      if (containsNumbers(this.value)) {
        showToast("⚠️ Name should contain letters only, no numbers!");
        this.value = this.value.replace(/\d/g, "");
      }
    });
  }

  // ================= Phone Number block letters in real time =================
  const phoneInput = document.getElementById("checkout-phone");
  if (phoneInput) {
    phoneInput.addEventListener("keypress", function (e) {
      if (/[A-Za-z]/.test(e.key)) {
        e.preventDefault();
        showToast("⚠️ Phone number should contain numbers only!");
      }
   
