<?php
session_start();
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
                        <a href="movies.php">
                            <span>Movies</span>
                        </a>
                    </li>
                    <li>
                        <a href="showtime.php" class="active">
                            <span>Shows</span>
                        </a>
                    </li>
                    <li>
                        <a href="booking.php">
                            <span>Bookings</span>
                        </a>
                    </li>
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

        <div class="showtime_details_admin">
            <?php
            if (isset($_SESSION['alert_message'])) {
                echo "<div class='alert alert-success mt-3'>" . htmlspecialchars($_SESSION['alert_message']) . "</div>";
                unset($_SESSION['alert_message']);
            } ?>
            <div class="card-body" id="moviesSection">
                <div class="showtime_head">
                    <h2>
                        SHOWTIME DETAILS
                    </h2>
                    <div class="search_box admin_search">
                        <form action="" method="GET">
                            <input type="text" name="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                    <button class="addShow">Add Show Time</button>
                </div>

                <div class="responsive-table">
                    <?php
                    require "../php/db_connection.php";
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_show_id"])) {
                        $showIdToDelete = $_POST["delete_show_id"];
                        $deleteQuery = $db->query("DELETE FROM showtimes WHERE show_id = '$showIdToDelete'");
                        if ($deleteQuery) {
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit();
                        } else {
                            echo "Error deleting showtime.";
                        }
                    }

                    $searchQuery = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';

                    if ($searchQuery) {
                        $searchSQL = "movies.movie_title LIKE '%$searchQuery%' OR 
                                      movies.language LIKE '%$searchQuery%' OR 
                                      showtimes.show_id LIKE '%$searchQuery%' OR 
                                      showtimes.date LIKE '%$searchQuery%' OR 
                                      showtimes.time LIKE '%$searchQuery%'";

                        $query = $db->query("SELECT movies.movie_title, movies.language, showtimes.show_id, showtimes.date, showtimes.time FROM showtimes JOIN movies ON showtimes.movie_id = movies.movie_id WHERE ($searchSQL) ORDER BY showtimes.date DESC");
                    } else {
                        $query = $db->query("SELECT movies.movie_title, movies.language, showtimes.show_id, showtimes.date, showtimes.time FROM showtimes JOIN movies ON showtimes.movie_id = movies.movie_id  ORDER BY showtimes.date ASC");
                    }

                    if ($query->num_rows > 0) {
                        echo "<table border='1' class='table table-bordered' width='100%' cellspacing='0'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Show ID</th>";
                        echo "<th>Movie Title</th>";
                        echo "<th>Language</th>";
                        echo "<th>Date</th>";
                        echo "<th>Time</th>";
                        echo "<th class='text-center'>Edit</th>";
                        echo "<th class='text-center'>Delete</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        while ($row = $query->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["show_id"] . "</td>";
                            echo "<td>" . $row["movie_title"] . "</td>";
                            echo "<td>" . $row["language"] . "</td>";
                            echo "<td>" . $row["date"] . "</td>";
                            echo "<td>" . $row["time"] . "</td>";
                            echo "<form method='post' onsubmit=\"return confirm('Are you sure?');\">";
                            echo "<input type='hidden' name='delete_show_id' value='" . $row["show_id"] . "'>";
                            echo "<td class='text-center'><button class='btn btn-warning edit-btn'><i class='fas fa-edit'></i></button></td>";
                            echo "<td class='text-center'><button type='submit' class='btn btn-danger delete-btn'><i class='fas fa-trash-alt'></i></button></td>";
                            echo "</form>";
                            echo "</tr>";
                        }
                    }else {
                        echo "<div class='w-100 mx-4'>";
                        echo "<div class='card mb-4'>";
                        echo "<div class='card-body'>";
                        echo "<h4>No Showtime Found</h4>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Add New Showtimes modal -->
    <div id="showModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New ShowTime</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="showtimeForm" action="php/add_showtime.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="movie">Movie:</label>
                            <select name="movie_id" id="movie" class="form-control" required>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" id="date" class="form-control" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="time">Time:</label>
                            <select id="time" name="time" class="form-control" required>
                                <option value="09.00am">09.00am</option>
                                <option value="12.30pm">12.30pm</option>
                                <option value="04.30pm">04.30pm</option>
                                <option value="06.30pm">06.30pm</option>
                                <option value="09.00pm">09.00pm</option>
                            </select>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        function fetchMovies() {
            $.ajax({
                url: 'php/fetch_movies.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var movieSelect = $('#movie');
                    movieSelect.empty();
                    $.each(data, function(index, movie) {
                        movieSelect.append('<option value="' + movie.movie_id + '">' + movie.movie_title + '</option>');
                    });
                },
                error: function() {
                    alert('Failed to fetch movies');
                }
            });
        }

        fetchMovies();

        const modal = document.getElementById("showModal");
        const btn = document.querySelector(".addShow");
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