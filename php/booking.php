<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['user_id'])) {
    $_SESSION['alert_message'] = "You have to login to book!";
    $_SESSION['booking'] = true;
    header("Location: login.php");
}
function getMovies($db)
{
    $sql = "SELECT movie_id, movie_title FROM movies where current_movies = 1";
    $result = $db->query($sql);
    if (!$result) {
        die("Error fetching movies: " . $db->error);
    }
    $movies = [];
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
    return $movies;
}

function getMovieDetails($db, $movie_id)
{
    $sql = "SELECT * FROM movies WHERE movie_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie = $result->fetch_assoc();
    $stmt->close();
    return $movie;
}

function getShowtimes($db, $movie_id)
{
    $sql = "SELECT s.date, s.time FROM showtimes s WHERE s.movie_id = ? ORDER BY s.date, s.time";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $showtimes = [];
    while ($row = $result->fetch_assoc()) {
        $showtimes[$row['date']][] = $row['time'];
    }
    $stmt->close();
    return $showtimes;
}

if (isset($_POST['movie'])) {
    header("Location: booking.php?movie=" . urlencode($_POST['movie']));
    exit();
}

$movie_id = $_GET['movie'] ?? null;
$all_movies = getMovies($db);
$selected_movie = null;
$showtimes = [];

