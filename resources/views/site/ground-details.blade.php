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

#time {            
    max-height: 200px;            
    overflow-y: auto;              
}
/* Responsive Design */
@media screen and (max-width: 600px) {
    .form-row {
        flex-direction: column;
        gap: 15px;
    }
}

/* Basic Popup Styles */
.popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.popup-box {
    background: white;
    padding: 20px;
    border-radius: 5px;
    width: 400px;
    text-align: center;
}

.popup-box input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
}

.popup-box button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

.close-modal {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    font-size: 20px;
    color: #aaa;
}

.unavailable {
    text-decoration: line-through;
    color: gray;
}

@media screen and (max-width: 600px) {
    .booking-form-area{
        max-width: unset;
    }
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
.booking-form-container .time-slot:hover {
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
                    <h2>Club Ground</h2>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><span>Club Ground</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- end of wpo-breadcumb-section-->

<div id="registrationPopup" class="popup-overlay">
    <div class="popup-box">
        <h2>Sign Up</h2>
        <span class="close-modal" onclick="togglePopup('registrationPopup')">X</span>
        
        <!-- Registration Form -->
        <form id="registrationForm">
            <input type="text" id="name" name="name" placeholder="Name" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="text" id="phone" name="phone" placeholder="Phone" required>

            <div id="otpSection" style="display:none;">
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
            </div>
            <button type="button" id="sendOtpBtn">Create Account</button>
            <button type="button" id="verifyOtpBtn" style="display:none;">Verify OTP</button>
        </form>
        
        <p>Already Signed up? <a onclick="togglePopup('registrationPopup'); togglePopup('loginPopup')">Sign In</a></p>
    </div>
</div>

<div id="loginPopup" class="popup-overlay">
    <div class="popup-box">
        <h2>Login Form</h2>
        <span class="close-modal" onclick="togglePopup('loginPopup')">X</span>
        
        <!-- Login Form -->
        <form id="loginForm">
            <input type="email" id="loginEmail" name="email" placeholder="Email" required>
            <div id="otpSectionLogin" style="display:none;">
                <input type="text" id="loginotp" name="otp" placeholder="OTP" required>
            </div>

            <button type="button" id="sendOtpBtnLogin">Send OTP</button>
            <button type="button" id="verifyOtpBtnLogin" style="display:none;">Verify OTP</button>
        </form>
        
        <p>Not Signed up? <a onclick="togglePopup('loginPopup'); togglePopup('registrationPopup')">Sign Up</a></p>
    </div>
</div>

<!-- start of places-videos-->
<section class="places-videos-section">
    <div class="container">
        <!-- <div class="booking-form-area">
            <center><h2>Check Ground Avilability</h2></center>
            <form method="post" class="booking-form" id="booking-form-main" novalidate="novalidate">
                <div class="form-row">
                    <div class="form-field">
                        <label for="date">Select Date*</label>
                        <input type="date" class="form-control date-input" name="date" id="date" placeholder="Select Date" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-field">
                        <label for="time">Select Time*</label>
                        <select class="form-control time-select" name="time" id="time" required>
                            <option value="">Select Time</option>
                            @foreach($allTimeSlot as $time)
                                @if(in_array($time, $availabelTimeSlot))
                                    <option value="{{ $time }}">{{ date('h:i A', strtotime($time)) }}</option>
                                @else
                                    <option value="{{ $time }}" disabled style="text-decoration: line-through;">{{ date('h:i A', strtotime($time)) }}</option>
                                @endif
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
                        <label class="form-label">Select Time Slots*</label>
                        <div class="time-slot-container" id="timeSlots">
                            @foreach($allTimeSlot as $time)
                                @if(in_array($time, $availabelTimeSlot))
                                    <div class="time-slot" data-time="{{ $time }}">{{ date('h:i A', strtotime($time)) }}</div>
                                @else
                                    <div class="time-slot disabled" data-time="{{ $time }}">{{ date('h:i A', strtotime($time)) }}</div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
        
                <!-- <button type="submit" class="btn btn-submit">Confirm Booking</button> -->
            </form>
        </div>
        <div class="row align-items-center">
            <div class="col-xl-5 order-xl-2  col-12">
                <div class="wpo-section-title s2 wow fadeInRightSlow" data-wow-duration="1700ms">
                    <!-- <span>// Ground</span> -->
                    <h3>{{$grounds->ground_name}}</h3>
                    <p>{{$grounds->description}}</p>
                    
                    <a class="theme-btn book-ground">Book Now</a>
                </div>
            </div>
            <div class="col-xl-7 order-xl-1 col-12">
                <div class="videos-wraper videos-slide wow fadeInLeftSlow" data-wow-duration="1700ms">
                    @php
                        $images = explode(', ', $grounds->ground_images);
                    @endphp
                    <div class="top-slide">
                        @foreach($images as $image)
                        <div class="item">
                            <div class="image">
                                <img src="{{ asset($image) }}" alt="{{ $grounds->ground_name }}">
                                
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="bottom-slider">
                        @foreach($images as $image)
                        <div class="item">
                            <div class="image">
                                <img src="{{ asset($image) }}" alt="{{ $grounds->ground_name }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div></br></br>
</section>
<!-- end of places-videos-->

<script>
var pendingBookingData = null;
$(document).ready(function () {

    var urlParams = new URLSearchParams(window.location.search);
    var encodedDate = urlParams.get('date');
    var encodedTime = urlParams.get('time');
    if (encodedDate) {
        var decodedDate = atob(encodedDate);
        $("#date").val(decodedDate);
    }
    if (encodedTime) {
        var decodedTime = atob(encodedTime);
        
        setTimeout(function () {
            // $("#time").val(decodedTime);
            // $("#time").niceSelect('update');
            $(".time-slot").each(function () {
                if (!$(this).hasClass("disabled") && $(this).attr("data-time") === decodedTime) {
                    $(this).addClass("selected");
                }
            });
        }, 400);
        
    }

    generateTimeslot();

    $(".popup-overlay").on("click", function (event) {
        if ($(event.target).closest(".popup-box").length === 0) {
            $(this).hide();
        }
    });
    
    $('.book-ground').on("click", function() {
        $(".preloader").show();

        event.preventDefault();
        let selectedDate = $('#date').val();
        let selectedTime = [];
        let allSlots = $('.time-slot');
        let selectedSlots = $('.time-slot.selected');

        if (selectedSlots.length === 0) {
            alert("Please select at least one time slot.");
            $(".preloader").hide();
            return;
        }

        let firstSelectedIndex = allSlots.index(selectedSlots.first());
        let lastSelectedIndex = allSlots.index(selectedSlots.last());
        let isContinuous = true;

        for (let i = firstSelectedIndex; i <= lastSelectedIndex; i++) {
            if (allSlots[i].classList.contains('disabled')) {
                isContinuous = false;
                break;
            }
        }

        if (!isContinuous) {
            alert("Sandwich selection is not allowed. Please select continuous time slots without gaps.");
            $(".preloader").hide();
            return;
        }

        selectedSlots.forEach(slot => selectedTime.push(slot.dataset.time));
        var urlParams = new URLSearchParams(window.location.search);
        var groundId = urlParams.get('id');
        if (selectedDate && selectedTime && groundId) {
            
            $.ajax({
                url: baseUrl + '/check-login',
                type: 'POST',
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $(".preloader").hide();
                    if(response.status){
                        
                        bookGround({ date: selectedDate, time: selectedTime, ground_id: groundId });
                    }else{
                        pendingBookingData = { date: selectedDate, time: selectedTime, ground_id: groundId };
                        togglePopup('registrationPopup');
                    }
                }
            });
            
        } else {
            $(".preloader").hide();
            alert('Please select a valid date, time, and ground.');
        }
    });
});

function bookGround(data){
    $(".preloader").show();
    $.ajax({
        url: baseUrl + '/book-ground',
        type: 'POST',
        data: data,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            
            $(".preloader").hide();
            generateTimeslot();
            setTimeout(function () {
                var temp = '';
                if (response.id) {
                    temp = '/' + response.id;
                }
                window.location.href = baseUrl + '/booking-status/success' + temp;
            }, 2500);
        },
        error: function() {
            $(".preloader").hide();
            setTimeout(function () {
                window.location.href = baseUrl + '/booking-status/fail';
            }, 2500);
        }
    });
}

