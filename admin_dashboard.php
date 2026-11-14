<?php
    session_start();
    require("database.php");

    if (!isset($_SESSION['username'])) {
        header("Location: admin_login.php");
        exit();
    }

    if (isset($_POST['confirm_order'])) {
        $order_id = (int) $_POST['order_id'];

        $con->query("UPDATE tbl_orders SET status = 'Confirmed' WHERE order_id = $order_id");
        header("Location: admin_dashboard.php");
        exit();
    }

    if (isset($_POST['decline_order'])) {
        $order_id = (int) $_POST['order_id'];

        $con->query("UPDATE tbl_orders SET status = 'Declined' WHERE order_id = $order_id");
        header("Location: admin_dashboard.php");
        exit();
    }

    if (isset($_POST['deliver_order'])) {
        $order_id = (int) $_POST['order_id'];

        $con->query("UPDATE tbl_orders SET status = 'Delivered' WHERE order_id = $order_id");
        header("Location: admin_dashboard.php");
        exit();
    }

    $execActive = $con->query("SELECT * FROM tbl_orders
                               WHERE status = 'Pending' OR status = 'Confirmed'
                               ORDER BY created_at DESC");

    $execPast = $con->query("SELECT * FROM tbl_orders
                             WHERE status = 'Delivered' OR status = 'Declined'
                             ORDER BY created_at DESC");

    $statPending = $con->query("SELECT COUNT(*) AS total FROM tbl_orders WHERE status = 'Pending'")->fetch_assoc();
    $statConfirmed = $con->query("SELECT COUNT(*) AS total FROM tbl_orders WHERE status = 'Confirmed'")->fetch_assoc();
    $statDelivered = $con->query("SELECT COUNT(*) AS total FROM tbl_orders WHERE status = 'Delivered'")->fetch_assoc();
    $statRevenue = $con->query("SELECT COALESCE(SUM(total), 0) AS total FROM tbl_orders WHERE status = 'Delivered'")->fetch_assoc();

    $pageTitle = "Orders";
    $pageSubtitle = "Review new orders, confirm them and track every delivery.";
    $activePage = "admin_dashboard.php";
    include("admin_header.php");
?>

  <section class="stats" aria-label="Order summary">
    <article class="stat">
      <div class="icon-badge"><i class="fa-regular fa-bell"></i></div>
      <div>
        <p>New orders</p>
        <strong><?php echo $statPending['total']; ?></strong>
      </div>
    </article>
    <article class="stat">
      <div class="icon-badge"><i class="fa-solid fa-fire-burner"></i></div>
      <div>
        <p>In progress</p>
        <strong><?php echo $statConfirmed['total']; ?></strong>
      </div>
    </article>
    <article class="stat">
      <div class="icon-badge"><i class="fa-solid fa-truck-fast"></i></div>
      <div>
        <p>Delivered</p>
        <strong><?php echo $statDelivered['total']; ?></strong>
      </div>
    </article>
    <article class="stat">
      <div class="icon-badge"><i class="fa-solid fa-peso-sign"></i></div>
      <div>
        <p>Sales delivered</p>
        <strong>₱<?php echo number_format($statRevenue['total'], 2); ?></strong>
      </div>
    </article>
  </section>

  <div class="orders-grid">

    <section class="panel">
      <h2 class="panel__title">Active Orders <span class="count"><?php echo $execActive->num_rows; ?></span></h2>

      <div class="order-list">
        <?php if ($execActive->num_rows > 0) { ?>
          <?php while ($row = $execActive->fetch_assoc()) { ?>
            <?php
                $order_id = $row['order_id'];
                $execItems = $con->query("SELECT * FROM tbl_order_items WHERE order_id = $order_id");
            ?>
            <article class="order-card">
              <header class="order-card__head">
                <div>
                  <p class="order-card__no"><?php echo htmlspecialchars($row['order_number']); ?></p>
                  <p class="order-card__time">Placed <?php echo date("M j, Y g:i A", strtotime($row['created_at'])); ?></p>
                </div>
                <span class="status status--<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span>
              </header>

              <ul class="info-list">
                <li><i class="fa-regular fa-user"></i> <span><?php echo htmlspecialchars($row['customer_name']); ?></span></li>
                <li><i class="fa-solid fa-phone"></i> <a href="tel:<?php echo htmlspecialchars($row['phone']); ?>"><?php echo htmlspecialchars($row['phone']); ?></a></li>
                <li><i class="fa-solid fa-location-dot"></i> <span><?php echo htmlspecialchars($row['address']); ?></span></li>
                <li><i class="fa-regular fa-clock"></i> <span>Deliver on <?php echo date("M j, Y", strtotime($row['delivery_date'])); ?> at <?php echo date("g:i A", strtotime($row['delivery_time'])); ?></span></li>
                <li><i class="fa-solid fa-hashtag"></i> <span>Ref. no. <strong><?php echo htmlspecialchars($row['proof_ref']); ?></strong></span></li>
              </ul>

              <ul class="order-items">
                <?php while ($item = $execItems->fetch_assoc()) { ?>
                  <li>
                    <span><?php echo htmlspecialchars($item['product_name']); ?> <em>&times; <?php echo $item['qty']; ?></em></span>
                    <span>₱<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
                  </li>
                <?php } ?>
              </ul>

              <?php if ($row['notes'] != "") { ?>
                <p class="order-card__notes"><i class="fa-regular fa-comment-dots"></i> <?php echo htmlspecialchars($row['notes']); ?></p>
              <?php } ?>

              <footer class="order-card__foot">
                <p class="order-card__total"><span>Total</span> ₱<?php echo number_format($row['total'], 2); ?></p>

                <div class="actions-cell">
                  <?php if ($row['status'] == 'Pending') { ?>
                    <form method="POST" action="admin_dashboard.php">
                      <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                      <button type="submit" name="confirm_order" value="1" class="btn btn--primary btn--sm">
                        <i class="fa-solid fa-check"></i> Confirm
                      </button>
                    </form>
                    <form method="POST" action="admin_dashboard.php"
                          onsubmit="return confirm('Decline this order?');">
                      <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                      <button type="submit" name="decline_order" value="1" class="btn btn--ghost btn--sm">
                        <i class="fa-solid fa-xmark"></i> Decline
                      </button>
                    </form>
                  <?php } else if ($row['status'] == 'Confirmed') { ?>
                    <form method="POST" action="admin_dashboard.php"
                          onsubmit="return confirm('Mark this order as delivered?');">
                      <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                      <button type="submit" name="deliver_order" value="1" class="btn btn--soft btn--sm">
                        <i class="fa-solid fa-truck-fast"></i> Mark as Delivered
                      </button>
                    </form>
                  <?php } ?>
                </div>
              </footer>
            </article>
          <?php } ?>
        <?php } else { ?>
          <div class="empty-state">
            <i class="fa-regular fa-face-smile"></i>
            <p>No active orders right now.</p>
          </div>
        <?php } ?>
      </div>
    </section>

    <section class="panel">
      <h2 class="panel__title">Past Orders <span class="count"><?php echo $execPast->num_rows; ?></span></h2>

      <div class="order-list">
        <?php if ($execPast->num_rows > 0) { ?>
          <?php while ($row = $execPast->fetch_assoc()) { ?>
            <?php
                $order_id = $row['order_id'];
                $execItems = $con->query("SELECT * FROM tbl_order_items WHERE order_id = $order_id");
            ?>
            <article class="order-card order-card--past">
              <header class="order-card__head">
                <div>
                  <p class="order-card__no"><?php echo htmlspecialchars($row['order_number']); ?></p>
                  <p class="order-card__time">Placed <?php echo date("M j, Y g:i A", strtotime($row['created_at'])); ?></p>
                </div>
                <span class="status status--<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span>
              </header>

              <ul class="info-list">
                <li><i class="fa-regular fa-user"></i> <span><?php echo htmlspecialchars($row['customer_name']); ?></span></li>
                <li><i class="fa-solid fa-phone"></i> <span><?php echo htmlspecialchars($row['phone']); ?></span></li>
                <li><i class="fa-solid fa-location-dot"></i> <span><?php echo htmlspecialchars($row['address']); ?></span></li>
                <li><i class="fa-regular fa-clock"></i> <span><?php echo date("M j, Y", strtotime($row['delivery_date'])); ?> at <?php echo date("g:i A", strtotime($row['delivery_time'])); ?></span></li>
                <li><i class="fa-solid fa-hashtag"></i> <span>Ref. no. <strong><?php echo htmlspecialchars($row['proof_ref']); ?></strong></span></li>
              </ul>

              <ul class="order-items">
                <?php while ($item = $execItems->fetch_assoc()) { ?>
                  <li>
                    <span><?php echo htmlspecialchars($item['product_name']); ?> <em>&times; <?php echo $item['qty']; ?></em></span>
                    <span>₱<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
                  </li>
                <?php } ?>
              </ul>

              <?php if ($row['notes'] != "") { ?>
                <p class="order-card__notes"><i class="fa-regular fa-comment-dots"></i> <?php echo htmlspecialchars($row['notes']); ?></p>
              <?php } ?>

              <footer class="order-card__foot">
                <p class="order-card__total"><span>Total</span> ₱<?php echo number_format($row['total'], 2); ?></p>
              </footer>
            </article>
          <?php } ?>
        <?php } else { ?>
          <div class="empty-state">
            <i class="fa-regular fa-folder-open"></i>
            <p>No past orders yet.</p>
          </div>
        <?php } ?>
      </div>
    </section>

  </div>

<?php include("admin_footer.php"); ?>
