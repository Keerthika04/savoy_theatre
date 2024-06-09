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

$query = $db->query("SELECT movie_id, movie_title, movie_card_poster, duration, released_date FROM movies where 	current_movies = 1");

$currentMovies = array();

if ($query->num_rows > 0) {
  while ($row = $query->fetch_assoc()) {
    $currentMovies[] = $row;
  }
}

$query = $db->query("SELECT 	movie_id, 	movie_title, movie_card_poster, duration, released_date FROM movies where 	upcoming_movies = 1");

$upcomingMovies = array();

if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $upcomingMovies[] = $row;
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
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" /> -->
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
                <a href="php/movie_details.php?movie=<?php echo htmlspecialchars($row["movie_id"]); ?>" class="border_btn">More Info</a>
              </div>
            </div>
        <?php }
        } ?>
      </div>

      <div class="swiper-pagination"></div>
    </div>
  </section>

  <section class="movies_slider">
    <div class="container">
      <div class="blurry-bg"></div>
      <div class="black_overlay"></div>
      <div class="swiper available tranding-slider contents">
        <h1>Now Playing Movies</h1>
        <div class="swiper-wrapper">
          <?php foreach ($currentMovies as $movie) : ?>
            <?php $imageURL = 'uploaded_card_images/' . $movie["movie_card_poster"]; ?>
            <div class="tranding-slide movies_slider swiper-slide" data-movie-id="<?= htmlspecialchars($movie["movie_id"]); ?>">
              <img src="<?= $imageURL ?>" alt="<?= $movie['movie_title'] ?>">
              <div class="movie-info">
                <h2><?= $movie['movie_title'] ?></h2>
                <p><?= $movie['duration'] ?></p>
                <p>Release on <?= $movie['released_date'] ?></p>
                <a href="php/booking.php?movie=<?php echo htmlspecialchars($movie["movie_id"]); ?>" class="buy-tickets"> <span></span><span></span><span></span>
                  <span></span>Buy Tickets</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="upcoming_movie_slider">
    <div class="container">
      <div class="blurry-bg2"></div>
      <div class="black_overlay"></div>
      <div class="swiper upcoming_movies tranding-slider contents">
        <h1>Upcoming Movies</h1>
        <div class="swiper-wrapper">
          <?php foreach ($upcomingMovies as $movie) : ?>
            <?php $imageURL = 'uploaded_card_images/' . $movie["movie_card_poster"]; ?>
            <div class="tranding-slide movies_slider swiper-slide" data-movie-id="<?= htmlspecialchars($movie["movie_id"]); ?>">
              <img src="<?= $imageURL ?>" alt="<?= $movie['movie_title'] ?>" class="upcomingImg">
              <div class="movie-info">
                <h2><?= $movie['movie_title'] ?></h2>
                <p><?= $movie['duration'] ?></p>
                <p>Release on <?= $movie['released_date'] ?></p>
                <a href="php/booking.php?movie=<?php echo htmlspecialchars($movie["movie_id"]); ?>" class="buy-tickets"> <span></span><span></span><span></span>
                  <span></span>Buy Tickets</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <div class="links">
        <div class="column">
            <h2>Our Company</h2>
            <ul>
                <li>Our Brands</li>
                <li>Contact Us</li>
                <li>FAQs</li>
                <li>Corporate Information</li>
                <li>Savoy Investor Connect</li>
                <li>Investor Relations</li>
                <li>Media Center</li>
                <li>Careers</li>
                <li>Savoy Privacy Policy</li>
                <li>Terms & Conditions</li>
            </ul>
        </div>
        <div class="column">
            <h2>Movies</h2>
            <ul>
                <li>Movies</li>
                <li>Theatres</li>
                <li>Ratings Information</li>
                <li>IMAX at Savoy</li>
                <li>Dolby Cinema at Savoy</li>
                <li>PRIME at Savoy</li>
                <li>RealD 3D</li>
            </ul>
        </div>
        <div class="column">
            <h2>Programming</h2>
            <ul>
                <li>Private Theatre Rentals</li>
                <li>Savoy Artisan Films</li>
                <li>Savoy Thrills & Chills</li>
                <li>Savoy Screen Unseen</li>
                <li>Fan Faves</li>
                <li>International Films</li>
                <li>Film Festivals</li>
                <li>Special Events</li>
                <li>Sensory Friendly Films</li>
                <li>Groups & Events</li>
            </ul>
        </div>
        <div class="column">
            <h2>More</h2>
            <ul>
                <li>Offers & Promotions</li>
                <li>Gift Cards</li>
                <li>Movie Merchandise</li>
                <li>Savoy Merchandise</li>
                <li>NFTs from Savoy</li>
                <li>Mobile App</li>
                <li>Savoy Scene</li>
                <li>Request Refund</li>
            </ul>
        </div>
    </div>

    <footer class="footer">
        <div class="logo">SAVOY THEATRES</div>
        <p>WE MAKE BEST MOVIE EXPERIENCE.</p>
        <p>&copy; Copyright 2024 Savoy Theatres</p>
    </footer>

</body>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    let TrandingSlider = new Swiper('.available', {
      effect: 'coverflow',
      centeredSlides: true,
      loop: true,
      slidesPerView: 4,
      spaceBetween: 30,
      coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 1.5,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      on: {
        slideChange: function() {
          let activeImage = this.slides[this.activeIndex].querySelector('img');
          let backgroundImage = 'url(' + activeImage.src + ')';
          document.querySelector('.blurry-bg').style.backgroundImage = backgroundImage;
        },
      },
    });
    let upcomingSlider = new Swiper('.upcoming_movies', {
      effect: 'coverflow',
      centeredSlides: true,
      loop: true,
      slidesPerView: 4,
      spaceBetween: 30,
      coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 1,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      on: {
        slideChange: function() {

          let upcomingImg = this.slides[this.activeIndex].querySelector('.upcomingImg');
          let backgroundImage = 'url(' + upcomingImg.src + ')';
          document.querySelector('.blurry-bg2').style.backgroundImage = backgroundImage;
        },
      },
    });
  });
  document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.tranding-slide').forEach(function(slide) {
    slide.addEventListener('click', function(event) {
      if (!event.target.closest('.buy-tickets')) {
        var movieId = this.getAttribute('data-movie-id');
        window.location.href = 'php/movie_details.php?movie=' + movieId;
      }
    });
  });
});
</script>
<script src="js/script.js"></script>
</html>