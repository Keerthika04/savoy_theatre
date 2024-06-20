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
                        <a href="booking.php" class="active">
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

        <div class="booking_details_admin">
            <div class="card-body" id="booking_Section">
                <div class="booking_head">
                    <h2>
                        BOOKING DETAILS
                    </h2>
                    <div class="search_box admin_search">
                        <form action="" method="GET">
                            <input type="text" name="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <?php
                    require "../php/db_connection.php";
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_booking_id"])) {
                        $bookingIdToDelete = $_POST["delete_booking_id"];
                        $deleteQuery = $db->query("DELETE FROM booking WHERE booking_id = '$bookingIdToDelete'");
                        if ($deleteQuery) {
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit();
                        } else {
                            echo "Error deleting movie.";
                        }
                    }

                    $searchQuery = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';

                    if ($searchQuery) {
                        $searchSQL = "booking.booking_id LIKE '%$searchQuery%' OR 
                                      booking.adult LIKE '%$searchQuery%' OR 
                                      booking.children LIKE '%$searchQuery%' OR 
                                      booking.total LIKE '%$searchQuery%' OR 
                                      booking.seats LIKE '%$searchQuery%' OR 
                                      (CASE WHEN booking.confirm_booking = 1 THEN 'Confirmed' ELSE 'Pending' END) LIKE '%$searchQuery%' OR 
                                      users.username LIKE '%$searchQuery%' OR 
                                      showtimes.date LIKE '%$searchQuery%' OR 
                                      showtimes.time LIKE '%$searchQuery%' OR 
                                      parking.vehicle LIKE '%$searchQuery%' OR 
                                      movies.movie_id LIKE '%$searchQuery%' OR 
                                      movies.movie_title LIKE '%$searchQuery%'";
                        $query = $db->query("SELECT booking.booking_id, booking.adult, 
                                            booking.children, booking.total, booking.seats, booking.confirm_booking, 
                                            users.username, showtimes.date AS show_date, showtimes.time AS show_time, 
                                            parking.vehicle,  movies.movie_id, movies.movie_title
                                            FROM booking 
                                            JOIN users ON booking.customer_id = users.user_id
                                            JOIN showtimes ON booking.showtime_id = showtimes.show_id
                                            JOIN parking ON booking.parking_id = parking.parking_id 
                                            JOIN movies ON showtimes.movie_id = movies.movie_id WHERE ($searchSQL) 
                                            ORDER BY booking_id DESC");
                    } else {
                    $query = $db->query(" SELECT booking.booking_id, booking.adult, 
                                            booking.children, booking.total, booking.seats, booking.confirm_booking, 
                                            users.username, showtimes.date AS show_date, showtimes.time AS show_time, 
                                            parking.vehicle,  movies.movie_id, movies.movie_title
                                            FROM booking 
                                            JOIN users ON booking.customer_id = users.user_id
                                            JOIN showtimes ON booking.showtime_id = showtimes.show_id
                                            JOIN parking ON booking.parking_id = parking.parking_id 
                                            JOIN movies ON showtimes.movie_id = movies.movie_id ORDER BY booking_id DESC");
                    }

                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            echo "<div class='w-100 mx-4'>";
                            echo "<div class='card mb-4'>";
                            echo "<form method='post' class='card-header' onsubmit=\"return confirm('Are you sure?');\">";
                            echo "<input type='hidden' name='delete_booking_id' value='" . $row["booking_id"] . "'>";
                            echo "<button type='submit' class='btn btn-danger delete-btn'><i class='fas fa-trash-alt'></i></button>";
                            echo "</form>";
                            echo "<div class='card-body'>";
                            echo "<h3>" . $row["movie_title"] . "</h3>
                                  <h4>Booking ID: " . $row["booking_id"] . "</h4>";

                            echo "<table class='table table-bordered'>";
                            echo "<tr><td><strong>Booked By:</strong></td><td>" . $row["username"] . "</td></tr>";
                            echo "<tr><td><strong>Show Date:</strong></td><td>" . $row["show_date"] . "</td></tr>";
                            echo "<tr><td><strong>Show Time:</strong></td><td>" . $row["show_time"] . "</td></tr>";
                            echo "<tr><td><strong>Vehicle:</strong></td><td>" . $row["vehicle"] . "</td></tr>";
                            echo "<tr><td><strong>Adults:</strong></td><td>" . $row["adult"] . "</td></tr>";
                            echo "<tr><td><strong>Children:</strong></td><td>" . $row["children"] . "</td></tr>";
                            echo "<tr><td><strong>Total Amount:</strong></td><td>" . $row["total"] . "</td></tr>";
                            echo "<tr><td><strong>Seats:</strong></td><td>" . $row["seats"] . "</td></tr>";
                            echo "<tr><td><strong>Confirmation:</strong></td><td>" . ($row["confirm_booking"] ? 'Confirmed' : 'Pending') . "</td></tr>";
                            echo "</table>";
                            echo"<div class='modal-footer'>";
                            if (!$row["confirm_booking"]) {
                                echo "<form method='post' action='php/confirm_booking.php'onsubmit=\"return confirm('Are you sure?');\">";
                                echo "<input type='hidden' name='confirm_booking_id' value='" . $row["booking_id"] . "'>";
                                echo "<button  class='btn btn-primary'>Confirm</button>";
                                echo "</form>";
                            }else{
       
                                echo "<div class='alert alert-success'> Confirmed </div>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }else {
                        echo "<div class='w-100 mx-4'>";
                        echo "<div class='card mb-4'>";
                        echo "<div class='card-body'>";
                        echo "<h4>No Booking Available</h4>";
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