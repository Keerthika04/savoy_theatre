<?php
session_start();
require 'db_connection.php';

$date = $_POST['date'];
$time = $_POST['time'];

function getBookedSeats($db, $date, $time)
{
    $query = "SELECT seats FROM booking WHERE showtime_id IN (SELECT show_id FROM showtimes WHERE date = ? AND time = ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookedSeats = [];
    while ($row = $result->fetch_assoc()) {
        $seats = explode(',', $row['seats']);
        $bookedSeats = array_merge($bookedSeats, $seats);
    }
    $stmt->close();
    return $bookedSeats;
}

$bookedSeats = getBookedSeats($db, $date, $time);

echo json_encode($bookedSeats);
?>
