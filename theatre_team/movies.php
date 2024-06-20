<?php
session_start();

// Made sure only authorized users have access and can't be accessed by url change
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="./css/dashboard.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="flex">
        <div class="sidebar">
            <div class="fixed">
                <div class="logo">
                    <img src="../Images/logo.png" alt="Logo" />
                </div>
                <ul class="menu">
                    <li>
                        <a href="movies.php" class="active">
                            <span>Movies</span>
                        </a>
                    </li>
                    <li>
                        <a href="showtime.php">
                            <span>Shows</span>
                        </a>
                    </li>
                    <li>
                        <a href="booking.php">
                            <span>Bookings</span>
                        </a>
                    </li>
                    <!-- To make sure staffs dont have access to specific pages  -->
                    <?php if ($_SESSION['user_type'] != 1) { ?>
                        <li>
                            <a href="feedback.php">
                                <span>Feedback</span>
                            </a>
                        </li>
                        <li>
                            <a href="user.php">
                                <span>Users</span>
                            </a>
                        </li>
                        <li>
                            <a href="staff.php">
                                <span>Staffs</span>
                            </a>
                        </li>
                        <li>
                            <a href="promotion.php">
                                <span>Promotions</span>
                            </a>
                        </li>
                    <?php }; ?>
                    <li>
                        <a href="user_profile.php">
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <i class="fa fa-sign-out"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="movies_details_admin">
            <!-- Alert messages -->
            <?php
            if (isset($_SESSION['alert_message'])) {
                echo "<div class='alert alert-success mt-3'>" . htmlspecialchars($_SESSION['alert_message']) . "</div>";
                unset($_SESSION['alert_message']);
            } ?>
            <!-- Movie Details -->
            <div class="card-body" id="moviesSection">
                <div class="movie_head">
                    <h2>
                        MOVIES DETAILS
                    </h2>
                    <div class="search_box admin_search">
                        <form action="" method="GET">
                            <input type="text" name="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                    <!-- This feature is only meant for admins -->
                    <?php
                    if ($_SESSION['user_type'] == 0) { ?>
                        <button class="addMovie">Add Movie</button>
                    <?php  } ?>
                </div>

                <div class="row">
                    <?php
                    require "../php/db_connection.php";

                    // Deleting
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_movie_id"])) {
                        $movieIdToDelete = $_POST["delete_movie_id"];
                        $deleteQuery = $db->query("DELETE FROM movies WHERE movie_id = '$movieIdToDelete'");
                        if ($deleteQuery) {
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit();
                        } else {
                            echo "Error deleting movie.";
                        }
                    }

                    // Searching
                    $searchQuery = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';

                    if ($searchQuery) {
                        $searchSQL = "movie_id LIKE '%$searchQuery%' OR 
              movie_title LIKE '%$searchQuery%' OR 
              language LIKE '%$searchQuery%' OR 
              movie_cast LIKE '%$searchQuery%' OR 
              genre LIKE '%$searchQuery%' OR 
              rating LIKE '%$searchQuery%' OR 
              duration LIKE '%$searchQuery%' OR 
              released_date LIKE '%$searchQuery%'";
                        $query = $db->query("SELECT * FROM movies WHERE ($searchSQL) ORDER BY movie_id DESC");
                    } else {

                        $query = $db->query("SELECT * FROM movies ORDER BY movie_id DESC");
                    }

                    // Movie Card Details
                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            echo "<div class='col-lg-4'>";
                            echo "<div class='card mb-4'>";
                            if ($_SESSION['user_type'] == 0) {
                                echo "<form method='post' class='card-header' onsubmit=\"return confirm('Are you sure?');\">";
                                echo "<input type='hidden' name='delete_movie_id' value='" . $row["movie_id"] . "'>";
                                echo "<button class='btn btn-success edit-btn mr-2'><i class='fas fa-edit'></i></button>";
                                echo "<button type='submit' class='btn btn-danger delete-btn'><i class='fas fa-trash-alt'></i></button>";
                                echo "</form>";
                            }
                            echo "<img src='" . "../uploaded_movie_posters/" . $row["movie_poster"] . "' class='card-img-top cardImg' alt='Poster'>";
                            echo "<div class='card-body'>";
                            echo "<div class='card-content'>
                            <img src='../uploaded_card_images/" . $row["movie_card_poster"] . "' class='card-img-top cardImg' alt='Poster'>
                                         <h3 class='card-title'>" . $row["movie_title"] . "</h3>
                                    </div>";
                            echo "<p class='card-text'><strong>Language:</strong> " . $row["language"] . "</p>";
                            echo "<p class='card-text'><strong>Cast:</strong> " . $row["movie_cast"] . "</p>";
                            echo "<p class='card-text'><strong>Genre:</strong> " . $row["genre"] . "</p>";
                            echo "<p class='card-text'><strong>Story Plot:</strong> " . $row["storyplot"] . "</p>";
                            echo "<p class='card-text'><strong>Rating:</strong> " . $row["rating"] . "</p>";
                            echo "<p class='card-text'><strong>Duration:</strong> " . $row["duration"] . "</p>";
                            echo "<p class='card-text'><strong>Release Date:</strong> " . $row["released_date"] . "</p>";
                            echo "<p class='card-text'><strong>Current Movies:</strong> " . $row["current_movies"] . "</p>";
                            echo "<p class='card-text'><strong>Upcoming Movies:</strong> " . $row["upcoming_movies"] . "</p>";
                            echo "<a href='" . $row["movie_trailer"] . "' target='_blank' class='btn btn-primary'>Watch Trailer</a>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='w-100 mx-4'>";
                        echo "<div class='card mb-4'>";
                        echo "<div class='card-body'>";
                        echo "<h4>No Movie Found</h4>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Add New Movie modal -->
    <div id="movieModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Movie</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="movieForm" action="php/add_movie.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="movie_title">Movie Title:</label>
                            <input type="text" id="movie_title" name="movie_title" class="form-control" required placeholder="The Garfield Movie">
                        </div>

                        <div class="form-group">
                            <label for="movie_card_poster">Movie Card Poster:</label>
                            <input type="file" id="movie_card_poster" name="movie_card_poster" class="form-control-file" required>
                        </div>

                        <div class="form-group">
                            <label for="movie_poster">Movie Poster:</label>
                            <input type="file" id="movie_poster" name="movie_poster" class="form-control-file" required>
                        </div>

                        <div class="form-group">
                            <label for="language">Language:</label>
                            <input type="text" id="language" name="language" class="form-control" placeholder="English" required>
                        </div>

                        <div class="form-group">
                            <label for="movie_cast">Movie Cast:</label>
                            <input type="text" id="movie_cast" name="movie_cast" class="form-control" required placeholder="Samuel L. Jackson [ACTOR],Chris Pratt [ACTOR],Ving Rhames [ACTOR],Cecily Strong [ACTOR],Nicholas Hoult [ACTOR],Hannah Waddingham [ACTOR],Mark Dindal [DIRECTOR],John Cohen [PRODUCER],Broderick Johnson [PRODUCER],Andrew Kosove [PRODUCER]">
                        </div>

                        <div class="form-group">
                            <label for="genre">Genre:</label>
                            <input type="text" id="genre" name="genre" class="form-control" required placeholder="Adventure / Comedy / Animation">
                        </div>

                        <div class="form-group">
                            <label for="storyplot">Story Plot:</label>
                            <textarea id="storyplot" name="storyplot" class="form-control" required placeholder="Garfield, the world-famous, Monday-hating, lasagna-loving indoor cat, is about to have a wild outdoor adventure. After an unexpected reunion with his long-lost father - scruffy street cat Vic- Garfield and his canine friend Odie are forced from their perfectly pampered life into joining Vic in a hilarious, high-stakes heist. "></textarea>
                        </div>

                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <input type="number" id="rating" name="rating" class="form-control" required max=5 placeholder="Max rating is 5">
                        </div>

                        <div class="form-group">
                            <label for="movie_trailer">Movie Trailer URL:</label>
                            <input type="text" id="movie_trailer" name="movie_trailer" class="form-control" required placeholder="https://youtu.be/IeFWNtMo1Fs?si=0TFKOdzfffXKcOYs">
                        </div>

                        <div class="form-group">
                            <label for="duration">Duration:</label>
                            <input type="text" id="duration" name="duration" class="form-control" required placeholder="1hr 41mins">
                        </div>

                        <div class="form-group">
                            <label for="released_date">Released Date:</label>
                            <input type="date" id="released_date" name="released_date" class="form-control" required>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" id="current_movies" name="current_movies" class="form-check-input">
                            <label for="current_movies" class="form-check-label">Current Movies</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" id="upcoming_movies" name="upcoming_movies" class="form-check-input">
                            <label for="upcoming_movies" class="form-check-label">Upcoming Movies</label>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById("movieModal");
        const btn = document.querySelector(".addMovie");
        const span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });
</script>

</html>