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

    $items = array();
    $problems = array();
    $subtotal = 0;

    for ($i = 0; $i < count($_POST['item_name']); $i++) {
        $product_name = $con->real_escape_string($_POST['item_name'][$i]);
        $qty = (int) $_POST['item_qty'][$i];

        if ($qty < 1) {
            continue;
        }

        $execProduct = $con->query("SELECT * FROM tbl_products
                                    WHERE product_name = '$product_name' AND is_hidden = 0");

        if ($execProduct->num_rows == 0) {
            $problems[] = $_POST['item_name'][$i] . " is no longer available.";
            continue;
        }

        $product = $execProduct->fetch_assoc();

        if ((int) $product['is_available'] == 0) {
            $problems[] = $product['product_name'] . " is currently unavailable.";
            continue;
        }

        $items[] = array(
            "name" => $product['product_name'],
            "price" => (float) $product['price'],
            "qty" => $qty
        );
        $subtotal += (float) $product['price'] * $qty;
    }

    if (count($problems) > 0 || count($items) == 0) {
        $message = count($problems) > 0 ? implode("\n", $problems) : "Your cart is empty.";
        ?>
            <script>
                alert(<?php echo json_encode($message); ?>);
                window.location.href = "cart.php";
            </script>
        <?php
        exit();
    }

    $total = $subtotal + $delivery_fee;

    $query = "INSERT INTO tbl_orders
                (order_number, customer_name, phone, address, delivery_date, delivery_time, proof_ref, notes, subtotal, delivery_fee, total)
              VALUES
                ('$order_number', '$name', '$phone', '$address', '$date', '$time', '$proof', '$notes', $subtotal, $delivery_fee, $total)";

    $exec = $con->query($query);

    if (!$exec) {
        ?>
            <script>
                alert("Order failed! Please try again.");
                window.location.href = "form.php";
            </script>
        <?php
        exit();
    }

    $order_id = $con->insert_id;

    foreach ($items as $item) {
        $product_name = $con->real_escape_string($item['name']);
        $price = $item['price'];
        $qty = $item['qty'];

        $con->query("INSERT INTO tbl_order_items (order_id, product_name, price, qty)
                     VALUES ($order_id, '$product_name', $price, $qty)");
    }

    $pageTitle = "Order Placed";
    $pageDescription = "Dough LEYcious - Your order has been placed.";
    $activePage = "";
    include("site_header.php");
?>

<main>
  <header class="page-banner">
    <div class="container">
      <span class="eyebrow">Order confirmed</span>
      <h1>Thank You</h1>
      <p>Your order has been placed and is waiting for confirmation.</p>
    </div>
  </header>
</main>

<div class="modal-overlay active" id="success-modal">
  <div class="modal modal--success" role="dialog" aria-modal="true" aria-labelledby="success-title">
    <div class="icon-badge"><i class="fa-solid fa-check"></i></div>
    <h2 id="success-title">Order Placed Successfully</h2>
    <p>Thank you for your order. We will confirm your order shortly via SMS or call.</p>

    <div class="success-details" id="success-details">
      <p><strong>Order #</strong> <span><?php echo $order_number; ?></span></p>
      <p><strong>Name</strong> <span><?php echo htmlspecialchars($_POST['customer_name']); ?></span></p>
      <p><strong>Phone</strong> <span><?php echo htmlspecialchars($_POST['phone']); ?></span></p>
      <p><strong>Address</strong> <span><?php echo htmlspecialchars($_POST['address']); ?></span></p>
      <p><strong>Delivery</strong> <span><?php echo htmlspecialchars($_POST['delivery_date']); ?> at <?php echo htmlspecialchars($_POST['delivery_time']); ?></span></p>
      <p><strong>Total</strong> <span>₱<?php echo number_format($total, 2); ?></span></p>
      <?php if ($_POST['notes'] != "") { ?>
        <p><strong>Notes</strong> <span><?php echo htmlspecialchars($_POST['notes']); ?></span></p>
      <?php } ?>

      <div class="success-items">
        <h4>Items Ordered</h4>
        <?php foreach ($items as $item) { ?>
          <p>
            <span><?php echo htmlspecialchars($item['name']); ?> &times; <?php echo $item['qty']; ?></span>
            <span>₱<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
          </p>
        <?php } ?>
      </div>
    </div>

    <button type="button" class="btn btn--soft btn--block" onclick="openReceiptModal()">
      <i class="fa-regular fa-file-lines"></i> View Receipt
    </button>

    <div class="success-buttons">
      <a href="menu.php" class="btn btn--primary">Continue Shopping</a>
      <a href="index.php" class="btn btn--ghost">Back to Home</a>
    </div>
  </div>
</div>

<div class="modal-overlay" id="receipt-modal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="receipt-modal-title">
    <button type="button" class="modal__close" onclick="closeReceiptModal()" aria-label="Close">
      <i class="fa-solid fa-xmark"></i>
    </button>

    <div class="modal__header">
      <div class="icon-badge"><i class="fa-solid fa-receipt"></i></div>
      <h2 id="receipt-modal-title">Your Receipt</h2>
      <p>Print or save your receipt.</p>
    </div>

    <div class="receipt-container" id="receipt-container" aria-label="Receipt">
      <div class="receipt-actions">
        <button type="button" class="btn btn--primary btn--sm" onclick="printReceipt()">
          <i class="fa-solid fa-print"></i> Print Receipt
        </button>
      </div>

      <div class="receipt" id="receipt">
        <div class="receipt-header">
          <div class="receipt-title">Dough LEYcious Receipt</div>
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
            <?php foreach ($items as $item) { ?>
              <div class="receipt-item">
                <span><?php echo htmlspecialchars($item['name']); ?> &times; <?php echo $item['qty']; ?></span>
                <span>₱<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
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

<script>
  localStorage.removeItem("cart");
</script>

<?php include("site_footer.php"); ?>
