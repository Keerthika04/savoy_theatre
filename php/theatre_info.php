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
    <link rel="stylesheet" href="../css/about_theatre.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <nav class="navbar flex_align_center" id="navbar">
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
    </nav>

    <section class="banner">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="../Images/Theatre.jpg" alt="TheatreBG" />
                    <div class="banner_text">
                        <h1>About Savoy</h1>
                        <p>Where You Can Find Better Movie Experience</p>
                    </div>
                </div>
            </div>

            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="about_theatre">
        <div>
            <h2>Welcome to Savoy Theatre</h2>
            <p>Savoy Theatre, which is situated in the heart of Colombo stands tall as an enduring emblem of cinematic
                excellence. For decades, we've been the
                go-to destination for movie buffs, offering a captivating blend of entertainment and culture under one
                roof.</p>
        </div>

        <div>
            <h2>Our History</h2>
            <p>For generations, Savoy Theatre has been a cornerstone of Colombo's entertainment landscape, hosting
                premieres, film festivals, and special events. Our enduring legacy is a testament to our dedication to
                excellence and our passion for the cinematic arts.</p>
        </div>

        <div>
            <h2>State-of-the-Art Facilities</h2>
            <p>At Savoy Theatre, we pride ourselves on offering the latest in cinematic technology. Our state-of-the-art
                facilities include:</p>
            <ul>
                <li><strong>High-definition screens</strong> with crystal-clear picture quality</li>
                <li><strong>Dolby Atmos surround sound system</strong> for an immersive audio experience</li>
                <li><strong>Comfortable seating</strong> with ample legroom and perfect sightlines</li>
                <li><strong>Premium concessions</strong> offering a variety of snacks and beverages for vips</li>
            </ul>
        </div>

        <div>
            <h2>A Diverse Selection of Films</h2>
            <p>We believe in the power of film to inspire, entertain, and educate. </br> Our diverse selection of films
                includes:</p>
            <ul>
                <li><strong>Blockbuster hits</strong> from around the world</li>
                <li><strong>Independent films</strong> showcasing unique voices and stories</li>
                <li><strong>Classic movies</strong> that have stood the test of time</li>
                <li><strong>Documentaries</strong> that offer insightful perspectives on various topics</li>
            </ul>
        </div>

        <div>
            <h2>Community Engagement</h2>
            <p>Savoy Theatre is more than just a place to watch movies; it's a community hub. We regularly host events
                such as:</p>
            <ul>
                <li><strong>Film festivals</strong> celebrating various genres and cultures</li>
                <li><strong>Special screenings</strong> with Q&amp;A sessions featuring filmmakers and actors</li>
                <li><strong>Workshops and seminars</strong> on film production and critique</li>
            </ul>
        </div>

        <div>
            <h2>Our Mission</h2>
            <p>Our mission is to create an inclusive and welcoming environment where everyone can enjoy the magic of
                cinema. We are committed to:</p>
            <ul>
                <li><strong>Enhancing the movie-going experience</strong> through continuous innovation</li>
                <li><strong>Supporting the local arts community</strong> by providing a platform for emerging talent
                </li>
                <li><strong>Fostering a love for cinema</strong> in audiences of all ages</li>
            </ul>
        </div>

        <div>
            <h2>Visit Us</h2>
            <p>Conveniently located in Colombo, Savoy Theatre is easily accessible by public transport and offers ample
                parking facilities. Whether you're planning a night out with friends, a family outing, or a solo trip to
                the movies, we look forward to welcoming you to Savoy Theatre.</p>
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
<script src="../js/script.js"></script>

</html>