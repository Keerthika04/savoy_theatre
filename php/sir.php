<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theater Seating</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .seat {
            width: 30px;
            height: 30px;
            margin: 5px;
            background-color: #5cb85c;
            color: white;
            text-align: center;
            line-height: 30px;
            cursor: pointer;
        }
        .seat:hover {
            background-color: #4cae4c;
        }
        .seat.selected {
            background-color: #007bff;
        }
        .row {
            display: flex;
            justify-content: center;
        }
        .section-title {
            margin: 20px 0;
            text-align: center;
        }
        .screen {
            background-color: #ddd;
            height: 20px;
            width: 60%;
            margin: 20px auto;
            text-align: center;
            line-height: 20px;
            color: #000;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center my-4">Theater Seating</h1>
    <div class="form-group">
        <label for="movieSelect">Select Movie:</label>
        <select class="form-control" id="movieSelect">
            <option>Movie 1</option>
            <option>Movie 2</option>
            <option>Movie 3</option>
        </select>
    </div>
    <div class="form-group">
        <label for="dateSelect">Select Date:</label>
        <input type="date" class="form-control" id="dateSelect">
    </div>
    <div class="form-group">
        <label for="timeSelect">Select Show Time:</label>
        <select class="form-control" id="timeSelect">
            <option>10:30 AM</option>
            <option>2:00 PM</option>
            <option>6:30 PM</option>
        </select>
    </div>
    <div class="form-group">
        <label for="adults">Number of Adults:</label>
        <input type="number" class="form-control" id="adults" min="0" value="0">
    </div>
    <div class="form-group">
        <label for="children">Number of Children:</label>
        <input type="number" class="form-control" id="children" min="0" value="0">
    </div>
    <div class="section-title">Screen</div>
    <div class="screen">Screen This Way</div>
    <div class="section-title">Normal Seats</div>
    <div id="normalSeats" class="mb-4"></div>
    <div class="section-title">ODC Seats</div>
    <div id="odcSeats" class="mb-4"></div>
    <div class="section-title">Balcony Seats</div>
    <div id="balconySeats" class="mb-4"></div>
    <div class="text-center">
        <button id="bookNow" class="btn btn-primary">Book Now</button>
        <button id="refresh" class="btn btn-secondary">Refresh</button>
    </div>
</div>

<!-- Booking Details Modal -->
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
                <p><strong>Total Price:</strong> $<span id="modalTotalPrice"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="payNow">Pay Now</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        // Create seats
        function createSeats(section, count) {
            let rows = Math.ceil(count / 20);
            let seatNumber = 1;
            for (let i = 0; i < rows; i++) {
                let row = $('<div class="row"></div>');
                for (let j = 0; j < 20; j++) {
                    if (seatNumber <= count) {
                        let seat = $('<div class="seat"></div>').text(seatNumber);
                        row.append(seat);
                        seatNumber++;
                    }
                }
                $('#' + section).append(row);
            }
        }

        // Create seats for each section
        createSeats('normalSeats', 150);
        createSeats('odcSeats', 150);
        createSeats('balconySeats', 200);

        // Seat selection logic
        $('.seat').on('click', function() {
            let adults = parseInt($('#adults').val()) || 0;
            let children = parseInt($('#children').val()) || 0;
            let totalSeatsNeeded = adults + children;
            let selectedSeats = $('.seat.selected').length;
           
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else if (selectedSeats < totalSeatsNeeded) {
                $(this).addClass('selected');
            }
        });

        // Book Now button click event
        $('#bookNow').on('click', function() {
            let movie = $('#movieSelect').val();
            let date = $('#dateSelect').val();
            let time = $('#timeSelect').val();
            let adults = parseInt($('#adults').val()) || 0;
            let children = parseInt($('#children').val()) || 0;
            let selectedSeats = [];
            $('.seat.selected').each(function() {
                selectedSeats.push($(this).text());
            });

            // Calculate prices
            let normalPrice = {adult: 150, child: 100};
            let odcPrice = {adult: 250, child: 200};
            let balconyPrice = {adult: 350, child: 250};
            let totalPrice = 0;

            $('.seat.selected').each(function() {
                let seatNumber = parseInt($(this).text());
                if (seatNumber <= 150) { // Normal seats
                    if (adults > 0) {
                        totalPrice += normalPrice.adult;
                        adults--;
                    } else if (children > 0) {
                        totalPrice += normalPrice.child;
                        children--;
                    }
                } else if (seatNumber <= 300) { // ODC seats
                    if (adults > 0) {
                        totalPrice += odcPrice.adult;
                        adults--;
                    } else if (children > 0) {
                        totalPrice += odcPrice.child;
                        children--;
                    }
                } else if (seatNumber <= 500) { // Balcony seats
                    if (adults > 0) {
                        totalPrice += balconyPrice.adult;
                        adults--;
                    } else if (children > 0) {
                        totalPrice += balconyPrice.child;
                        children--;
                    }
                }
            });

            // Add 10% tax
            totalPrice = totalPrice * 1.1;
            totalPrice = totalPrice.toFixed(2);

            // Update modal content
            $('#modalMovie').text(movie);
            $('#modalDate').text(date);
            $('#modalTime').text(time);
            $('#modalSeats').text(selectedSeats.join(', '));
            $('#modalAdults').text($('#adults').val());
            $('#modalChildren').text($('#children').val());
            $('#modalTotalPrice').text(totalPrice);

            // Show modal
            $('#bookingModal').modal('show');
        });

        // Pay Now button click event
        $('#payNow').on('click', function() {
            alert('Payment successful!');
            $('#bookingModal').modal('hide');
            // Optionally redirect to a payment success page
        });

        // Refresh button click event
        $('#refresh').on('click', function() {
            $('.seat').removeClass('selected');
            $('#movieSelect').val('Movie 1');
            $('#dateSelect').val('');
            $('#timeSelect').val('10:30 AM');
            $('#adults').val(0);
            $('#children').val(0);
        });
    });
</script>
</body>
</html>