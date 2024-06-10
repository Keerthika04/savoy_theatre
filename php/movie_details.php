<?php
session_start();
require 'db_connection.php';

if (isset($_GET['action']) && $_GET['action'] == 'check_session') {
    if (isset($_SESSION['user_id'])) {
        header("Location: user_profile.php");
        exit();
    } else {
        header("Location: login.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/movie_detailssss.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <nav class="header" id="navbar">
        <div class="sub_navbar flex_align_center">
            <div class="left_nav">
                <div class="logo">
                    <img src="../Images/logo.png" alt="Logo" />
                </div>
                <ul class="nav_links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="movies.php">Movies</a></li>
                    <li><a href="theatre_info.php">Theatre</a></li>
                </ul>
            </div>
            <div class="right_nav">
                <a href="booking.php" class="buy_tickets">
                    <img src="../Images/tickets.png" alt="Ticket Icon" class="ticket_icon" />
                    Buy Movie Tickets
                </a>
                <div class="menu_icon" onclick="toggleMenu()">
                    <i class="fas fa-bars"></i>
                </div>
                <a href="?action=check_session"><i class="fa-solid fa-user user_profile"></i></a>
            </div>
        </div>
        <?php

        $_SESSION['movie_id'] = $_GET['movie'];

        $movie_id = $_SESSION['movie_id'];

        $query = $db->query("SELECT * FROM movies Where movie_id = '$movie_id'");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                $imageURL = '../uploaded_movie_posters/' . $row["movie_poster"];

                if ($row["upcoming_movies"] == 0):
                    ?>
                    <div class="nav_movie" id="nav_movie">
                        <div class="left">
                            <h2><?php echo htmlspecialchars($row["movie_title"]); ?></h2>
                            <h4><span>Duration :</span> <?php echo htmlspecialchars($row["duration"]); ?></h4>
                        </div>
                        <div class="right">
                            <a href="booking.php?movie=<?php echo htmlspecialchars($row["movie_id"]); ?>">Buy Tickets</a>
                        </div>
                    </div>
                <?php endif; ?>
            </nav>

            <section class="banner">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide movie_poster">
                            <div class="black_overlay"></div>
                            <img src="<?php echo $imageURL; ?>" alt="" />
                            <div class="movie_detail">
                                <h1 class="with_language"><?php echo htmlspecialchars($row["movie_title"]); ?></h1>
                                <h3>Language - <?php echo htmlspecialchars($row["language"]); ?></h3>

                                <?php if ($row["upcoming_movies"] == 0): ?>
                                    <a href="booking.php?movie=<?php echo htmlspecialchars($row["movie_id"]); ?>">Buy Tickets</a>
                                <?php endif; ?>

                                <a href="<?php echo htmlspecialchars($row["movie_trailer"]); ?>" class="border_btn">Watch
                                    Trailer</a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <section class="movie_details">
                <div class="about_movie">
                    <div class="left">
                        <h1><span>Duration :</span> <?php echo htmlspecialchars($row["duration"]); ?></h1>
                        <h1><span>Released on :</span> <?php echo htmlspecialchars($row["released_date"]); ?></h1>
                        <h1><?php echo htmlspecialchars($row["genre"]); ?></h1>
                    </div>
                    <div class="right">
                        <p><?php echo htmlspecialchars($row["storyplot"]); ?></p>
                    </div>
                </div>
                <div class="about_cast">
                    <div class="left">
                        <h1>Cast & Crew</h1>
                    </div>
                    <div class="right">
                        <?php
                        $sqlData = $row["movie_cast"];
                        $sqlData = preg_replace('/\s*,\s*/', ',', trim($sqlData));
                        $items = explode(',', $sqlData);

                        foreach ($items as $item) {
                            preg_match('/(.*?)(\s*\[.*\])/', $item, $matches);
                            $name = $matches[1];
                            $role = $matches[2];

                            echo "<h1>" . htmlspecialchars($name) . "<span> &nbsp" . htmlspecialchars($role) . "</span></h1>";
                        }
                        ?>
                    </div>
                </div>
            </section>

        <?php }
        } ?>

</body>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="../js/script.js"></script>
</html>