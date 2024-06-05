<?php
session_start();
require 'db_connection.php';

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
        if ($selected_movie) {
            $showtimes = getShowtimes($db, $movie_id);
            $selected_movie['showtimes'] = $showtimes;
        } else {
            echo "<p>Error: Unable to fetch movie details.</p>";
        }
    } else {
        echo "<p>Error: No movies found in the database.</p>";
    }
}else{
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
    <title>Movies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Savoy</title>
    <link rel="icon" href="../Images/favicon.png" type="image/png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/booking.css">
    <div id="movieBackground" style="background-image: url('<?php echo $imageURL; ?>');" class="movie_booking_page">
        <div class="black_overlay"></div>
    </div>
    <style>
        .radio-btn-group input[type="radio"] {
            display: none;
        }

        .radio-btn-group label {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border: 1px solid #007bff;
            border-radius: 5px;
            cursor: pointer;
            background-color: #f8f9fa;
            color: #007bff;
        }

        .radio-btn-group input[type="radio"]:checked+label {
            background-color: #007bff;
            color: white;
        }

        .showtime-list {
            display: none;
        }

        .showtime-list input[type="radio"] {
            display: none;
        }

        .showtime-list label {
            display: inline-block;
            padding: 5px 10px;
            margin: 3px;
            border: 1px solid #28a745;
            border-radius: 3px;
            cursor: pointer;
            background-color: #e9ecef;
            color: #28a745;
        }

        .showtime-list input[type="radio"]:checked+label {
            background-color: #28a745;
            color: white;
        }

        .seat {
            width: 30px;
            height: 30px;
            margin: 3px;
            background-color: #ccc;
            text-align: center;
            line-height: 30px;
            cursor: pointer;
        }

        .selected {
            background-color: blue;
            color: white;
        }

        .row {
            display: flex;
            justify-content: center;
        }

        .modal-content {
            padding: 20px;
        }
    </style>
</head>

<body>
    <nav class="booking_head_nav">
        <a href=""><i class="fa-solid fa-circle-chevron-left" style="color: #ffffff; font-size: 1.5rem;"></i></a>
        <form method="post" id="movieSelectForm">
            <div class="form-group">
                <select class="form-control" id="movieSelect" name="movie"
                    onchange="document.getElementById('movieSelectForm').submit();">
                    <?php foreach ($all_movies as $movie_option): ?>
                        <option value="<?php echo htmlspecialchars($movie_option['movie_id']); ?>" <?php echo ($movie_option['movie_id'] == $movie_id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($movie_option['movie_title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </nav>
    <?php if ($selected_movie): ?>
        <div class="movie">
            <h3><?php echo htmlspecialchars($selected_movie['movie_title']); ?></h3>
            <p>Duration: <?php echo htmlspecialchars($selected_movie['duration']); ?></p>
            <p><?php echo htmlspecialchars($selected_movie['storyplot']); ?></p>

            <?php if (!empty($selected_movie['showtimes'])): ?>
                <h4>Select Date:</h4>
                <div class="radio-btn-group">
                    <?php foreach ($selected_movie['showtimes'] as $date => $showtimes): ?>
                        <input type="radio" id="date_<?php echo htmlspecialchars($date); ?>" name="showdate"
                            value="<?php echo htmlspecialchars($date); ?>"
                            onclick="showShowtimes('<?php echo htmlspecialchars($date); ?>')">
                        <label for="date_<?php echo htmlspecialchars($date); ?>"><?php echo htmlspecialchars($date); ?></label>
                    <?php endforeach; ?>
                </div>
                <div class="showtimes">
                    <?php foreach ($selected_movie['showtimes'] as $date => $showtimes): ?>
                        <ul id="showtimes_<?php echo htmlspecialchars($date); ?>" class="showtime-list">
                            <?php foreach ($showtimes as $time): ?>
                                <input type="radio" id="time_<?php echo htmlspecialchars($date . '_' . $time); ?>" name="showtime"
                                    value="<?php echo htmlspecialchars($time); ?>"
                                    onclick="setSelectedShowtime('<?php echo htmlspecialchars($time); ?>')">
                                <label
                                    for="time_<?php echo htmlspecialchars($date . '_' . $time); ?>"><?php echo htmlspecialchars($time); ?></label>
                            <?php endforeach; ?>
                        </ul>
                    <?php endforeach; ?>
                </div>
                <div class="form-group">
                    <label for="adults">Number of Adults:</label>
                    <input type="number" id="adults" class="form-control" min="0" value="0" onchange="validateTotal()">
                </div>
                <div class="form-group">
                    <label for="children">Number of Children:</label>
                    <input type="number" id="children" class="form-control" min="0" value="0" onchange="validateTotal()">
                </div>
                <div id="normalSeats">
                    <h3>Normal Seats</h3>
                </div>
                <div id="odcSeats">
                    <h3>ODC Seats</h3>
                </div>
                <div id="balconySeats">
                    <h3>Balcony Seats</h3>
                </div>

                <div class="form-group">
                    <label for="parking">Parking Needed:</label>
                    <select id="parking" class="form-control">
                        <option value="No Parking">No Parking</option>
                        <option value="Motorbike">Motorbike</option>
                        <option value="Car">Car</option>
                        <option value="Minivan">Minivan</option>
                    </select>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" id="bookButton" onclick="bookShowtime()">Book Showtime</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                    <form id="bookingForm">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile Number:</label>
                            <input type="text" id="mobile" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="payNow">Pay Now</button>
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
        let totalSeats = 0;

        function showShowtimes(date) {
            const showtimeLists = document.querySelectorAll('.showtime-list');
            showtimeLists.forEach(list => {
                list.style.display = 'none';
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
            function createSeats(section, count) {
                let rows = Math.ceil(count / 20);
                let seatNumber = 1;
                for (let i = 0; i < rows; i++) {
                    let row = $('<div class="row"></div>');
                    for (let j = 0; j < 20 && seatNumber <= count; j++) {
                        let seat = $('<div class="seat"></div>').text(seatNumber);
                        seat.attr('data-seat-number', seatNumber);
                        row.append(seat);
                        seatNumber++;
                    }
                    section.append(row);
                }
            }

            createSeats($('#normalSeats'), 150);
            createSeats($('#odcSeats'), 150);
            createSeats($('#balconySeats'), 200);

            $('.seat').on('click', function () {
                if (!$(this).hasClass('selected') && selectedSeats.length < totalSeats) {
                    $(this).addClass('selected');
                    selectedSeats.push(parseInt($(this).text()));
                } else if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    selectedSeats = selectedSeats.filter(seat => seat !== parseInt($(this).text()));
                }
            });

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