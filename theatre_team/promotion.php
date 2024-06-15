<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 0) {
    if ($_SESSION['user_type'] != 0) {
        header("Location: movies.php");
    } else {
        header("Location: index.php");
    }
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
                        <a href="movies.php">
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
                            <a href="promotion.php" class="active">
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

        <div class="promotion_admin">
            <div class="card-body" id="promotion_Section">
                <div class="promotion_head">
                    <h2>
                        PROMOTION DETAILS
                    </h2>
                    <button class="addPromotion">Add Promotion</button>
                </div>

                <div class="row">
                    <?php
                    require "../php/db_connection.php";
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_banners_id"])) {
                        $bannersIdToDelete = $_POST["delete_banners_id"];
                        $deleteQuery = $db->query("DELETE FROM banner WHERE banner_id = '$bannersIdToDelete'");
                        if ($deleteQuery) {
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit();
                        } else {
                            echo "Error deleting movie.";
                        }
                    }


                    $query = $db->query(" SELECT banner.banner_id, banner.banner_poster, banner.movie_id, movies.movie_title
                                            FROM banner 
                                            JOIN movies ON banner.movie_id = movies.movie_id ORDER BY banner_id DESC");


                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            echo "<div class='w-100 mx-4'>";
                            echo "<div class='card mb-4'>";
                            echo "<form method='post' class='card-header' onsubmit=\"return confirm('Are you sure?');\">";
                            echo "<input type='hidden' name='delete_banners_id' value='" . $row["banner_id"] . "'>";
                            echo "<button type='submit' class='btn btn-danger delete-btn'><i class='fas fa-trash-alt'></i></button>";
                            echo "</form>";
                            echo "<div class='card-body'>";
                            echo "<h3>" . $row["movie_title"] . "</h3>
                                  <h4>Banner ID: " . $row["banner_id"] . "</h4>";
                            echo "<img src='" . "../uploaded_banner_images/" . $row["banner_poster"] . "' class='card-img-top cardImg' alt='Poster'>";

                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='w-100 mx-4'>";
                        echo "<div class='card mb-4'>";
                        echo "<div class='card-body'>";
                        echo "<h4>No Booking available.</h4>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Promotions Modal -->
    <div id="promotionModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Promotion</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="promotionForm" action="php/add_promotion.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="movie">Movie:</label>
                            <select name="movie_id" id="movie" class="form-control" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="banner_poster">Banner Poster:</label>
                            <input type="file" id="banner_poster" name="banner_poster" class="form-control-file" required>
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

        const modal = document.getElementById("promotionModal");
        const btn = document.querySelector(".addPromotion");
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