<?php
session_start();
require 'db_connection.php';

$movie = $_POST['movie'];
$date = $_POST['date'];
$time = $_POST['time'];
$seats = $_POST['seats'];
$adults = $_POST['adults'];
$children = $_POST['children'];
$parking = $_POST['parking'];
$totalPrice = $_POST['totalPrice'];

if ($parking !== "No Parking") {
    $query = $db->query("SELECT parking_id FROM parking ORDER BY parking_id DESC LIMIT 1");
    $newParkingID = "p0001";

    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $last_id = $row['parking_id'];
        $num = (int) substr($last_id, 1) + 1;
        $newParkingID = "p" . str_pad($num, 4, "0", STR_PAD_LEFT);
    }

    $query = "INSERT INTO parking (parking_id, vehicle) VALUES (?,?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $newParkingID, $parking);
    $stmt->execute();
}

$query = $db->query("SELECT booking_id FROM booking ORDER BY booking_id DESC LIMIT 1");
$newID = "b0001";

if ($query->num_rows > 0) {
    $row = $query->fetch_assoc();
    $last_id = $row['booking_id'];
    $num = (int) substr($last_id, 1) + 1;
    $newID = "b" . str_pad($num, 4, "0", STR_PAD_LEFT);
}

$query = "SELECT show_id FROM showtimes WHERE date =? AND time =?";
$stmt = $db->prepare($query);
$stmt->bind_param("ss", $date, $time);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$showID = $row['show_id'];

$query = "INSERT INTO booking (booking_id, customer_id, showtime_id, date, adult, children, total, seats, parking_id) VALUES (?,?,?,?,?,?,?,?,?)";
$stmt = $db->prepare($query);
$stmt->bind_param("sssssssss", $newID, $_SESSION['user_id'], $showID, $date, $adults, $children, $totalPrice, $seats, $newParkingID);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Booking successful!";
} else {
    echo "Error: " . $db->error;
}
?>