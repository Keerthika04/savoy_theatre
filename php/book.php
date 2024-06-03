<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    echo "<div class='alert alert-danger mt-3'>You have to login to book!</div>";
    $_SESSION['booking'] = true;
}

$name = $_SESSION['username']; 
$mobile = $_SESSION['phone_no']; 
$movie = $_POST['movie'];
$date = $_POST['date'];
$time = $_POST['time'];
$seats = $_POST['seats'];
$adults = $_POST['adults'];
$children = $_POST['children'];
$parking = $_POST['parking'];
$totalPrice = $_POST['totalPrice'];

// Generate booking ID
$query = "SELECT MAX(CAST(SUBSTRING(booking_id, 2, LENGTH(booking_id)-1) AS UNSIGNED)) AS maxID FROM booking";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$maxID = $row['maxID'];
$newID = 'b' . str_pad($maxID + 1, 4, '0', STR_PAD_LEFT);

$query = "INSERT INTO booking (booking_id, customer_id, showtime_id, date, adult, children, total, parking_id, seats) VALUES ('$newID', '$_SESSION[customer_id]', '$movie', '$date', '$time', '$seats', '$adults', '$children', '$parking', '$totalPrice')";
if (mysqli_query($conn, $query)) {
    echo "Booking successful!";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
