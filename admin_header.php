<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require("database.php");

    if (!isset($_SESSION['username'])) {
        header("Location: admin_login.php");
        exit();
    }
    
    $execLow = $con->query("SELECT * FROM tbl_products
                            WHERE is_hidden = 0 AND stock <= low_stock_level
                            ORDER BY stock");
?>

<a href="destroy_session.php">
    <button>Logout</button>
</a>

<h1>Welcome, Kirsten!</h1>

<p>
    <a href="admin_dashboard.php">Orders</a> |
    <a href="admin_products.php">Products</a>
</p>

<?php if ($execLow->num_rows > 0) { ?>
    <h2>Low Stock Alert</h2>
    <ul>
        <?php while ($low = $execLow->fetch_assoc()) { ?>
            <li>
                <?php echo htmlspecialchars($low['product_name']); ?> -
                <?php echo $low['stock']; ?> left
                (alert level: <?php echo $low['low_stock_level']; ?>)
            </li>
        <?php } ?>
    </ul>
<?php } ?>