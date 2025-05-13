@extends('layouts.site')

@section('content')
<style>
    .list{
        height: 200px;
        overflow-y: scroll !important;
    }
    /* General container styling */
    .booking-form-area {
        width: 100%;
        max-width: 65%;
        margin: 35px auto;
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    /* Styling for the form row (date and time fields) */
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    /* Styling for each form field */
    .form-field {
        width: 100%; /* Ensures both inputs take full width */
    }

    /* Label styling */
    .form-field label {
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    /* Form control for input fields and select dropdown */
    .form-control {
        width: 100%; /* Make sure it takes full available width */
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box; /* Ensures padding doesn't affect width */
    }

    /* Submit button styling */
    .submit-area {
        text-align: center;
        margin-top: 20px;
    }

    .submit-btn {
        padding: 12px 20px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        text-transform: uppercase;
        transition: background-color 0.3s ease;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }

    .section-padding {
        padding: 10px 0;
    }

    /* Responsive Design */
    @media screen and (max-width: 600px) {
        .form-row {
            flex-direction: column;
            gap: 15px;
        }
    }

    .top-content {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* Two equal columns */
        gap: 10px; /* Space between boxes */
        align-items: stretch; /* Ensures equal height */
    }

    .sports-box {
        background: #FFF;
        padding: 5px;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    /* Mobile: One column */
    @media (max-width: 600px) {
        .top-content {
            grid-template-columns: 1fr; /* One column */
        }
}

@media screen and (max-width: 600px) {
    .booking-form-area{
        max-width: unset;
    }
}

.no-grounds {
    text-align: center;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.no-grounds i {
    font-size: 50px;
    color: gray;
}

.no-grounds p {
    font-size: 18px;
    color: red;
    margin-top: 10px;
}
</style>

<style>
        /* Booking Form Wrapper */
.booking-form-container {
    max-width: 85%;
    margin: 50px auto;
    padding: 20px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.3);
    transition: 0.3s;
    color: #333;
    position: relative; /* Ensure it doesn’t get affected by other elements */
}

/* Prevent styles from affecting other forms */
.booking-form-container:hover {
    transform: scale(1.02);
}

/* Ensure only form elements inside the booking form are styled */
.booking-form-container .form-label {
    font-weight: bold;
    color: #333 !important;
    font-size: 18px;
}

.booking-form-container .form-control {
    border-radius: 8px;
    border: 1px solid #ccc !important;
    background: #fff !important;
    color: #333 !important;
    width: 100%;
}

/* Fix spacing */
.booking-form-container .form-control:not(:last-child) {
    margin-bottom: 10px;
}

/* Time Slot Container */
.booking-form-container .time-slot-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

/* Time Slots */
.booking-form-container .time-slot {
    background: #eee !important;
    color: #333 !important;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    border: 1px solid #bbb !important;
    text-align: center;
    min-width: 55px;
}

/* Time Slot Hover Effect */
.booking-form-container .time-slot:hover:not(:has(s)) {
    background: #ffcc00 !important;
    color: #000 !important;
}

/* Selected Time Slot */
.booking-form-container .time-slot.selected {
    background: #268100 !important;
    color: #fff !important;
    transform: scale(1.1);
    box-shadow: 0px 0px 10px rgba(255, 140, 0, 0.5);
}

/* Submit Button */
.booking-form-container .btn-submit {
    background: #268100 !important;
    color: #fff !important;
    font-weight: bold;
    border-radius: 8px;
    transition: 0.3s;
    width: 100%;
    font-size: 20px;
}

.booking-form-container .btn-submit:hover {
    background: #000000 !important;
    transform: translateY(-3px);
}

.time-slot.disabled {
    pointer-events: none;
    opacity: 0.5;
    background-color: #ddd;
    cursor: not-allowed;
}
</style>
<!-- start of breadcumb-section -->
<!-- <div class="wpo-breadcumb-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="wpo-breadcumb-wrap">
                    <h2>Grounds</h2>
                    <ul>
                        <li><a href="{{ url('/home') }}">Home</a></li>
                        <li><span>Grounds</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- end of wpo-breadcumb-section-->
<section id="ground" class="featured-section section-padding">
    <div class="container">

        <!-- <div class="booking-form-area">
            <center><h2>Check Ground Avilability</h2></center>
            <form method="post" class="booking-form" id="booking-form-main" novalidate="novalidate">
                <div class="form-row">
                    <div class="form-field">
                        <label for="date">Select Date*</label>
                        <input type="date" class="form-control date-input" name="date" id="date" placeholder="Select Date" required>
                        <small class="error" id="dateError"></small>
                    </div>
                    <div class="form-field">
                        <label for="time">Select Time*</label>
                        @php
                            $startTime = strtotime("01:00"); // Start time
                            $endTime = strtotime("23:00");   // End time
                            $timeSlots = [];

                            while ($startTime <= $endTime) {
                                $timeSlots[] = date("H:i:s", $startTime);
                                $startTime = strtotime("+1 hour", $startTime);
                            }
                        @endphp
                        <select class="form-control time-select" name="time" id="time" required>
                            <option value="">Select Time</option>
                            @foreach($timeSlots as $time)
                                <option value="{{ $time }}">{{ date("h:i A", strtotime($time)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div> -->

        <div class="booking-form-container">
            <h2 class="text-center mb-4">Book Your Ground</h2>
            <form id="booking-form">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="date" class="form-label">Select Date*</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                        <br>
                        @php
                            $startTime = strtotime("01:00"); // Start time
                            $endTime = strtotime("23:00");   // End time
                            $timeSlots = [];

                            while ($startTime <= $endTime) {
                                $timeSlots[] = date("H:i:s", $startTime);
                                $startTime = strtotime("+1 hour", $startTime);
                            }
                        @endphp
                        <label class="form-label">Select Time Slots*</label>
                        <div class="time-slot-container" id="timeSlots">
                            @foreach($timeSlots as $time)
                                @if(in_array($time, $available_timeslots))
                                    <div class="time-slot" data-time="{{ $time }}">{{ date('h:i A', strtotime($time)) }}</div>
                                @else
                                    <!-- <div class="time-slot striked" data-time="{{ $time }}"><s>{{ date('h:i A', strtotime($time)) }}</s></div> -->
                                @endif
                                
                            @endforeach
                        </div>
                    </div>
                </div>
        
                <!-- <button type="submit" class="btn btn-submit">Confirm Booking</button> -->
            </form>
        </div>
        
        <div class="gallery-container gallery-fancybox masonry-gallery row">
            @foreach($grounds as $key => $value)
            <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all {{$value->classname}} zoomIn" data-wow-duration="2000ms">
                <a href="{{ url('/ground-details')}}?id={{base64_encode($value->ground_id)}}">
                    <div class="featured-card">
                        <div class="image">
                            @php
                                $imageArray = explode(',', $value->ground_images);
                            @endphp
                            <img src="{{ asset($imageArray[0]) }}" alt="">
                        </div>
                        <div class="content">
                            <div class="top-content">
                                <div class="sports-box">
                                    <h6>{{ $value->category_name }}</h6>
                                    <span class="date">{{ $value->ground_name }}</span>
                                </div>
                                <div class="sports-box">
                                    <h6>AED {{ $value->rate }}</h6>
                                    <span class="date">Per Hour</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

    </div>
</section>
<script>
$(document).ready(function () {
    var today = new Date().toISOString().split("T")[0];
    $("#date").val(today);

    displayData();
    $('#date').on("input", function() {
        // const datePattern = /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/;
        // var date = $("#date").val();
        // const dateError = document.getElementById("dateError");
        // if (!datePattern.test(date)) {
        //     dateError.textContent = "Please enter a valid date in DD-MM-YYYY format.";
        //     event.preventDefault();
        // } else {
        //     dateError.textContent = "";
        // }
        localStorage.removeItem('selectedTime');
        displayData();
    });

    initializeTimeSlotClick();

    $('.time-slot').on("click", function() {
        displayData(); 
    });
});

function displayData() {
    var date = $("#date").val();
    var time = $("#time").val();
    let selectedTime = [];

    let selectedSlots = $('.time-slot.selected');
    selectedSlots.each(function () {
        selectedTime.push($(this).data('time'));
    });
    localStorage.setItem('selectedTime', JSON.stringify(selectedTime));
    $.ajax({
        url: baseUrl + '/grounds', // Ensure baseUrl is correct
        type: 'POST',
        data: { 
            'date': date,
            'time': selectedTime,
            'game_id': getUrlParameter('game_id')
        },
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(res) {

            var availableTimeSlots = res.available_timeslots;
            const persistedSelectedTime = JSON.parse(localStorage.getItem('selectedTime') || '[]');
            const start = new Date();
            start.setHours(1, 0, 0); // 01:00

            const end = new Date();
            end.setHours(23, 0, 0); // 23:00

            let htmlStr = '';

            while (start <= end) {
                const time = start.toTimeString().slice(0, 8);
                const ampm = start.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                
                availableTimeSlots = Object.values(availableTimeSlots);
                const isAvailable = availableTimeSlots.includes(time);
                const isSelected = isAvailable && persistedSelectedTime.includes(time);
                const selectedClass = isSelected ? 'selected' : '';

                if (isAvailable) {
                    htmlStr += `<div class="time-slot ${selectedClass}" data-time="${time}">${ampm}</div>`;
                } else {
                    // htmlStr += `<div class="time-slot striked" data-time="${time}"><s>${ampm}</s></div>`;
                }

                start.setHours(start.getHours() + 1);
            }
            $('#timeSlots').html(htmlStr);

            if (!res.grounds || res.grounds.length === 0) {
                // console.warn("No grounds available.");
                var str = '<div class="no-grounds">'
                            +'<i class="fa fa-landmark"></i>'
                            +'<p>No Grounds Available</p>'
                        +'</div>';
                $(".gallery-container").html(str);
                return;
            }

            var html = '';

            res.grounds.forEach(function(ground) {
                var imageArray = ground.ground_images ? ground.ground_images.split(',') : [];
                var firstImage = imageArray.length > 0 ? imageArray[0].trim() : 'default-image.jpg';

                html += `
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all ${ground.classname} zoomIn" 
                         data-wow-duration="2000ms">
                        <a href="${baseUrl}/ground-details?id=${window.btoa(ground.ground_id)}&date=${window.btoa(date)}">
                            <div class="featured-card">
                                <div class="image">
                                    <img src="${baseUrl}/${firstImage}" alt="${ground.ground_name}">
                                </div>
                                <div class="content">
                                    <div class="top-content">
                                        <div class="sports-box">
                                            <h6>${ground.category_name}</h6>
                                            <span class="date">${ground.ground_name}</span>
                                        </div>
                                        <div class="sports-box">
                                            <h6>AED ${ground.rate}</h6>
                                            <span class="date">Per Hour</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                `;
            });

            $(".gallery-container").html(html);
        },
        error: function(err) {
            console.error("Error fetching grounds:", err);
        }
    });
}

$('#timeSlots').on('click', '.time-slot', function () {
    if ($(this).find('s').length > 0) return; // Prevent clicking unavailable slots

    $(this).toggleClass('selected');

    // Update localStorage with current selected slots
    const selectedTimes = [];
    $('.time-slot.selected').each(function () {
        selectedTimes.push($(this).data('time'));
    });

    localStorage.setItem('selectedTime', JSON.stringify(selectedTimes));
});

function getUrlParameter(name) {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

function initializeTimeSlotClick() {
    $(".time-slot").off("click").on("click", function () {
        if (!$(this).hasClass("disabled")) {
            $(this).toggleClass("selected"); // Toggle selection

            let selectedTimes = $(".time-slot.selected").map(function () {
                return $(this).data("time");
            }).get();
        }
    });
}


</script>
@endsection