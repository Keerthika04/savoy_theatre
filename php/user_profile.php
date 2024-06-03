<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT first_name, last_name, username, email, phone_no FROM users WHERE user_id = ?";
if ($stmt = $db->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['user_id']);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div class="container mt-5">
        <h2>Profile</h2>
        <table class="table table-bordered">
            <tr>
                <th>First Name</th>
                <td><?php echo htmlspecialchars($first_name); ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo htmlspecialchars($last_name); ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?php echo htmlspecialchars($username); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($email); ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo htmlspecialchars($phone_number); ?></td>
            </tr>
        </table>
        <a href="../index.php" class="btn btn-primary">Back to Home</a>
        <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</html>