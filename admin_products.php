<?php
    session_start();
    require("database.php");

    if (!isset($_SESSION['username'])) {
        header("Location: admin_login.php");
        exit();
    }

    if (isset($_POST['update_price'])) {
        $product_id = (int) $_POST['product_id'];
        $price = (float) $_POST['price'];

        $con->query("UPDATE tbl_products SET price = $price WHERE product_id = $product_id");
        header("Location: admin_products.php");
        exit();
    }

    if (isset($_POST['hide_product'])) {
        $product_id = (int) $_POST['product_id'];

        $con->query("UPDATE tbl_products SET is_hidden = 1 WHERE product_id = $product_id");
        header("Location: admin_products.php");
        exit();
    }

    if (isset($_POST['restore_product'])) {
        $product_id = (int) $_POST['product_id'];

        $con->query("UPDATE tbl_products SET is_hidden = 0 WHERE product_id = $product_id");
        header("Location: admin_products.php");
        exit();
    }

    if (isset($_POST['delete_product'])) {
        $product_id = (int) $_POST['product_id'];

        $con->query("DELETE FROM tbl_products WHERE product_id = $product_id");
        header("Location: admin_products.php");
        exit();
    }

    if (isset($_POST['update_stock'])) {
        $product_id = (int) $_POST['product_id'];
        $stock = (int) $_POST['stock'];
        $low_stock_level = (int) $_POST['low_stock_level'];

        $con->query("UPDATE tbl_products SET stock = $stock, low_stock_level = $low_stock_level WHERE product_id = $product_id");
        header("Location: admin_products.php");
        exit();
    }

    $execProducts = $con->query("SELECT * FROM tbl_products
                                 WHERE is_hidden = 0
                                 ORDER BY category, product_name");

    $execStock = $con->query("SELECT * FROM tbl_products
                              WHERE is_hidden = 0
                              ORDER BY category, product_name");

    $execHidden = $con->query("SELECT * FROM tbl_products
                               WHERE is_hidden = 1
                               ORDER BY category, product_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <?php include("admin_header.php"); ?>

    <h2>Product Management</h2>

    <table border="1">
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Action</th>
        </tr>

        <?php if ($execProducts->num_rows > 0) { ?>
            <?php while ($product = $execProducts->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td>
                        <form method="POST" action="admin_products.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            ₱<input type="number" name="price" step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                            <input type="submit" name="update_price" value="Update Price">
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="admin_products.php"
                              onsubmit="return confirm('Hide this item? You can restore it later.');">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="submit" name="hide_product" value="Hide">
                        </form>

                        <form method="POST" action="admin_products.php"
                              onsubmit="return confirm('PERMANENTLY delete this item? This cannot be undone!');">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="submit" name="delete_product" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">No products found.</td>
            </tr>
        <?php } ?>
    </table>

    <h2>Inventory Tracker</h2>

    <table border="1">
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Status</th>
            <th>Stock / Alert Level</th>
        </tr>

        <?php if ($execStock->num_rows > 0) { ?>
            <?php while ($product = $execStock->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td>
                        <?php if ($product['stock'] <= $product['low_stock_level']) { ?>
                            LOW STOCK
                        <?php } else { ?>
                            OK
                        <?php } ?>
                    </td>
                    <td>
                        <form method="POST" action="admin_products.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            Stock: <input type="number" name="stock" min="0" value="<?php echo $product['stock']; ?>" required>
                            Alert at: <input type="number" name="low_stock_level" min="0" value="<?php echo $product['low_stock_level']; ?>" required>
                            <input type="submit" name="update_stock" value="Update Stock">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4">No products found.</td>
            </tr>
        <?php } ?>
    </table>

    <h2>Hidden Products</h2>

    <table border="1">
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Action</th>
        </tr>

        <?php if ($execHidden->num_rows > 0) { ?>
            <?php while ($product = $execHidden->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td>₱<?php echo number_format($product['price'], 2); ?></td>
                    <td>
                        <form method="POST" action="admin_products.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="submit" name="restore_product" value="Restore">
                        </form>

                        <form method="POST" action="admin_products.php"
                              onsubmit="return confirm('PERMANENTLY delete this item? This cannot be undone!');">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="submit" name="delete_product" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4">No hidden products.</td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>