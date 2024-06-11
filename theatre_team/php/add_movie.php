<?php
session_start();
require "../../php/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $movie_title = $_POST['movie_title'];
    $language = $_POST['language'];
    $movie_cast = $_POST['movie_cast'];
    $genre = $_POST['genre'];
    $storyplot = $_POST['storyplot'];
    $rating = $_POST['rating'];
    $movie_trailer = $_POST['movie_trailer'];
    $duration = $_POST['duration'];
    $released_date = $_POST['released_date'];
    $current_movies = isset($_POST['current_movies']) ? 1 : 0;
    $upcoming_movies = isset($_POST['upcoming_movies']) ? 1 : 0;

    $movie_card_poster = $_FILES['movie_card_poster']['name'];
    $movie_poster = $_FILES['movie_poster']['name'];

    $card_poster_target = "../../uploaded_card_images/" . basename($movie_card_poster);
    $movie_poster_target = "../../uploaded_movie_posters/" . basename($movie_poster);

    if (move_uploaded_file($_FILES['movie_card_poster']['tmp_name'], $card_poster_target) && move_uploaded_file($_FILES['movie_poster']['tmp_name'], $movie_poster_target)) {
        $query = $db->query("SELECT movie_id FROM movies ORDER BY movie_id DESC LIMIT 1");
        $newID = "m001";

        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $last_id = $row['movie_id'];
            $num = (int) substr($last_id, 1) + 1;
            $newID = "m" . str_pad($num, 3, "0", STR_PAD_LEFT);
        }

        $sql = "INSERT INTO movies (movie_id,movie_title, movie_card_poster, movie_poster, language, movie_cast, genre, storyplot, rating, movie_trailer, duration, released_date, current_movies, upcoming_movies)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("ssssssssisssii", $newID, $movie_title, $movie_card_poster, $movie_poster, $language, $movie_cast, $genre, $storyplot, $rating, $movie_trailer, $duration, $released_date, $current_movies, $upcoming_movies);
            if ($stmt->execute()) {
                $_SESSION['alert_message'] = "New movie added successfully";
                header("Location: ../admin.php");
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your files.";
    }
}
