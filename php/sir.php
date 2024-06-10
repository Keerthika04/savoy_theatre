<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['alert_message'] = "You have to login to book!";
    $_SESSION['booking'] = true;
    header("Location: login.php");
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
    <link rel="stylesheet" href="../styles.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theater Booking System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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

<div class="container mt-5">
    <div class="d-flex justify-content-between">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <div>
            <a href="profile.php" class="btn btn-secondary">Profile</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
   

<div class="container">
    <h1 class="text-center">Theater Booking System</h1>

    <div id="bookingSection">
        <div class="form-group">
            <label for="dateSelect">Select Date:</label>
            <input type="date" id="dateSelect" class="form-control">
        </div>
        <div class="form-group">
            <label for="movieSelect">Select Movie:</label>
            <select id="movieSelect" class="form-control">
                <option value="Movie 1">Movie 1</option>
                <option value="Movie 2">Movie 2</option>
                <option value="Movie 3">Movie 3</option>
            </select>
        </div>
        <div class="form-group">
            <label for="timeSelect">Select Show Time:</label>
            <select id="timeSelect" class="form-control">
                <option value="10:30 AM">10:30 AM</option>
                <option value="2:00 PM">2:00 PM</option>
                <option value="6:30 PM">6:30 PM</option>
            </select>
        </div>
        <div class="form-group">
            <label for="adults">Number of Adults:</label>
            <input type="number" id="adults" class="form-control" min="0" value="0">
        </div>
        <div class="form-group">
            <label for="children">Number of Children:</label>
            <input type="number" id="children" class="form-control" min="0" value="0">
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
                <option value="none">No Parking</option>
                <option value="motorbike">Motorbike</option>
                <option value="car">Car</option>
                <option value="minivan">Minivan</option>
            </select>
        </div>

        <button id="bookNow" class="btn btn-primary">Book Now</button>
        <button id="refresh" class="btn btn-secondary">Refresh</button>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
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
                    <p><strong>Total Price:</strong> $<span id="modalTotalPrice"></span></p>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile Number:</label>
                        <input type="text" id="mobile" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="payNow">Pay Now</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

  
   
    <!-- Refresh and Book Now Button -->
    <div class="text-center mt-3">
        <button id="refresh" class="btn btn-secondary">Refresh</button>
    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Create seats
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

        let selectedSeats = [];
        let totalSeats = 0;

        function calculateTotalPrice() {
            let adults = parseInt($('#adults').val());
            let children = parseInt($('#children').val());
            let price = 0;

            selectedSeats.forEach(seat => {
                if (seat <= 150) {
                    price += (adults * 150) + (children * 100);
                } else if (seat <= 300) {
                    price += (adults * 250) + (children * 200);
                } else {
                    price += (adults * 350) + (children * 250);
                }
            });

            let parking = $('#parking').val();
            if (parking === 'motorbike') {
                price += 50;
            } else if (parking === 'car') {
                price += 100;
            } else if (parking === 'minivan') {
                price += 150;
            }

            let tax = price * 0.10;
            price += tax;

            return price.toFixed(2);
        }

        $('.seat').on('click', function() {
            if (!$(this).hasClass('selected') && selectedSeats.length < totalSeats) {
                $(this).addClass('selected');
                selectedSeats.push(parseInt($(this).text()));
            } else if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                selectedSeats = selectedSeats.filter(seat => seat !== parseInt($(this).text()));
            }
        });

        $('#adults, #children').on('input', function() {
            totalSeats = parseInt($('#adults').val()) + parseInt($('#children').val());
            if (selectedSeats.length > totalSeats) {
                selectedSeats = selectedSeats.slice(0, totalSeats);
                $('.seat').removeClass('selected');
                selectedSeats.forEach(seat => {
                    $(`.seat:contains(${seat})`).addClass('selected');
                });
            }
        });

        $('#bookNow').on('click', function() {
            if (selectedSeats.length !== totalSeats) {
                alert('Please select the exact number of seats.');
                return;
            }

            let movie = $('#movieSelect').val();
            let date = $('#dateSelect').val();
            let time = $('#timeSelect').val();
            let adults = $('#adults').val();
            let children = $('#children').val();
            let parking = $('#parking').val();
            let totalPrice = calculateTotalPrice();

            $('#modalMovie').text(movie);
            $('#modalDate').text(date);
            $('#modalTime').text(time);
            $('#modalSeats').text(selectedSeats.join(', '));
            $('#modalAdults').text(adults);
            $('#modalChildren').text(children);
            $('#modalParking').text(parking);
            $('#modalTotalPrice').text(totalPrice);

            $('#bookingModal').modal('show');
        });

        $('#payNow').on('click', function() {
            let name = $('#name').val();
            let mobile = $('#mobile').val();
            let movie = $('#modalMovie').text();
            let date = $('#modalDate').text();
            let time = $('#modalTime').text();
            let seats = $('#modalSeats').text();
            let adults = $('#modalAdults').text();
            let children = $('#modalChildren').text();
            let parking = $('#modalParking').text();
            let totalPrice = $('#modalTotalPrice').text();

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
                success: function(response) {
                    alert(response);
                    $('#bookingModal').modal('hide');
                    $('#refresh').click();
                }
            });
        });

        $('#refresh').on('click', function() {
            $('.seat').removeClass('selected');
            $('#movieSelect').val('Movie 1');
            $('#dateSelect').val('');
            $('#timeSelect').val('10:30 AM');
            $('#adults').val(0);
            $('#children').val(0);
            $('#name').val('');
            $('#mobile').val('');
            $('#parking').val('none');
            selectedSeats = [];
            totalSeats = 0;
        });
    });
</script>
</body>
</html>

</div>
</body>
</html>
