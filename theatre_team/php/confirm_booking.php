<?php
session_start();
require "../../php/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $bookingId = $_POST['confirm_booking_id'];
    
    $stmt = $db->prepare("UPDATE booking SET confirm_booking = 1 WHERE booking_id = ?");
    $stmt->bind_param("s", $bookingId);
    
    if ($stmt->execute()) {
        header("Location: ../booking.php");
        exit();
    } else {
        echo 'Error confirming booking.';
    }
    $stmt->close();
    
}

?>