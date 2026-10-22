<?php
// ================= DATABASE CONNECTION & ORDER PROCESSING =================
$host = 'localhost';
$db   = 'doughleycious';
$user = 'root'; // Default XAMPP user
$pass = '';     // Default XAMPP password (usually empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$order_success = false;
$order_id = null;

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
    // 1. Handle File Upload
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create uploads folder if it doesn't exist
    }
    
    $fileName = time() . '_' . basename($_FILES['proof']['name']);
    $uploadFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['proof']['tmp_name'], $uploadFile)) {
        try {
            $pdo->beginTransaction();

            // Insert Order
            $stmt = $pdo->prepare("INSERT INTO orders (full_name, phone, address, delivery_date, delivery_time, proof_of_payment, notes, subtotal, delivery_fee, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $_POST['name'],
                $_POST['phone'],
                $_POST['address'],
                $_POST['date'],
                $_POST['time'],
                $fileName,
                $_POST['notes'],
                $_POST['subtotal'], 
                $_POST['delivery_fee'], 
                $_POST['total'] 
            ]);

            $order_id = $pdo->lastInsertId();

            // Insert Order Items (Decoded from JSON hidden input)
            if(isset($_POST['cart_items_json']) && !empty($_POST['cart_items_json'])) {
                $cartItems = json_decode($_POST['cart_items_json'], true);
                if (is_array($cartItems)) {
                    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
                    foreach ($cartItems as $item) {
                        $stmtItem->execute([$order_id, $item['name'], $item['price'], $item['qty']]);
                    }
                }
            }

            $pdo->commit();
            $order_success = true;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Error saving order: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload proof of payment.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Dough LEYcious</title>

   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="receipt.css">

   <!-- for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- for font only -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- favicon -->
    <link rel="icon" type="image/png" href="images/logo.png">
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="index.php" class="nav-logo">
  <img src="images/logo.png" alt="Dough LEYcious Logo" class="nav-logo-img">
</a>

            <button class="nav-toggle-btn" type="button" aria-label="Open menu" aria-expanded="false">
              <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="cart.php">🛒 Cart (<span id="cart-count">0</span>)</a></li>
            </ul>
        </div>
    </nav>

    <!-- ================= MAIN CHECKOUT PAGE ================= -->
    <main class="checkout-page">

        <!-- page header -->
        <section class="checkout-header">
            <h1>🍪 Checkout</h1>
            <p>Review your order and complete your purchase</p>
        </section>

        <!-- cart summary section -->
        <section class="checkout-content">
            <div class="checkout-grid">

                <div class="order-summary-card">
                    <h2>📋 Order Summary</h2>
                    <div id="checkout-items">
                        <div class="empty-cart-message" id="empty-cart-msg">
                            <div class="empty-icon">🛒</div>
                            <h3>Your cart is empty</h3>
                            <p>Add some delicious cookies before checking out!</p>
                            <a href="menu.php" class="shop-now-btn">Browse Menu</a>
                        </div>
                    </div>

                    <div class="order-totals" id="order-totals" style="display:none;">
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

                <div class="payment-info-card">
                    <h2>💳 Payment Information</h2>

                    <div class="payment-methods">
                        <h3>Send payment to:</h3>
                        <div class="payment-option">
                            <div class="payment-icon">📱</div>
                            <div class="payment-details">
                                <strong>G-cash</strong>
                                <p>09366352711</p>
                                <p class="payment-name">Mylene n.</p>
                            </div>
                        </div>
                        
                    <div class="payment-reminder">
                        <p>⚠️ Please send the exact amount and keep your receipt/screenshot for proof of payment.</p>
                    </div>

                    <button class="proceed-checkout-btn" id="proceed-btn" onclick="openCheckoutModal()" disabled>
                        🍪 Proceed to Checkout
                    </button>
                </div>

            </div>
        </section>

    </main>

    <!-- ================= TERMS & CONDITIONS MODAL ================= -->
    <div class="modal-overlay" id="terms-modal" aria-hidden="true">
        <div class="checkout-modal" role="dialog" aria-modal="true" aria-labelledby="terms-title">
            <button class="modal-close" type="button" onclick="closeTermsModal()">&times;</button>

            <div class="modal-header">
                <div class="modal-icon">📜</div>
                <h2 id="terms-title">Terms and Conditions</h2>
                <p>Please agree before proceeding to checkout.</p>
            </div>

            <div class="terms-body">
                <div class="terms-text">
                    <p><strong>1.</strong> Your order will be processed based on the items currently in your cart.</p>
                    <p><strong>2.</strong> Be aware that the delivery is only available in NCR.</p>
                    <p><strong>3.</strong> Confirmation of payment proof may be required to finalize your order.</p>
                    <p style="margin-top:12px;"><strong>For complete details:</strong> <a href="terms.php" target="_blank" rel="noopener">Read our full Terms</a></p>
                </div>

                <label class="terms-agree">
                    <input type="checkbox" id="terms-agree-checkbox" />
                    <span>I agree to the Terms and Conditions</span>
                </label>
                <div class="terms-error" id="terms-error" style="display:none;">
                    ⚠️ Please check the box to agree.
                </div>

                <button type="button" class="checkout-submit-btn" onclick="agreeAndProceed()">
                    ✅ Agree & Proceed
                </button>
            </div>
        </div>
    </div>

    <!-- ================= CHECKOUT MODAL ================= -->
    <div class="modal-overlay" id="checkout-modal">

        <div class="checkout-modal" role="dialog" aria-modal="true" aria-labelledby="checkout-form-title">

            <button class="modal-close" onclick="closeCheckoutModal()">&times;</button>

            <div class="modal-header">
                <div class="modal-icon">🍪</div>
                <h2 id="checkout-form-title">Complete Your Order</h2>
                <p>Fill in your delivery details below</p>
            </div>

            <!-- order summary in modal -->
            <div class="modal-summary" id="modal-summary">
                
            </div>

            <!-- CHECKOUT FORM (Updated for PHP processing) -->
            <form id="checkout-form" action="checkout.php" method="POST" enctype="multipart/form-data" onsubmit="submitOrder(event)">

                <!-- HIDDEN FIELDS FOR PHP -->
                <input type="hidden" name="submit_order" value="1">
                <input type="hidden" name="subtotal" id="hidden-subtotal" value="0">
                <input type="hidden" name="delivery_fee" id="hidden-delivery-fee" value="50">
                <input type="hidden" name="total" id="hidden-total" value="0">
                <input type="hidden" name="cart_items_json" id="hidden-cart-items">

                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" id="checkout-name" name="name" placeholder="e.g. Arvic Pelinta" required>
                </div>

                <div class="form-group">
                    <label>Phone Number <span class="required">*</span></label>
                    <input type="tel" id="checkout-phone" name="phone" placeholder="e.g. 09123456789" required>
                </div>

                <div class="form-group">
                    <label>Delivery Address <span class="required">*</span></label>
                    <textarea id="checkout-address" name="address" placeholder="Complete address (street, barangay, city)" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Delivery Date <span class="required">*</span></label>
                        <input type="date" id="checkout-date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label>Delivery Time <span class="required">*</span></label>
                        <input type="time" id="checkout-time" name="time" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Proof of Payment <span class="required">*</span></label>
                    <div class="file-upload-wrapper">
                        <label class="file-upload-label" for="checkout-proof">
                            <span class="upload-icon">📎</span>
                            <span class="upload-text">Click to upload screenshot</span>
                            <span class="upload-hint">JPG, PNG or PDF (max 5MB)</span>
                        </label>
                        <input type="file" id="checkout-proof" name="proof" accept="image/*,.pdf" required onchange="showFileName(this)">
                    </div>
                    <div class="file-name" id="file-name-display"></div>
                </div>

                <div class="form-group">
                    <label>Other Messages (Optional)</label>
                    <textarea id="checkout-notes" name="notes" placeholder="Any special requests or notes..."></textarea>
                </div>

                <button type="submit" class="checkout-submit-btn">
                    🍪 Place Order
                </button>
            </form>
        </div>
    </div>

    <!-- ================= SUCCESS MODAL ================= -->
    <div class="modal-overlay" id="success-modal" <?php if($order_success) echo 'style="display:flex;"'; ?>>
        <div class="success-modal">
            <div class="success-icon">✅</div>
            <h2>Order Placed Successfully!</h2>
            <p>Thank you for your order! We'll confirm your order shortly via SMS/call.</p>
            <div class="success-details" id="success-details">
                <?php if($order_success) echo "<p><strong>Order #:</strong> " . $order_id . "</p>"; ?>
            </div>

            <div class="success-receipt-actions" style="margin-top: 16px;">
                <button type="button" class="receipt-action-btn" onclick="openReceiptModal()">
                    👀 View Receipt
                </button>
                <hr style="margin: 16px 0;">
            </div>

            <!-- ================= RECEIPT MODAL ================= -->
            <div class="modal-overlay" id="receipt-modal" aria-hidden="true">
                <div class="checkout-modal receipt-modal" role="dialog" aria-modal="true" aria-labelledby="receipt-modal-title">
                    <button class="modal-close" type="button" onclick="closeReceiptModal()">&times;</button>

                    <div class="modal-header">
                        <div class="modal-icon">🎟️</div>
                        <h2 id="receipt-modal-title">Your Receipt</h2>
                        <p>Print or save your receipt.</p>
                    </div>

                    <div class="receipt-container" id="receipt-container" aria-label="Receipt" style="margin-top: 10px;">
                        <div class="receipt-actions">
                            <button type="button" class="receipt-action-btn" onclick="printReceipt()">
                                🖨️ Print Receipt
                            </button>
                        </div>

                        <div class="receipt" id="receipt">
                            <div class="receipt-header">
                                <div class="receipt-title">🎟️ Dough LEYcious Receipt</div>
                                <div class="receipt-order-no">Order #: <span id="receipt-order-no">—</span></div>
                                <div class="receipt-date">Date: <span id="receipt-date">—</span></div>
                            </div>

                            <div class="receipt-section">
                                <div class="receipt-section-title">Customer</div>
                                <div class="receipt-line"><span>Name:</span> <span id="receipt-name">—</span></div>
                                <div class="receipt-line"><span>Phone:</span> <span id="receipt-phone">—</span></div>
                                <div class="receipt-line"><span>Address:</span> <span id="receipt-address">—</span></div>
                            </div>

                            <div class="receipt-section">
                                <div class="receipt-section-title">Delivery</div>
                                <div class="receipt-line"><span>Date:</span> <span id="receipt-delivery-date">—</span></div>
                                <div class="receipt-line"><span>Time:</span> <span id="receipt-delivery-time">—</span></div>
                            </div>

                            <div class="receipt-section">
                                <div class="receipt-section-title">Items</div>
                                <div class="receipt-items" id="receipt-items"></div>
                            </div>

                            <div class="receipt-section" id="receipt-notes-section" style="display:none;">
                                <div class="receipt-section-title">Notes</div>
                                <div class="receipt-note" id="receipt-notes"></div>
                            </div>

                            <div class="receipt-totals">
                                <div class="receipt-total-row"><span>Subtotal</span> <span id="receipt-subtotal">—</span></div>
                                <div class="receipt-total-row"><span>Delivery Fee</span> <span id="receipt-delivery-fee">—</span></div>
                                <div class="receipt-total-row receipt-total-final"><span>Total</span> <span id="receipt-total">—</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="success-buttons">
                <a href="menu.php" class="success-btn shop-more">Continue Shopping</a>
                <a href="index.php" class="success-btn go-home">Back to Home</a>
            </div>
        </div>
    </div>

<!-- ================= FOOTER ================= -->
    <button id="backToTop" class="back-to-top" type="button" aria-label="Back to top" title="Back to top">
  <i class="fas fa-cookie-bite"></i>
    </button>

    <footer class="footer">
  <div class="footer-content">

    <div class="footer-section">
      <h3>Find Us</h3>
      <div class="socials">
        <a href="https://www.facebook.com/profile.php?id=61567054065532" 
           aria-label="Visit our Facebook page">
          <i class="fab fa-facebook"></i>
        </a>
        <a href="https://www.instagram.com/dough_leycious/" 
           aria-label="Visit our Instagram page">
          <i class="fab fa-instagram"></i>
        </a>
      </div>
    </div>

    <div class="footer-section">
      <h3>Quick Links</h3>
        <a href="index.php">Home</a>
          <a href="menu.php">Menu</a>
        <a href="aboutus.php">About Us</a>
        <a href="terms.php">Terms and Conditions</a>
    </div>

    <div class="footer-section">
      <h3>Contact</h3>
      <p>📧 <a href="mailto:k.munsayac1109@gmail.com">k.munsayac1109@gmail.com</a></p>
      <p>📍 Available for delivery</p>
    </div>

  </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <p>&copy; 2025 Dough LEYcious. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>

    <!-- ================= SUCCESS TRIGGER SCRIPT ================= -->
    <?php if($order_success): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Show the success modal automatically
            const successModal = document.getElementById("success-modal");
            if (successModal) {
                successModal.classList.add("active");
                document.body.style.overflow = "hidden";
            }

            // 2. Clear the cart now that the order is safely in the database
            localStorage.removeItem("cart");
            if(typeof updateCartCount === 'function') updateCartCount();

            // 3. Populate the receipt data using the PHP data
            document.getElementById("receipt-order-no").textContent = "<?php echo $order_id; ?>";
            document.getElementById("receipt-date").textContent = "<?php echo date('m/d/Y h:i A'); ?>";
            document.getElementById("receipt-name").textContent = "<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>";
            document.getElementById("receipt-phone").textContent = "<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>";
            document.getElementById("receipt-address").textContent = "<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>";
            document.getElementById("receipt-delivery-date").textContent = "<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>";
            document.getElementById("receipt-delivery-time").textContent = "<?php echo htmlspecialchars($_POST['time'] ?? ''); ?>";
            
            const notes = "<?php echo htmlspecialchars($_POST['notes'] ?? ''); ?>";
            if(notes) {
                document.getElementById("receipt-notes-section").style.display = "block";
                document.getElementById("receipt-notes").textContent = notes;
            }

            // Rebuild the receipt items
            let cartJSON = `<?php echo $_POST['cart_items_json'] ?? '[]'; ?>`;
            try {
                let cartItems = JSON.parse(cartJSON);
                let receiptItemsHTML = "";
                cartItems.forEach(item => {
                    receiptItemsHTML += `<div class="receipt-item">
                        <span class="receipt-item-name">${item.name} × ${item.qty}</span>
                        <span class="receipt-item-amount">₱${(item.price * item.qty).toFixed(2)}</span>
                    </div>`;
                });
                document.getElementById("receipt-items").innerHTML = receiptItemsHTML;
            } catch(e) {
                console.log("Could not parse cart for receipt", e);
            }

            // Set Totals
            document.getElementById("receipt-subtotal").textContent = "₱<?php echo number_format($_POST['subtotal'] ?? 0, 2); ?>";
            document.getElementById("receipt-delivery-fee").textContent = "₱<?php echo number_format($_POST['delivery_fee'] ?? 0, 2); ?>";
            document.getElementById("receipt-total").textContent = "₱<?php echo number_format($_POST['total'] ?? 0, 2); ?>";
        });
    </script>
    <?php endif; ?>

</body>
</html>
