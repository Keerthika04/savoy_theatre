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
                    <li>
                        <a href="feedback.php">
                            <span>Feedback</span>
                        </a>
                    </li>
                    <li>
                        <a href="user.php" class="active">
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

        <div class="user_details_admin">
            <div class="card-body" id="user_Section">
                <div class="user_head">
                    <h2>
                        USER's DETAILS
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
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user_id"])) {
                        $userIdToDelete = $_POST["delete_user_id"];

                        $db->query("DELETE FROM movie_feedbacks WHERE user_id = '$userIdToDelete'");
                        $db->query("DELETE FROM booking WHERE customer_id = '$userIdToDelete'");
                        
                        $deleteQuery = $db->query("DELETE FROM users WHERE user_id = '$userIdToDelete'");
                        if ($deleteQuery) {
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit();
                        } else {
                            echo "Error deleting user.";
                        }
                    }

                    $searchQuery = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';

                    if ($searchQuery) {
                        $searchSQL = "user_id LIKE '%$searchQuery%' OR 
                        username LIKE '%$searchQuery%' OR 
                        password LIKE '%$searchQuery%' OR 
                        first_name LIKE '%$searchQuery%' OR 
                        last_name LIKE '%$searchQuery%' OR 
                        phone_no LIKE '%$searchQuery%' OR 
                        email LIKE '%$searchQuery%'";

                        $query = $db->query("SELECT * FROM users WHERE  user_type = 2 AND ($searchSQL) ORDER BY user_id DESC");
                    } else {
                        $query = $db->query("SELECT * FROM users WHERE user_type = 2 ORDER BY user_id DESC");
                    }

                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            echo "<div class='w-100 mx-4'>";
                            echo "<div class='card mb-4'>";
                            echo "<form method='post' class='card-header' onsubmit=\"return confirm('Are you sure?');\">";
                            echo "<input type='hidden' name='delete_user_id' value='" . $row["user_id"] . "'>";
                            echo "<button type='submit' class='btn btn-danger delete-btn'><i class='fas fa-trash-alt'></i></button>";
                            echo "</form>";
                            echo "<div class='card-body'>";
                            echo "<h3> Username: " . $row["username"] . "</h3>
                                  <h4>User ID: " . $row["user_id"] . "</h4>";

                            echo "<table class='table table-bordered'>";
                            echo "<tr><td><strong>First Name:</strong></td><td>" . $row["first_name"] . "</td></tr>";
                            echo "<tr><td><strong>Last Name:</strong></td><td>" . $row["last_name"] . "</td></tr>";
                            echo "<tr><td><strong>Phone No:</strong></td><td>" . $row["phone_no"] . "</td></tr>";
                            echo "<tr><td><strong>Email:</strong></td><td>" . $row["email"] . "</td></tr>";
                            echo "</table>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                            echo "<div class='w-100 mx-4'>";
                            echo "<div class='card mb-4'>";
                            echo "<div class='card-body'>";
                            echo "<h4>No Users Found</h4>";
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