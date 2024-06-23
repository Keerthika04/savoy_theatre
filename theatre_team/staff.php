<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 0) {
    if ($_SESSION['user_type'] != 0) {
        header("Location: movies.php");
    } else {
        header("Location: index.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="./css/dashboard.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="flex">
        <div class="sidebar">
            <div class="fixed">
                <div class="logo">
                    <img src="../Images/logo.png" alt="Logo" />
                </div>
                <ul class="menu">
                    <li>
                        <a href="movies.php">
                            <span>Movies</span>
                        </a>
                    </li>
                    <li>
                        <a href="showtime.php">
                            <span>Shows</span>
                        </a>
                    </li>
                    <li>
                        <a href="booking.php">
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="feedback.php">
                            <span>Feedback</span>
                        </a>
                    </li>
                    <li>
                        <a href="user.php">
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="staff.php" class="active">
                            <span>Staffs</span>
                        </a>
                    </li>
                    <li>
                        <a href="promotion.php">
                            <span>Promotions</span>
                        </a>
                    </li>
                    <li>
                        <a href="user_profile.php">
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <i class="fa fa-sign-out"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="staff_details_admin">
            <?php
            if (isset($_SESSION['alert_message'])) {
                echo "<div class='alert alert-success mt-3'>" . htmlspecialchars($_SESSION['alert_message']) . "</div>";
                unset($_SESSION['alert_message']);
            } ?>

            <div class="registering_staffs p-2">
                <!-- Register New Staff modal -->
                <div class="modal-header">
                    <h2>Register New Staff</h2>
                </div>
                <div class="modal-body">
                    <form id="userForm" action="staff.php" method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="first_name">First Name:</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" required placeholder="First Name" autocomplete="off">
                            </div>

                            <div class="form-group col ml-4">
                                <label for="last_name">Last Name:</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" required placeholder="Last Name" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col">
                                <label for="phone_no">Phone Number:</label>
                                <input type="text" id="phone_no" name="phone_no" class="form-control" required placeholder="+94*********" autocomplete="off">
                            </div>

                            <div class="form-group col ml-4">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required placeholder="johndoe@gmail.com" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" required placeholder="Username" autocomplete="off">
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required placeholder="Password">
                            </div>

                            <div class="form-group col ml-4">
                                <label for="confirm_password">Confirm Password:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Password">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            require "../php/db_connection.php";

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirm_password"]) && isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["phone_no"]) && isset($_POST["email"])) {

                $username = $_POST["username"];
                $password = $_POST["password"];
                $confirm_password = $_POST["confirm_password"];
                $first_name = $_POST["first_name"];
                $last_name = $_POST["last_name"];
                $phone_no = $_POST["phone_no"];
                $email = $_POST["email"];
                $user_type = 1;

                $errors = [];

                // Check if username is already taken
                $sql = "SELECT username FROM users WHERE username = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $errors[] = "Username is already taken!";
                }
                $stmt->close();

                if (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
                    $errors[] = "First name should contain only letters!";
                }

                if (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
                    $errors[] = "Last name should contain only letters!";
                }

                if (!preg_match("/^\+[0-9]+$/", $phone_no) || !(strlen($phone_no) == 12)) {
                    $errors[] = "Phone number should contain only 12 numbers and include the country code!";
                }

                if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/\W/", $password)) {
                    $errors[] = "Password should be at least 8 characters long, include at least one uppercase letter and one special character.";
                }

                if ($password !== $confirm_password) {
                    $errors[] = "Passwords do not match! Try again!";
                }

                if (empty($errors)) {

                    $otp = rand(100000, 999999);
                    $_SESSION['otp'] = $otp;
                    $_SESSION['mail'] = $email;

                    require "../php/Mail/phpmailer/PHPMailerAutoload.php";
                    $mail = new PHPMailer(true);

                    $mail->SMTPDebug = 2;

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Port = 587;
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'tls';

                    $mail->Username = 'jeyandrankeerthika5@gmail.com';
                    $mail->Password = 'mojfidopvduiutfs';

                    $mail->setFrom('jeyandrankeerthika5@gmail.com', 'OTP Verification');
                    $mail->addAddress($_POST["email"]);

                    $mail->isHTML(true);
                    $mail->Subject = "Your Savoy Theatre One Time Password (OTP) for Verification";
                    $mail->Body = "<p>Dear " . $first_name . " " . $last_name . ", <br> <br>Welcome to Savoy Theatre!. To ensure the security of your Savoy Theatre account, we need to verify your email address using a One Time Password (OTP).</p> <h4>Your verification OTP code is $otp </h4>
                    <br>
                    <p>Please use this OTP to complete the verification process and gain access to your account. Remember, for your security, do not share this OTP with anyone.
                    <br>If you have any questions or encounter any issues during the login process, please don't hesitate to reach out to our support team at Savoy Theatre Support.
                    <br>Thank you for choosing Savoy Theatre and Wishing you the Best!</p>
                    <b>From Savoy Theatre</b>";

                    if (!$mail->send()) {
            ?>
                        <script>
                            alert("<?php echo "Register Failed, Invalid Email " ?>");
                        </script>
            <?php
                    } else {
                        $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
                        $user_data = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'username' => $username,
                            'password' => $password_hashed,
                            'email' => $email,
                            'phone_number' => $phone_no,
                            'user_type' => $user_type
                        );
                        $_SESSION['user_data'] = $user_data;

                        echo "<script>alert('Successfully sent the OTP to $email'); window.location.replace('php/verification.php');</script>";
                    }
                } else {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger mt-3'>$error</div>";
                    }
                }
            } ?>

            <div class="card-body mt-5" id="staffSection">

                <div class="staff_head">
                    <h2>
                        STAFF's DETAILS
                    </h2>
                    <div class="search_box admin_search">
                        <form action="" method="GET">
                            <input type="text" name="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <?php
                    require "../php/db_connection.php";
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_staff_id"])) {
                        $staffIdToDelete = $_POST["delete_staff_id"];
                        $deleteQuery = $db->query("DELETE FROM `users` WHERE user_id = '$staffIdToDelete' AND user_type = 1");
                        if ($deleteQuery) {
                        } else {
                            echo "Error deleting staff.";
                        }
                    }

                    $searchQuery = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';

                    if ($searchQuery) {
                        $searchSQL = "user_id LIKE '%$searchQuery%' OR 
                        username LIKE '%$searchQuery%' OR 
                        password LIKE '%$searchQuery%' OR 
                        first_name LIKE '%$searchQuery%' OR 
                        last_name LIKE '%$searchQuery%' OR 
                        phone_no LIKE '%$searchQuery%' OR 
                        email LIKE '%$searchQuery%'";

                        $query = $db->query("SELECT * FROM users WHERE  user_type = 1 AND ($searchSQL) ORDER BY user_id DESC");
                    } else {
                    $query = $db->query("SELECT * FROM users WHERE user_type = 1 ORDER BY user_id DESC");
                    }

                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            echo "<div class='col-lg-4'>";
                            echo "<div class='card mb-4'>";
                            echo "<form method='post' action='staff.php' class='card-header' onsubmit=\"return confirm('Are you sure?');\">";
                            echo "<input type='hidden' name='delete_staff_id' value='" . $row["user_id"] . "'>";
                            echo "<button type='submit' class='btn btn-danger delete-btn'><i class='fas fa-trash-alt'></i></button>";
                            echo "</form>";
                            echo "<div class='card-body'>";
                            echo "<p class='card-text'><strong>Username:</strong> " . $row["username"] . "</p>";
                            echo "<p class='card-text'><strong>First Name:</strong> " . $row["first_name"] . "</p>";
                            echo "<p class='card-text'><strong>Last Name:</strong> " . $row["last_name"] . "</p>";
                            echo "<p class='card-text'><strong>Phone No:</strong> " . $row["phone_no"] . "</p>";
                            echo "<p class='card-text'><strong>Email:</strong> " . $row["email"] . "</p>";
                            echo "<p class='card-text'><strong>User Type:</strong> " . ($row["user_type"] == 1 ? "Staff" : "User") . "</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='w-100 mx-4'>";
                        echo "<div class='card mb-4'>";
                        echo "<div class='card-body'>";
                        echo "<h4>Empty Staff Details</h4>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById("userModal");
        const btn = document.querySelector(".addNewStaff");
        const span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });
</script>

</html>