<?php
session_start();
require 'db_connection.php';

// Stores the retrived data
$movie = $_POST['movie'];
$date = $_POST['date'];
$time = $_POST['time'];
$seats = $_POST['seats'];
$adults = $_POST['adults'];
$children = $_POST['children'];
$parking = $_POST['parking'];
$totalPrice = $_POST['totalPrice'];

// If user selects any vehicle for parking the parking id will be given for that booking
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
    $stmt->close();
}

// Retrieves the id to generate the new booking Id based on the last id
$query = $db->query("SELECT booking_id FROM booking ORDER BY booking_id DESC LIMIT 1");
$newID = "b0001";

if ($query->num_rows > 0) {
    $row = $query->fetch_assoc();
    $last_id = $row['booking_id'];
    $num = (int) substr($last_id, 1) + 1;
    $newID = "b" . str_pad($num, 4, "0", STR_PAD_LEFT);
}

// Gets the show id based on the selected date and time
$query = "SELECT show_id FROM showtimes WHERE date =? AND time =?";
$stmt = $db->prepare($query);
$stmt->bind_param("ss", $date, $time);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$showID = $row['show_id'];
$stmt->close();

// Inserts the booking details
$query = "INSERT INTO booking (booking_id, customer_id, showtime_id, adult, children, total, seats, parking_id) VALUES (?,?,?,?,?,?,?,?)";
$stmt = $db->prepare($query);
$stmt->bind_param("ssssssss", $newID, $_SESSION['user_id'], $showID, $adults, $children, $totalPrice, $seats, $newParkingID);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['alert_message'] = "Successfully Booked!";
    echo "Successfully Booked!";
} else {
    echo "Error: " . $db->error . "Booking Failed";
}
$stmt->close();
