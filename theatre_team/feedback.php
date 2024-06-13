<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="./css/css.css" />
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
                        <a href="admin.php">
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
                    <li>
                        <a href="feedback.php" class="active">
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
                    <li>
                        <a href="logout.php">
                            <i class="fa fa-sign-out"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="booking_details_admin">
            <div class="card-body" id="booking_Section">
                <div class="booking_head">
                    <h2>
                        Booking DETAILS
                    </h2>
                </div>

                <div class="row">
                    <?php
                    require "../php/db_connection.php";
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_feedback_id"])) {
                        $feedbackIdToDelete = $_POST["delete_feedback_id"];
                        $deleteQuery = $db->query("DELETE FROM movie_feedbacks WHERE feedback_id = '$feedbackIdToDelete'");
                        if ($deleteQuery) {
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit();
                        } else {
                            echo "Error deleting movie.";
                        }
                    }

                    $query = $db->query("SELECT movie_feedbacks.feedback_id, movie_feedbacks.feedbacks, users.username, movies.movie_id, movies.movie_title
                                         FROM movie_feedbacks
                                         JOIN users ON movie_feedbacks.user_id = users.user_id
                                         JOIN movies ON movie_feedbacks.movie_id = movies.movie_id
                                         ORDER BY feedback_id DESC");
                                         
                                         $current_movie_id = null;
                                         if ($query->num_rows > 0) {
                                             while ($row = $query->fetch_assoc()) {
                                                 if ($current_movie_id != $row["movie_id"]) {
                                                     if ($current_movie_id != null) {
                                                         echo "</table>";
                                                         echo "</div>";
                                                         echo "</div>";
                                                         echo "</div>";
                                                     }
                                                     // Card for new movie
                                                     $current_movie_id = $row["movie_id"];
                                                     echo "<div class='w-100 mx-4'>";
                                                     echo "<div class='card mb-4'>";
                                                     echo "<div class='card-body'>";
                                                     echo "<h3>" . $row["movie_title"] . "</h3>";
                                                     echo "<table class='table table-bordered'>";
                                                 }
                                                 // Display feedback for that movie
                                                 echo "<tr><td class='width'><strong>Feedback By:</strong> " . $row["username"] . "</td><td class='width'>" . $row["feedbacks"] . "</td>";                       
                                                 echo "<form method='post' class='card-header' onsubmit=\"return confirm('Are you sure?');\">";
                                                 echo "<input type='hidden' name='delete_feedback_id' value='" . $row["feedback_id"] . "'>";
                                                 echo "<td class='text-center width'><button type='submit' class='btn btn-danger delete-btn'><i class='fas fa-trash-alt'></i></button></td>";
                                                 echo "</form></tr>";
                                             }
                                             // Close the last card
                                             echo "</table>";
                                             echo "</div>";
                                             echo "</div>";
                                             echo "</div>";
                                         } else {
                                             echo "<div class='w-100 mx-4'>";
                                             echo "<div class='card mb-4'>";
                                             echo "<div class='card-body'>";
                                             echo "<h4>No feedback available.</h4>";
                                             echo "</div>";
                                             echo "</div>";
                                             echo "</div>";
                                         }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>