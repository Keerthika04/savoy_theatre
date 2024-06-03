<?php
session_start();
require 'db_connection.php';

// Set the movie_id from POST request or session
if (isset($_POST['movie'])) {
    $_SESSION['movie_id'] = $_POST['movie'];
    header("Location: booking.php?movie=" . $_SESSION['movie_id']);
    exit();
}

// Get the movie_id from session or GET request
$movie_id = $_SESSION['movie_id'] ?? $_GET['movie'] ?? null;

$selected_movie = null;
$all_movies = [];

// Fetch all movies for the dropdown
$sql_all_movies = "SELECT movie_id, movie_title FROM movies";
$result_all_movies = $db->query($sql_all_movies);
while ($row = $result_all_movies->fetch_assoc()) {
    $all_movies[] = $row;
}

if ($movie_id) {
    // Function to fetch movie details
    function getMovieDetails($db, $movie_id)
    {
        $sql = "SELECT movie_id, movie_title FROM movies WHERE movie_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Fetch details for the selected movie
    $selected_movie = getMovieDetails($db, $movie_id);

    if ($selected_movie) {
        // Prepare the SQL statement to fetch the selected movie's showtimes
        $sql = "
            SELECT 
                s.date AS date, s.time AS time 
            FROM 
                showtimes s 
            WHERE 
                s.movie_id = ? 
            ORDER BY 
                s.date, s.time";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $selected_movie['showtimes'] = [];
        while ($row = $result->fetch_assoc()) {
            $selected_movie['showtimes'][$row['date']][] = $row['time'];
        }

        $stmt->close();
    } else {
        echo "Error: Unable to fetch movie details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movies</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Select a Movie</h2>
    <form method="post" id="movieSelectForm">
        <div class="form-group">
            <label for="movie">Choose Movie:</label>
            <select class="form-control" id="movieSelect" name="movie"
                    onchange="document.getElementById('movieSelectForm').submit();">
                <option value="">--Select Movie--</option>
                <?php foreach ($all_movies as $movie_option): ?>
                    <option value="<?php echo $movie_option['movie_id']; ?>" <?php echo ($movie_option['movie_id'] == $movie_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($movie_option['movie_title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <?php if ($selected_movie): ?>
        <div class="movie">
            <h3><?php echo htmlspecialchars($selected_movie['movie_title']); ?></h3>

            <?php if (!empty($selected_movie['showtimes'])): ?>
                <h4>Select Date:</h4>
                <ul>
                    <?php foreach ($selected_movie['showtimes'] as $date => $showtimes): ?>
                        <li>
                            <?php echo htmlspecialchars($date); ?>:
                            <ul>
                                <?php foreach ($showtimes as $time): ?>
                                    <li><?php echo htmlspecialchars($time); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No showtimes available for this movie.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
