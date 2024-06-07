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
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/user-profile.css">
</head>

<body>
    <div class="theater-screen"></div>
    <div class="container mt-5">
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

        <h3>Booking Details</h3>
        <div class="booking-details">
            <?php

            $customer_id = $_SESSION['user_id'];

            $sql = "SELECT 
            m.movie_title AS movie_name,
            s.date AS show_date,
            s.time AS show_time,
            b.parking_id,
            b.seats AS booked_seats
        FROM 
            booking b
        JOIN 
            showtimes s ON b.showtime_id = s.show_id
        JOIN 
            movies m ON s.movie_id = m.movie_id
        WHERE 
            b.customer_id = ?
        ORDER BY 
            b.booking_id DESC";

            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param('s', $customer_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<table class = 'booking_table table'>";
                    echo "<thead>
                <th>Movie Name</th>
                <th>Show Date</th>
                <th>Show Time</th>
                <th>Parking ID</th>
                <th>Booked Seats</th>
              </thead>";

                    while ($row = $result->fetch_assoc()) {
                        $parking_id = $row["parking_id"] == Null ? " No Parking " : htmlspecialchars($row["parking_id"]);
                        echo "<tr>
                        <td>" . htmlspecialchars($row["movie_name"]) . "</td>
                        <td>" . htmlspecialchars($row["show_date"]) . "</td>
                        <td>" . htmlspecialchars($row["show_time"]) . "</td>
                        <td>" . $parking_id . "</td>
                        <td>" . htmlspecialchars($row["booked_seats"]) . "</td>
                      </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No bookings yet.</p>";
                }

                $stmt->close();
            } else {
                echo "Error: " . $db->error;
            }

            ?>
        </div>

        <a href="../index.php" class="btn btn-success"><i class="fas fa-home"></i> Back to Home</a>
        <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</html>