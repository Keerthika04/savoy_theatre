<?php
session_start();
require 'db_connection.php';


if (isset($_SESSION['alert_message'])) {
    echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($_SESSION['alert_message']) . "</div>";
    unset($_SESSION['alert_message']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <form id="loginForm" action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="mt-3">
            <a href="register.php" class="btn btn-secondary">Register</a>
            <a href="forgot_password.php" class="btn btn-secondary">Forgot Password</a>
            <a href="../index.php" class="btn btn-secondary">Back</a>
        </div>
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT user_id, username, password FROM users WHERE username = ?";

            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param("s", $username);
                $stmt->execute();

                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($user_id, $username, $user_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $user_password)) {
                            session_start();

                            $_SESSION['user_id'] = $user_id;
                            $_SESSION['username'] = $username;

                            if ($_SESSION['booking'] == true) {
                                header("Location: booking.php");
                                exit();
                            } else {
                                header("Location: ../index.php");
                                exit();
                            }
                        } else {
                            echo "<div class='alert alert-danger mt-3'>The password you entered is not valid!</div>";
                        }
                    }
                } else {
                    echo "<div class='alert alert-danger mt-3'>Invalid username or password!</div>";
                }
            } else {
                echo "<div class='alert alert-danger mt-3'>Oops! Something went wrong. Please try again later!</div>";
            }

            $stmt->close();
        }

        ?>
    </div>
</body>

</html>