if (!$movie_id) {
    if (!empty($all_movies)) {
        $movie_id = $all_movies[0]['movie_id'];
        $selected_movie = getMovieDetails($db, $movie_id);
        $imageURL = '../uploaded_movie_posters/' . $selected_movie["movie_poster"];
        if ($selected_movie) {
            $showtimes = getShowtimes($db, $movie_id);
            $selected_movie['showtimes'] = $showtimes;
        } else {
            echo "<p>Error: Unable to fetch movie details.</p>";
        }
    } else {
        echo "<p>Error: No movies found in the database.</p>";
    }
} else {
    $selected_movie = getMovieDetails($db, $movie_id);
    if ($selected_movie) {
        $showtimes = getShowtimes($db, $movie_id);
        $selected_movie['showtimes'] = $showtimes;
        $imageURL = '../uploaded_movie_posters/' . $selected_movie["movie_poster"];
    } else {
        echo "<p>Error: Unable to fetch movie details.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/book.css">
</head>

<body style="background-image: url('<?php echo $imageURL; ?>');">
    <div id="movieBackground" style="background: inherit;" class="movie_booking_page">
    </div>
    <div class="black_overlay"></div>
    <nav class="contents">
        <div class="top_nav">
            <a href="../index.php"><i class="fa-solid fa-circle-chevron-left"
                    style="color: #ffffff; font-size: 1.5rem;"></i></a>
            <h4>Show Times</h4>
        </div>
        <div class="bottom_nav">
            <form method="post" id="movieSelectForm">
                <div class="form-group">
                    <select class="form-control movie_selection" id="movieSelect" name="movie"
                        onchange="document.getElementById('movieSelectForm').submit();">
                        <?php foreach ($all_movies as $movie_option): ?>
                            <option value="<?php echo htmlspecialchars($movie_option['movie_id']); ?>" <?php echo ($movie_option['movie_id'] == $movie_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($movie_option['movie_title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
            <div class="bottom_nav_border">
                <a href="#"> <i class="fa-solid fa-location-dot" style="color: #ffffff;"></i> &nbsp Location</a>
            </div>
            <div class="center">
                <a href="#"><i class="fa-solid fa-martini-glass-citrus" style="color: #ffffff;"></i> &nbsp Snack &
                    Drinks</a>
            </div>
        </div>
    </nav>
    <?php if ($selected_movie): ?>
        <div class="movie contents">
            <h1><?php echo htmlspecialchars($selected_movie['movie_title']); ?></h1>
            <h5>Duration: <?php echo htmlspecialchars($selected_movie['duration']); ?></h5>
            <br>
            <p><?php echo htmlspecialchars($selected_movie['storyplot']); ?></p>
            <br>
            <div class="movie_bg">
                <?php if (!empty($selected_movie['showtimes'])): ?>
                    <h4>Show Available Dates</h4>
                    <div class="radio-btn-group">
                        <?php foreach ($selected_movie['showtimes'] as $date => $showtimes): ?>
                            <div class="ticket">
                                <input type="radio" id="date_<?php echo htmlspecialchars($date); ?>" name="showdate"
                                    value="<?php echo htmlspecialchars($date); ?>"
                                    onclick="showShowtimes('<?php echo htmlspecialchars($date); ?>')">
                                <img src="../Images/ticket.png" alt="">
                                <label
                                    for="date_<?php echo htmlspecialchars($date); ?>"><?php echo htmlspecialchars($date); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="showtimes">

                        <?php foreach ($selected_movie['showtimes'] as $date => $showtimes): ?>
                            <ul id="showtimes_<?php echo htmlspecialchars($date); ?>" class="showtime-list">
                                <?php foreach ($showtimes as $time): ?>
                                    <h5>Time:</h5>
                                    <input type="radio" id="time_<?php echo htmlspecialchars($date . '_' . $time); ?>" name="showtime"
                                        value="<?php echo htmlspecialchars($time); ?>"
                                        onclick="setSelectedShowtime('<?php echo htmlspecialchars($time); ?>')">
                                    <label for="time_<?php echo htmlspecialchars($date . '_' . $time); ?>"><i
                                            class="fa-regular fa-clock" style="margin-right: 3px;"></i>
                                        <?php echo htmlspecialchars($time); ?></label>
                                <?php endforeach; ?>
                            </ul>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="count">
                    <div class="form-group">
                        <label for="adults">Number of Adults:</label>
                        <input type="number" id="adults" class="form-control" min="0" value="0" onchange="validateTotal()">
                    </div>
                    <div class="form-group">
                        <label for="children">Number of Children:</label>
                        <input type="number" id="children" class="form-control" min="0" value="0" onchange="validateTotal()">
                    </div>
                </div>
                <div id="normalSeats">
                    <h3> - Normal Seats - </h3>
                </div>
                <div id="odcSeats">
                    <h3>- ODC Seats -</h3>
                </div>
                <div id="balconySeats">
                    <h3>- Balcony Seats -</h3>
                </div>

                <div class="parking">
                    <div class="form-group">
                        <select id="parking" class="form-control">
                            <option value="No Parking">No Parking</option>
                            <option value="Motorbike">Motorbike</option>
                            <option value="Car">Car</option>
                            <option value="Minivan">Minivan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-light bookingBtn" id="bookButton" onclick="bookShowtime()">Book Now
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            <?php else: ?>
                <p>No showtimes available for this movie.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                </div>
                <div class="modal-body">
                    <p><strong>Movie:</strong> <span id="modalMovie"></span></p>
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                    <p><strong>Show Time:</strong> <span id="modalTime"></span></p>
                    <p><strong>Seats:</strong> <span id="modalSeats"></span></p>
                    <p><strong>Adults:</strong> <span id="modalAdults"></span></p>
                    <p><strong>Children:</strong> <span id="modalChildren"></span></p>
                    <p><strong>Parking:</strong> <span id="modalParking"></span></p>
                    <p><strong>Total Price:</strong> Rs.<span id="modalTotalPrice"> /-</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-light" id="payNow">Pay Now</button>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        let selectedShowtime = null;
        let selectedSeats = [];
        let bookedSeats = [];
        let totalSeats = 0;

        function showShowtimes(date) {
            const showtimeLists = document.querySelectorAll('.showtime-list');
            showtimeLists.forEach(list => {
                list.style.display = 'none';
                list.style.padding = '1rem 0 0 0';
            });
            const selectedList = document.getElementById('showtimes_' + date);
            if (selectedList) {
                selectedList.style.display = 'block';
            }
        }

        function setSelectedShowtime(showtime) {
            selectedShowtime = showtime;
        }


        function validateTotal() {
            const adults = parseInt(document.getElementById('adults').value) || 0;
            const children = parseInt(document.getElementById('children').value) || 0;
            const total = adults + children;

            if (total > 10) {
                alert('Total number of seats cannot exceed 10.');
                document.getElementById('adults').value = 0;
                document.getElementById('children').value = 0;
            }
        }

        $(document).ready(function () {
            function createSeats(section, count, prefix) {
                let rows = Math.ceil(count / 15);
                let seatNumber = 1;
                for (let i = 0; i < rows; i++) {
                    let row = $('<div class="row"></div>');
                    for (let j = 0; j < 15 && seatNumber <= count; j++) {
                        let seat = $('<div class="seat"></div>').text(prefix + seatNumber);
                        seat.attr('data-seat-number', prefix + seatNumber);
                        row.append(seat);
                        seatNumber++;
                    }
                    section.append(row);
                }
            }

            createSeats($('#normalSeats'), 150, 'A');
            createSeats($('#odcSeats'), 150, 'B');
            createSeats($('#balconySeats'), 200, 'C');

            function updateBookedSeats(bookedSeats) {
                $('.seat').each(function () {
                    const seatNumber = $(this).attr('data-seat-number');
                    if (bookedSeats.includes(seatNumber)) {
                        $(this).addClass('booked').off('click');
                    } else {
                        $('.seat').on('click', function () {
                            if (!$(this).hasClass('selected') && selectedSeats.length < totalSeats) {
                                $(this).addClass('selected');
                                selectedSeats.push(parseInt($(this).text()));
                            } else if ($(this).hasClass('selected')) {
                                $(this).removeClass('selected');
                                selectedSeats = selectedSeats.filter(seat => seat !== parseInt($(this).text()));
                            }
                        });
                    }
                });
            }

            $('#adults, #children').on('input', function () {
                totalSeats = parseInt($('#adults').val()) + parseInt($('#children').val());
                if (selectedSeats.length > totalSeats) {
                    selectedSeats = selectedSeats.slice(0, totalSeats);
                    $('.seat').removeClass('selected');
                    selectedSeats.forEach(seat => {
                        $(`.seat:contains(${seat})`).addClass('selected');
                    });
                }
            });

            function fetchBookedSeats(date, time) {
                $.ajax({
                    url: 'getBookedSeats.php',
                    type: 'POST',
                    data: {
                        date: date,
                        time: time
                    },
                    success: function (response) {
                        bookedSeats = JSON.parse(response);
                        updateBookedSeats(bookedSeats);
                    },
                    error: function (xhr, status, error) {
                        alert('Error fetching booked seats: ' + error);
                    }
                });
            }

            $('#movieSelect').on('change', function () {
                const date = $('input[name="showdate"]:checked').val();
                const time = $('input[name="showtime"]:checked').val();
                fetchBookedSeats(date, time);
            });

            $('input[name="showdate"], input[name="showtime"]').on('change', function () {
                const date = $('input[name="showdate"]:checked').val();
                const time = $('input[name="showtime"]:checked').val();
                fetchBookedSeats(date, time);
            });

            function fetchBookedSeats(date, time) {
                $.ajax({
                    url: 'getBookedSeats.php',
                    type: 'POST',
                    data: {
                        date: date,
                        time: time
                    },
                    success: function (response) {
                        bookedSeats = JSON.parse(response);
                        updateBookedSeats();
                        // console.log('Working');
                    },
                    error: function (xhr, status, error) {
                        alert('Error fetching booked seats: ' + error);
                    }
                });
            }

            function updateBookedSeats() {
                $('.seat').each(function () {
                    const seatNumber = $(this).attr('data-seat-number');
                    if (bookedSeats.includes(seatNumber)) {
                        $(this).addClass('booked');
                        // console.log('done');
                        $(this).off('click');
                    } else {
                        $(this).removeClass('booked');
                        $(this).on('click', function () {
                            const seatNumber = $(this).attr('data-seat-number');
                            if (!$(this).hasClass('selected') && selectedSeats.length < totalSeats) {
                                $(this).addClass('selected');
                                selectedSeats.push(seatNumber);
                            } else if ($(this).hasClass('selected')) {
                                $(this).removeClass('selected');
                                selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
                            }
                        });
                    }
                });
            }
        });


        function bookShowtime() {
            const adults = parseInt(document.getElementById('adults').value) || 0;
            const children = parseInt(document.getElementById('children').value) || 0;
            const total = adults + children;


            if (selectedShowtime) {
                if (total > 0 && total <= 10) {
                    if (selectedSeats.length == totalSeats) {
                        const movie = document.getElementById('movieSelect').options[document.getElementById('movieSelect').selectedIndex].text;
                        const date = document.querySelector('input[name="showdate"]:checked').value;
                        const time = document.querySelector('input[name="showtime"]:checked').value;
                        const seats = selectedSeats;
                        const adults = document.getElementById('adults').value;
                        const children = document.getElementById('children').value;
                        const parking = document.getElementById('parking').value;
                        const totalPrice = calculateTotalPrice(adults, children, parking);

                        document.getElementById('modalMovie').innerText = movie;
                        document.getElementById('modalDate').innerText = date;
                        document.getElementById('modalTime').innerText = time;
                        document.getElementById('modalSeats').innerText = seats;
                        document.getElementById('modalAdults').innerText = adults;
                        document.getElementById('modalChildren').innerText = children;
                        document.getElementById('modalParking').innerText = parking;
                        document.getElementById('modalTotalPrice').innerText = totalPrice;

                        $('#bookingModal').modal('show');
                    } else {
                        alert('Please select the exact number of seats.');
                        return;
                    }
                } else {
                    alert('The total number of bookings (adults and children) cannot exceed 10 and more than 0.');
                }
            } else {
                alert('Please select a showtime.');
            }
        }

        function calculateTotalPrice(adults, children, parking) {
            const adultPrice = 250;
            const childPrice = 150;
            let parkingPrice = 0;

            switch (parking) {
                case 'Motorbike':
                    parkingPrice = 50;
                    break;
                case 'Car':
                    parkingPrice = 100;
                    break;
                case 'Minivan':
                    parkingPrice = 150;
                    break;
            }

            return (adults * adultPrice) + (children * childPrice) + parkingPrice;
        }


        $('#payNow').on('click', function () {
            const name = $('#name').val();
            const mobile = $('#mobile').val();
            const movie = $('#modalMovie').text();
            const date = $('#modalDate').text();
            const time = $('#modalTime').text();
            const seats = $('#modalSeats').text();
            const adults = $('#modalAdults').text();
            const children = $('#modalChildren').text();
            const parking = $('#modalParking').text();
            const totalPrice = $('#modalTotalPrice').text();

            $.ajax({
                url: 'book.php',
                type: 'POST',
                data: {
                    name: name,
                    mobile: mobile,
                    movie: movie,
                    date: date,
                    time: time,
                    seats: seats,
                    adults: adults,
                    children: children,
                    parking: parking,
                    totalPrice: totalPrice
                },
                success: function (response) {
                    alert(response);
                    $('#bookingModal').modal('hide');
                    document.getElementById('movieSelectForm').submit();
                },
                error: function (xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });
    </script>
</body>

</html>