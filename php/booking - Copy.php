<?php
session_start();
require 'db_connection.php';

session_destroy();

if (isset($_POST['movie'])) {
    $_SESSION['movie_id'] = $_POST['movie'];
    header("Location: booking.php?movie=".$_SESSION['movie_id']);
}

$movie_id = $_SESSION['movie_id'] ?? $_GET['movie'] ;

if ($movie_id) {
    $_SESSION['movie_id'] = $movie_id;
    $selected_movie = getMovieDetails($db, $movie_id);
    $imageURL = '../uploaded_movie_posters/' . $selected_movie["movie_poster"];
} else {
    header("Location: ../index.php");
}

function getMovieDetails($db, $movie_id) {
    $query = $db->query("SELECT * FROM movies WHERE movie_id = '$movie_id'");
    return $query->fetch_assoc();
}

$all_movies_query = $db->query("SELECT * FROM movies WHERE movie_id != '$movie_id'");
$movies = [];
while ($row = $all_movies_query->fetch_assoc()) {
    $movies[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <nav class="booking_head_nav">
        <a href=""><i class="fa-solid fa-circle-chevron-left" style="color: #ffffff; font-size: 1.5rem;"></i></a>
        <h1 id="movieTitle"><?php echo htmlspecialchars($selected_movie['movie_title']); ?></h1>
    </nav>
    <div id="movieBackground" style="background-image: url('<?php echo $imageURL; ?>');" class="movie_booking_page">
        <div class="black_overlay"></div>
    </div>
    <section class="booking_head">
        <form method="POST" id="movieSelectForm">
            <label for="movieSelect">Selected Movie:</label>
            <select class="form-control" id="movieSelect" name="movie" onchange="document.getElementById('movieSelectForm').submit();">
                <option value="<?php echo htmlspecialchars($selected_movie['movie_id']); ?>">
                    <?php echo htmlspecialchars($selected_movie['movie_title']); ?></option>
                <?php foreach ($movies as $movie): ?>
                    <option value="<?php echo htmlspecialchars($movie['movie_id']); ?>">
                        <?php echo htmlspecialchars($movie['movie_title']); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </section>
    <section class="movie_details">
        <p><strong>Duration:</strong> <span id="movieDuration"><?php echo htmlspecialchars($selected_movie['duration']); ?></span></p>
        <p><strong>Genre:</strong> <span id="movieGenre"><?php echo htmlspecialchars($selected_movie['genre']); ?></span></p>
        <p><strong>Description:</strong> <span id="movieDescription"><?php echo htmlspecialchars($selected_movie['storyplot']); ?></span></p>
    </section>
</body>

</html>
