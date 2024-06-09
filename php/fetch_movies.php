<?php
session_start();
require 'php/db_connection.php';
$query = $db->query("SELECT 	movie_id, 	movie_title, movie_card_poster, duration, released_date FROM movies where 	current_movies = 1");

$currentMovies = array();

if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $currentMovies[] = $row;
    }
}

$query = $db->query("SELECT 	movie_id, 	movie_title, movie_card_poster, duration, released_date FROM movies where 	upcoming_movies = 1");

$upcomingMovies = array();

if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $upcomingMovies[] = $row;
    }
}

echo json_encode($currentMovies);
?>