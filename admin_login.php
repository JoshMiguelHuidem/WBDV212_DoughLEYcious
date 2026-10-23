<?php
    session_start();

    if (isset($_SESSION['username'])) {
        header("Location: admin_dashboard.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
</head>
<body>
    <table>
        <form action="login_process.php" method="POST">
            <tr>
                <td>Username</td>
                <td>
                    <input type="text" name="username" required>
                </td>
            </tr>
            <tr>
                <td>Password</td>
                <td>
                    <input type="password" name="password" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Log In">
                </td>
            </tr>
        </form>
    </table>
</body>
</html>