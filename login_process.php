<?php
    session_start();
    require("database.php");

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT *
            FROM 
                tbl_admin
            WHERE 
                username = '$username' AND password = '$password'";

    $exec = $con->query($query); 

    if ($exec->num_rows > 0) {
        $_SESSION['username'] = $username;
        ?>
            <script>
                alert("Login successful!");
                window.location.href = "admin_dashboard.php";
            </script>
        <?php
    } else {
        ?>
            <script>
                alert("Login failed!");
                window.location.href = "admin_login.php";
            </script>
        <?php
    }
?>