<?php
session_start();
require "../../php/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $movieId = isset($_POST['confirm_current']) ? $_POST['confirm_current'] : (isset($_POST['confirm_finished']) ? $_POST['confirm_finished'] : (isset($_POST['confirm_upcoming']) ? $_POST['confirm_upcoming'] : null));

    if ($movieId) {
        if (isset($_POST["confirm_current"])) {
            $stmt = $db->prepare("UPDATE movies SET current_movies = 1, upcoming_movies = 0 WHERE movie_id = ?");
        } elseif (isset($_POST["confirm_finished"])) {
            $stmt = $db->prepare("UPDATE movies SET current_movies = 0, upcoming_movies = 0 WHERE movie_id = ?");
        } elseif (isset($_POST["confirm_upcoming"])) {
            $stmt = $db->prepare("UPDATE movies SET current_movies = 0, upcoming_movies = 1 WHERE movie_id = ?");
        } else {
            echo 'Invalid action.';
            exit();
        }

        $stmt->bind_param("s", $movieId);

        if ($stmt->execute()) {
            header("Location: ../movies.php");
            exit();
        } else {
            echo 'Error confirming booking.';
        }
        $stmt->close();
    } else {
        echo 'No movie ID provided.';
    }
}
