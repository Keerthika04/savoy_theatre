<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/register.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="scripts.js"></script>
</head>

<body>
    <div class="register-container">
        <div class="register-box">
            <h2 class="register-title">Register</h2>
            <form id="registrationForm" action="register.php" method="post">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required
                        autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required
                        autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                        autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required autocomplete="off" placeholder="yourmail@gmail.com">
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="+94*********"
                        autocomplete="off">
                </div>
                <button type="submit" class="btn btn-register">Register
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </form>

            <div class="login-link">
                <span>Already have an account? </span>
                <a href="login.php" class="login-link-text">Login here</a>
            </div>

            <?php
            require 'db_connection.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $email = $_POST['email'];
                $phone_number = $_POST['phone_number'];
                $user_type = 2;

                $errors = [];

                if (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
                    $errors[] = "First name should contain only letters!";
                }

                if (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
                    $errors[] = "Last name should contain only letters!";
                }

                if (!preg_match("/^\+[0-9]+$/", $phone_number)) {
                    $errors[] = "Phone number should contain only numbers and include the country code!";
                }

                if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/\W/", $password)) {
                    $errors[] = "Password should be at least 8 characters long, include at least one uppercase letter and one special character.";
                }

                if ($password !== $confirm_password) {
                    $errors[] = "Passwords do not match! Try again!";
                }

                if (empty($errors)) {
                    $query = $db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1");
                    $new_customer_id = "c001";

                    if ($query->num_rows > 0) {
                        $row = $query->fetch_assoc();
                        $last_id = $row['user_id'];
                        $num = (int) substr($last_id, 1) + 1;
                        $new_customer_id = "c" . str_pad($num, 4, "0", STR_PAD_LEFT);
                    }

                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users (user_id, first_name, last_name, username, password, email, phone_no, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $db->prepare($sql);

                    $stmt->bind_param("sssssssi", $new_customer_id, $first_name, $last_name, $username, $hashed_password, $email, $phone_number, $user_type);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success mt-3'>Registration successful! <a href='login.php'>Click here to login</a>.</div>";
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Error: " . $stmt->error . "</div>";
                    }


                    $stmt->close();
                } else {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger mt-3'>$error</div>";
                    }
                }
            }
            ?>
        </div>
    </div>
</body>

</html>