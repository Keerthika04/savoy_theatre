<?php
session_start();
require 'php/db_connection.php';

if (isset($_GET['action']) && $_GET['action'] == 'check_session') {
  if (isset($_SESSION['user_id'])) {
    header("Location: php/user_profile.php");
    exit();
  } else {
    header("Location: php/login.php");
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
  <link rel="icon" href="./Images/favicon.png" type="image/png" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
  <nav class="navbar flex_align_center" id="navbar">
    <div class="left_nav">
      <div class="logo">
        <img src="Images/logo.png" alt="Logo" />
      </div>
      <ul class="nav_links">
        <li><a href="index.php">Home</a></li>
        <li><a href="movies.html">Movies</a></li>
        <li><a href="theatre.html">Theatre</a></li>
      </ul>
    </div>
    <!-- <div class="search_box">
        <input type="text" placeholder="Search" />
        <i class="fas fa-search"></i>
      </div> -->
    <div class="right_nav">
      <a href="./php/booking.php" class="buy_tickets">
        <img src="Images/tickets.png" alt="Ticket Icon" class="ticket_icon" />
        Buy Movie Tickets
      </a>
      <div class="menu_icon" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
      </div>

      <a href="?action=check_session"><i class="fa-solid fa-user user_profile"></i></a>
    </div>
  </nav>

  <section class="banner">
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <?php

        $query = $db->query("SELECT banner.banner_poster, movie_title, movies.movie_id FROM banner join movies ON banner.movie_id = movies.movie_id");

        if ($query->num_rows > 0) {
          while ($row = $query->fetch_assoc()) {
            $imageURL = 'uploaded_banner_images/' . $row["banner_poster"];
            ?>
            <div class="swiper-slide">
              <img src="<?php echo $imageURL; ?>" alt="" />
              <div class="movie_detail">
                <h1><?php echo htmlspecialchars($row["movie_title"]); ?></h1>
                <a href="php/booking.php?movie=<?php echo htmlspecialchars($row["movie_id"]); ?>">Buy Tickets</a>
                <a href="php/movie_details.php?movie=<?php echo htmlspecialchars($row["movie_id"]); ?>"
                  class="border_btn">More Info</a>
              </div>
            </div>
          <?php }
        } ?>
      </div>

      <div class="swiper-pagination"></div>
    </div>
  </section>
</body>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

</html>