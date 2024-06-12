<?php
require "../../php/db_connection.php";

$query = "SELECT movie_id, movie_title FROM movies";
$result = $db->query($query);

$movies = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error fetching movies"]);
    exit();
}

echo json_encode($movies);
?>