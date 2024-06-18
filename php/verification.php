<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="UTF-8">
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/verification.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="verification_container">
        <div class="otp">
            <h1>OTP Verification</h1>
            <form action="#" method="POST">
                <input type="text" id="otp" class="form-control" name="otp_code" required placeholder="Enter Your OTP">
                <input type="submit" value="Verify" name="verify" class="submit-btn">
            </form>
        </div>
    </div>
</body>

</html>
<?php
session_start();
require 'db_connection.php';

// Chceks the Input OTP code with session OTP
if (isset($_POST["verify"])) {
    $otp = $_SESSION['otp'];
    $email = $_SESSION['mail'];
    $otp_code = $_POST['otp_code'];

    if ($otp != $otp_code) {
?>
        <script>
            alert("Invalid OTP code");
        </script>
<?php
    } else {
        // Retrieves data from user_data array session
        $first_name = $_SESSION['user_data']['first_name'];
        $last_name = $_SESSION['user_data']['last_name'];
        $username = $_SESSION['user_data']['username'];
        $password = $_SESSION['user_data']['password'];
        $email = $_SESSION['user_data']['email'];
        $phone_number = $_SESSION['user_data']['phone_number'];
        $user_type = $_SESSION['user_data']['user_type'];

        // Generates new user_id
        $query = $db->query("SELECT user_id FROM users WHERE user_id LIKE 'c%' ORDER BY user_id DESC LIMIT 1");
        $new_customer_id = "c0001";

        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $last_id = $row['user_id'];
            $num = (int) substr($last_id, 1) + 1;
            $new_customer_id = "c" . str_pad($num, 4, "0", STR_PAD_LEFT);
        }

        // Insert user data into the database
        $sql = "INSERT INTO users (user_id, first_name, last_name, username, password, email, phone_no, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);

        $stmt->bind_param("sssssssi", $new_customer_id, $first_name, $last_name, $username, $password, $email, $phone_number, $user_type);

        if ($stmt->execute()) {

            // Clear and destroy session variables
            session_unset();
            session_destroy();
            session_start();

            // Redirect to login page with success message
            $_SESSION['success_alert_message'] = "Successfully Registered!";
            header("Location: login.php");
        } else {
            // Display error if database insertion fails
            echo "<div class='alert alert-danger mt-3'>Error: " . $stmt->error . "</div>";
        }


        $stmt->close();
    }
}


?>