$('#sendOtpBtn').on('click', function () {
    $(".preloader").show();
    var email = $('#email').val();
    var phone = $('#phone').val();
    var name = $('#name').val();

    if (!email || !phone || !name) {
        $(".preloader").hide();
        alert('Please fill all the fields.');
        return;
    }

    $.ajax({
        url: baseUrl + '/send-otp',
        type: 'POST',
        data: {
            email: email,
            phone: phone,
            name: name
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $(".preloader").hide();
            if (response.success) {
                $('#otpSection').show();
                $('#sendOtpBtn').hide();
                $('#verifyOtpBtn').show();
            } else {
                alert('Error sending OTP.');
            }
        },
        error: function() {
            $(".preloader").hide();
            alert('Error sending OTP.');
        }
    });
});

$('#sendOtpBtnLogin').on('click', function () {
    $(".preloader").show();
    var email = $('#loginEmail').val();

    if (!email) {
        $(".preloader").hide();
        alert('Please fill all the fields.1');
        return;
    }

    $.ajax({
        url: baseUrl + '/send-otp-login',
        type: 'POST',
        data: {
            email: email
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $(".preloader").hide();
            if (response.success) {
                alert('OTP sent successfully!');
                $('#otpSectionLogin').show();
                $('#sendOtpBtnLogin').hide();
                $('#verifyOtpBtnLogin').show();
            } else {
                alert('Error sending OTP.');
            }
        },
        error: function() {
            $(".preloader").hide();
            alert('Error sending OTP.');
        }
    });
});

