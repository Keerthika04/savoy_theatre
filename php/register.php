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
        <!-- Registration form -->
        <div class="register-box">
            <h2 class="register-title">Register</h2>
            <form id="registrationForm" action="register.php" method="post">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required autocomplete="off">
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
                    <input type="password" class="form-control" id="password" name="password" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required autocomplete="off" placeholder="yourmail@gmail.com">
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="+94*********" autocomplete="off">
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
            session_start();
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

                // Stores the error if it encountered any
                $errors = [];

                // Check if username already exists
                $sql = "SELECT username FROM users";
                $result = $db->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if ($row["username"] === $username) {
                            $errors[] = "Username is already taken!";
                        }
                    }
                }

                // Validates first name and last name should only have letters
                if (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
                    $errors[] = "First name should contain only letters!";
                }

                if (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
                    $errors[] = "Last name should contain only letters!";
                }

                // Validates the phone number, it should have 12 numbers with country code and numbers only
                if (!preg_match("/^\+[0-9]+$/", $phone_number) || !(strlen($phone_number) == 12)) {
                    $errors[] = "Phone number should contain only 12 numbers and include the country code!";
                }

                // Validates the password, it should be 8 characters long and should have 1 Caps and Symbol
                if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/\W/", $password)) {
                    $errors[] = "Password should be at least 8 characters long, include at least one uppercase letter and one special character.";
                }

                // Checks whether the password and confirm password matches
                if ($password !== $confirm_password) {
                    $errors[] = "Passwords do not match! Try again!";
                }

                  // If there are no errors, then proceed with mailing OTP 
                if (empty($errors)) {

                    $otp = rand(100000, 999999);
                    $_SESSION['otp'] = $otp;
                    $_SESSION['mail'] = $email;

                    // Send OTP through email using PHPMailer
                    require "Mail/phpmailer/PHPMailerAutoload.php";
                    $mail = new PHPMailer(true);

                    // Set to 2 for debug info
                    $mail->SMTPDebug = 2;

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // SMTP host
                    $mail->Port = 587; // SMTP port
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'tls'; // Enable TLS encryption

                    $mail->Username = 'jeyandrankeerthika5@gmail.com';
                    $mail->Password = 'mojfidopvduiutfs';

                    $mail->setFrom('jeyandrankeerthika5@gmail.com', 'OTP Verification');
                    $mail->addAddress($_POST["email"]);

                    $mail->isHTML(true);
                    $mail->Subject = "Your Savoy Theatre One Time Password (OTP) for Verification";
                    $mail->Body = "<p>Dear " . $first_name . " " . $last_name . ", <br> <br>Welcome to Savoy Theatre! We're thrilled to have you onboard. To ensure the security of your Savoy Theatre account, we need to verify your email address using a One Time Password (OTP).</p> <h4>Your verification OTP code is $otp </h4>
                    <br>
                    <p>Please use this OTP to complete the verification process and gain access to your account. Remember, for your security, do not share this OTP with anyone.
                    <br>If you have any questions or encounter any issues during the login process, please don't hesitate to reach out to our support team at Savoy Theatre Support.
                    <br>Thank you for choosing Savoy Theatre. We look forward to providing you with an exceptional experience!</p>
                    <b>From Savoy Theatre</b>";

                    if (!$mail->send()) {
            ?>
                        <script>
                            alert("<?php echo "Register Failed, Invalid Email " ?>");
                        </script>
                    <?php
                    } else {
                    ?>
                        <script>
                            <?php
                            $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
                            // To execute insert statement after verification
                            $user_data = array(
                                'first_name' => $first_name,
                                'last_name' => $last_name,
                                'username' => $username,
                                'password' => $password_hashed,
                                'email' => $email,
                                'phone_number' => $phone_number,
                                'user_type' => $user_type
                            );
                            $_SESSION['user_data'] = $user_data;
                            ?>

                            alert("<?php echo "Successfully sent the OTP to " . $email ?>");
                            window.location.replace('verification.php');
                        </script>
            <?php
                    }
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