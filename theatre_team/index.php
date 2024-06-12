<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="css/admin-Login.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="login-title">Login</h2>
            <form id="loginForm" action="index.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-1 btn-login"> <span></span>
                    <span></span>
                    <span></span>
                    <span></span>Login</button>
            </form>

            <?php
            session_start();
            require '../php/db_connection.php';


            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $sql = "SELECT user_id, username, password FROM users WHERE username = ? AND user_type = 0";

                if ($stmt = $db->prepare($sql)) {
                    $stmt->bind_param("s", $username);
                    $stmt->execute();

                    $stmt->store_result();

                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($user_id, $username, $user_password);
                        if ($stmt->fetch()) {
                            if (password_verify($password, $user_password)) {
                                header("Location: admin.php");
                                exit();
                            } else {
                                echo "<div class='alert alert-danger text-center mt-3'>The password you entered is not valid!</div>";
                            }
                        }
                    } else {
                        echo "<div class='alert alert-danger text-center mt-3'>Invalid username or password!</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger text-center mt-3'>Oops! Something went wrong. Please try again later!</div>";
                }

                $stmt->close();
            }

            ?>
        </div>
    </div>
</body>
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

</html>