$('#verifyOtpBtn').on('click', function () {
    $(".preloader").show();
    var email = $('#email').val();
    var phone = $('#phone').val();
    var name = $('#name').val();
    var otp = $('#otp').val();

    if (!email || !otp) {
        $(".preloader").hide();
        alert('Please enter your email and OTP.');
        return;
    }

    $.ajax({
        url: baseUrl + '/verify-otp',
        type: 'POST',
        data: {
            email: email,
            phone: phone,
            name: name,
            otp: otp
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $(".preloader").hide();
            if (response.status == 200) {
                alert('Registration successful!');
                if (pendingBookingData) {
                    bookGround(pendingBookingData);
                    pendingBookingData = null;
                }
            } else {
                alert('Invalid OTP. Please try again.');
            }
        },
        error: function() {
            $(".preloader").hide();
            alert('Error verifying OTP.');
        }
    });
});

$('#verifyOtpBtnLogin').on('click', function () {
    $(".preloader").show();
    var email = $('#loginEmail').val();
    var otp = $('#loginotp').val();

    if (!email || !otp) {
        $(".preloader").hide();
        alert('Please enter your email and OTP.');
        return;
    }

    $.ajax({
        url: baseUrl + '/verify-otp-login',
        type: 'POST',
        data: {
            email: email,
            otp: otp
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $(".preloader").hide();
            if (response.status == 200) {
                alert('Login successful!');
                if (pendingBookingData) {
                    bookGround(pendingBookingData);
                    pendingBookingData = null;
                }
                togglePopup('loginPopup');
            } else {
                alert('Invalid User. Please try again.');
            }
        },
        error: function() {
            $(".preloader").hide();
            alert('Error verifying OTP.');
        }
    });
});

function togglePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup.style.display === "flex") {
        popup.style.display = "none";
    } else {
        popup.style.display = "flex";
    }
}

function formatTime(time) {
    var [hours, minutes] = time.split(":");
    var suffix = hours >= 12 ? "PM" : "AM";
    hours = ((hours % 12) || 12); // Convert 24-hour to 12-hour format
    return hours + ":" + minutes + " " + suffix;
}

$('#date').on('change', function () {
    generateTimeslot();
});

function generateTimeslot(){
    $(".preloader").show();
    var selectedDate = $('#date').val();
    var urlParams = new URLSearchParams(window.location.search);
    var groundId = atob(urlParams.get('id'));

    $.ajax({
        url: baseUrl + '/get-ground-timeslot',
        type: 'POST',
        data: {
            date: selectedDate,
            ground_id: groundId
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $(".preloader").hide();
            // var allTimeSlot = response.allTimeSlot;
            // var availableTimeSlot = Object.values(response.availabelTimeSlot);
            // $("#time").niceSelect('destroy');
            // $("#time").empty().append('<option value="">Select Time</option>');
            // allTimeSlot.forEach(function(time) {
            //     var isAvailable = availableTimeSlot.includes(time);
            //     var optionText = formatTime(time);

            //     var option = $("<option></option>").text(optionText).val(time);
                
            //     if (!isAvailable) {
            //         option.prop("disabled", true);
            //     }

            //     $("#time").append(option);
            // });
            // $("#time").niceSelect();

            var allTimeSlot = response.allTimeSlot;
            var availableTimeSlot = Object.values(response.availabelTimeSlot);
            
            // Clear and update the time slot container
            $("#timeSlots").empty();
            
            allTimeSlot.forEach(function(time) {
                var isAvailable = availableTimeSlot.includes(time);
                var formattedTime = formatTime(time);

                var timeSlot = $("<div></div>")
                    .addClass("time-slot")
                    .attr("data-time", time)
                    .text(formattedTime);

                if (!isAvailable) {
                    timeSlot.addClass("disabled").css({
                        "pointer-events": "none",
                        "opacity": "0.5",
                        "background-color": "#ddd",
                        "cursor": "not-allowed"
                    });
                }

                $("#timeSlots").append(timeSlot);
            });

            initializeTimeSlotClick();
        }
    });
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

<script>
    // Set minimum date to today
    document.addEventListener("DOMContentLoaded", function () {
        let today = new Date().toISOString().split("T")[0];
        document.getElementById("date").setAttribute("min", today);
    });

    // Time slot selection
    document.querySelectorAll('.time-slot').forEach(slot => {
        slot.addEventListener('click', function () {
            this.classList.toggle('selected');
        });
    });

    
</script>
@endsection