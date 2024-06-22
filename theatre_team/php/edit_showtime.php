<?php
session_start();
require "../../php/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $show_id = $_POST['show_id'];
    $movie_id = $_POST['movie_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $db->prepare("UPDATE showtimes SET movie_id = ?, date = ?, time = ? WHERE show_id = ?");
    $stmt->bind_param("ssss", $movie_id, $date, $time, $show_id);

    if ($stmt->execute()) {
        $_SESSION['alert_message'] = "Showtime updated successfully!";
    } else {
        $_SESSION['alert_message'] = "Error updating showtime. Please try again.";
    }

    $stmt->close();
    header("Location: ../showtime.php");
    exit();
}
?>
