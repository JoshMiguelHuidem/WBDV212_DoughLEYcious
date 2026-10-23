<?php
    require("database.php");


    if (!isset($_POST['item_name'])) {
        header("Location: menu.php");
        exit();
    }

    $order_number = "DL-" . date("Ymd-Hi") . "-" . rand(1000, 9999);
    $name         = $con->real_escape_string($_POST['customer_name']);
    $phone        = $con->real_escape_string($_POST['phone']);
    $address      = $con->real_escape_string($_POST['address']);
    $date         = $con->real_escape_string($_POST['delivery_date']);
    $time         = $con->real_escape_string($_POST['delivery_time']);
    $proof        = $con->real_escape_string($_POST['proof_ref']);
    $notes        = $con->real_escape_string($_POST['notes']);
    $delivery_fee = 50;


    $subtotal = 0;
    for ($i = 0; $i < count($_POST['item_name']); $i++) {
        $subtotal += (float) $_POST['item_price'][$i] * (int) $_POST['item_qty'][$i];
    }
    $total = $subtotal + $delivery_fee;

    $query = "INSERT INTO tbl_orders
                (order_number, customer_name, phone, address, delivery_date, delivery_time, proof_ref, notes, subtotal, delivery_fee, total)
              VALUES
                ('$order_number', '$name', '$phone', '$address', '$date', '$time', '$proof', '$notes', $subtotal, $delivery_fee, $total)";

    $exec = $con->query($query);

    if ($exec) {

        $order_id = $con->insert_id;


        for ($i = 0; $i < count($_POST['item_name']); $i++) {
            $product_name = $con->real_escape_string($_POST['item_name'][$i]);
            $price = (float) $_POST['item_price'][$i];
            $qty = (int) $_POST['item_qty'][$i];

            $con->query("INSERT INTO tbl_order_items (order_id, product_name, price, qty)
                         VALUES ($order_id, '$product_name', $price, $qty)");
        }
        ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Order Placed - Dough LEYcious</title>
                <link rel="stylesheet" href="style.css">
                <link rel="stylesheet" href="receipt.css">
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                <link rel="icon" type="image/png" href="images/logo.png">
            </head>
            <body>

                <!-- ================= SUCCESS MODAL (order summary) ================= -->
                <div class="modal-overlay active" id="success-modal">
                    <div class="success-modal">
                        <div class="success-icon">✅</div>
                        <h2>Order Placed Successfully!</h2>
                        <p>Thank you for your order! We'll confirm your order shortly via SMS/call.</p>

                        <div class="success-details" id="success-details">
                            <div class="success-info">
                                <p><strong>🎟️ Order #:</strong> <?php echo $order_number; ?></p>
                                <p><strong>👤 Name:</strong> <?php echo htmlspecialchars($_POST['customer_name']); ?></p>
                                <p><strong>📱 Phone:</strong> <?php echo htmlspecialchars($_POST['phone']); ?></p>
                                <p><strong>📍 Address:</strong> <?php echo htmlspecialchars($_POST['address']); ?></p>
                                <p><strong>📅 Delivery:</strong> <?php echo htmlspecialchars($_POST['delivery_date']); ?> at <?php echo htmlspecialchars($_POST['delivery_time']); ?></p>
                                <p><strong>💰 Total:</strong> ₱<?php echo number_format($total, 2); ?></p>
                                <?php if ($_POST['notes'] != "") { ?>
                                    <p><strong>📝 Notes:</strong> <?php echo htmlspecialchars($_POST['notes']); ?></p>
                                <?php } ?>
                            </div>
                            <div class="success-items">
                                <h4>Items Ordered:</h4>
                                <?php for ($i = 0; $i < count($_POST['item_name']); $i++) { ?>
                                    <p>
                                        <?php echo htmlspecialchars($_POST['item_name'][$i]); ?> ×
                                        <?php echo (int) $_POST['item_qty'][$i]; ?> —
                                        ₱<?php echo number_format((float) $_POST['item_price'][$i] * (int) $_POST['item_qty'][$i], 2); ?>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="success-receipt-actions" style="margin-top: 16px;">
                            <button type="button" class="receipt-action-btn" onclick="openReceiptModal()">
                                👀 View Receipt
                            </button>
                            <hr style="margin: 16px 0;">
                        </div>

                        <!-- ================= RECEIPT MODAL ================= -->
                        <div class="modal-overlay" id="receipt-modal" aria-hidden="true">
                            <div class="checkout-modal receipt-modal" role="dialog" aria-modal="true">
                                <button class="modal-close" type="button" onclick="closeReceiptModal()">&times;</button>

                                <div class="modal-header">
                                    <div class="modal-icon">🎟️</div>
                                    <h2>Your Receipt</h2>
                                    <p>Print or save your receipt.</p>
                                </div>

                                <div class="receipt-container" id="receipt-container" style="margin-top: 10px;">
                                    <div class="receipt-actions">
                                        <button type="button" class="receipt-action-btn" onclick="printReceipt()">
                                            🖨️ Print Receipt
                                        </button>
                                    </div>

                                    <div class="receipt" id="receipt">
                                        <div class="receipt-header">
                                            <div class="receipt-title">🎟️ Dough LEYcious Receipt</div>
                                            <div class="receipt-order-no">Order #: <span><?php echo $order_number; ?></span></div>
                                            <div class="receipt-date">Date: <span><?php echo date("Y-m-d H:i"); ?></span></div>
                                        </div>

                                        <div class="receipt-section">
                                            <div class="receipt-section-title">Customer</div>
                                            <div class="receipt-line"><span>Name:</span> <span><?php echo htmlspecialchars($_POST['customer_name']); ?></span></div>
                                            <div class="receipt-line"><span>Phone:</span> <span><?php echo htmlspecialchars($_POST['phone']); ?></span></div>
                                            <div class="receipt-line"><span>Address:</span> <span><?php echo htmlspecialchars($_POST['address']); ?></span></div>
                                        </div>

                                        <div class="receipt-section">
                                            <div class="receipt-section-title">Delivery</div>
                                            <div class="receipt-line"><span>Date:</span> <span><?php echo htmlspecialchars($_POST['delivery_date']); ?></span></div>
                                            <div class="receipt-line"><span>Time:</span> <span><?php echo htmlspecialchars($_POST['delivery_time']); ?></span></div>
                                        </div>

                                        <div class="receipt-section">
                                            <div class="receipt-section-title">Items</div>
                                            <div class="receipt-items">
                                                <?php for ($i = 0; $i < count($_POST['item_name']); $i++) { ?>
                                                    <div class="receipt-item">
                                                        <span class="receipt-item-name"><?php echo htmlspecialchars($_POST['item_name'][$i]); ?> × <?php echo (int) $_POST['item_qty'][$i]; ?></span>
                                                        <span class="receipt-item-amount">₱<?php echo number_format((float) $_POST['item_price'][$i] * (int) $_POST['item_qty'][$i], 2); ?></span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <?php if ($_POST['notes'] != "") { ?>
                                            <div class="receipt-section">
                                                <div class="receipt-section-title">Notes</div>
                                                <div class="receipt-note"><?php echo htmlspecialchars($_POST['notes']); ?></div>
                                            </div>
                                        <?php } ?>

                                        <div class="receipt-totals">
                                            <div class="receipt-total-row"><span>Subtotal</span> <span>₱<?php echo number_format($subtotal, 2); ?></span></div>
                                            <div class="receipt-total-row"><span>Delivery Fee</span> <span>₱<?php echo number_format($delivery_fee, 2); ?></span></div>
                                            <div class="receipt-total-row receipt-total-final"><span>Total</span> <span>₱<?php echo number_format($total, 2); ?></span></div>
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

                <script>

                    localStorage.removeItem("cart");
                </script>
                <script src="script.js"></script>
            </body>
            </html>
        <?php
    } else {
        ?>
            <script>
                alert("Order failed! Please try again.");
                window.location.href = "form.php";
            </script>
        <?php
    }
?>