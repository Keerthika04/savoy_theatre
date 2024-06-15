<?php
session_start();
require '../php/db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT first_name, last_name, username, email, phone_no FROM users WHERE username = ?";
if ($stmt = $db->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name, $username, $email, $phone_number);
    $stmt->fetch();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <div class="theater-screen"></div>
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
                    <?php if ($_SESSION['user_type'] != 1) { ?>
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
                            <a href="staff.php">
                                <span>Staffs</span>
                            </a>
                        </li>
                    <li>
                        <a href="promotion.php">
                            <span>Promotions</span>
                        </a>
                    </li>
                    <?php }; ?>
                    <li>
                        <a href="user_profile.php" class="active">
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
        <div class="staff_user_profile">
            <div class="container">
                <h2>Welcome, <?php echo htmlspecialchars($first_name . " " . $last_name); ?>!</h2>
                <div class="welcome-message">
                    <p>Here are your details:</p>
                    <table class="table-bordered table">
                        <tr>
                            <th><i class="fas fa-user"></i> &nbsp; First Name</th>
                            <td><?php echo htmlspecialchars($first_name); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user"></i> &nbsp; Last Name</th>
                            <td><?php echo htmlspecialchars($last_name); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user"></i> &nbsp; Username</th>
                            <td><?php echo htmlspecialchars($username); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope"></i> &nbsp; Email</th>
                            <td><?php echo htmlspecialchars($email); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone"></i> &nbsp; Phone Number</th>
                            <td><?php echo htmlspecialchars($phone_number); ?></td>
                        </tr>
                    </table>
                </div>
                <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</html>