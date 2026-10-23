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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
</head>
<body>
    <?php include("admin_header.php"); ?>

    <h2>Active Orders</h2>

    <table border="1">
        <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Delivery</th>
            <th>Items</th>
            <th>Reference No.</th>
            <th>Notes</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php if ($execActive->num_rows > 0) { ?>
            <?php while ($row = $execActive->fetch_assoc()) { ?>
                <?php
                    $order_id = $row['order_id'];
                    $execItems = $con->query("SELECT * FROM tbl_order_items WHERE order_id = $order_id");
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['order_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo $row['delivery_date'] . " " . $row['delivery_time']; ?></td>
                    <td>
                        <?php while ($item = $execItems->fetch_assoc()) { ?>
                            <?php echo htmlspecialchars($item['product_name']); ?> × <?php echo $item['qty']; ?><br>
                        <?php } ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['proof_ref']); ?></td>
                    <td><?php echo htmlspecialchars($row['notes']); ?></td>
                    <td>₱<?php echo number_format($row['total'], 2); ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <form method="POST" action="admin_dashboard.php">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="submit" name="confirm_order" value="Confirm">
                            </form>
                            <form method="POST" action="admin_dashboard.php"
                                  onsubmit="return confirm('Decline this order?');">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="submit" name="decline_order" value="Decline">
                            </form>
                        <?php } else if ($row['status'] == 'Confirmed') { ?>
                            <form method="POST" action="admin_dashboard.php"
                                  onsubmit="return confirm('Mark this order as delivered?');">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="submit" name="deliver_order" value="Mark as Delivered">
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="11">No active orders.</td>
            </tr>
        <?php } ?>
    </table>

    <h2>Past Orders</h2>

    <table border="1">
        <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Delivery</th>
            <th>Items</th>
            <th>Reference No.</th>
            <th>Notes</th>
            <th>Total</th>
            <th>Status</th>
        </tr>

        <?php if ($execPast->num_rows > 0) { ?>
            <?php while ($row = $execPast->fetch_assoc()) { ?>
                <?php
                    $order_id = $row['order_id'];
                    $execItems = $con->query("SELECT * FROM tbl_order_items WHERE order_id = $order_id");
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['order_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo $row['delivery_date'] . " " . $row['delivery_time']; ?></td>
                    <td>
                        <?php while ($item = $execItems->fetch_assoc()) { ?>
                            <?php echo htmlspecialchars($item['product_name']); ?> × <?php echo $item['qty']; ?><br>
                        <?php } ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['proof_ref']); ?></td>
                    <td><?php echo htmlspecialchars($row['notes']); ?></td>
                    <td>₱<?php echo number_format($row['total'], 2); ?></td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="10">No past orders yet.</td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>