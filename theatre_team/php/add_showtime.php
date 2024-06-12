<?php
session_start();
require "../../php/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $query = $db->query("SELECT show_id FROM showtimes ORDER BY show_id DESC LIMIT 1");
    $newID = "s001";

    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $last_id = $row['show_id'];
        $num = (int) substr($last_id, 1) + 1;
        $newID = "s" . str_pad($num, 3, "0", STR_PAD_LEFT);
    }
    
        $movie_id = $_POST['movie_id'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        echo $movie_id;
    
        $query = "INSERT INTO showtimes (show_id, movie_id, date, time) VALUES (?, ?, ?, ?)";
    
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param("ssss", $newID, $movie_id, $date, $time);
            
            if ($stmt->execute()) {
                $_SESSION['alert_message'] = "New showtime added successfully";
                header("Location: ../showtime.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $db->error;
        }
    
}

?>