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
</style>
<!-- start of breadcumb-section -->
<div class="wpo-breadcumb-area">
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
</div>
<!-- end of wpo-breadcumb-section-->

<div id="registrationPopup" class="popup-overlay">
    <div class="popup-box">
        <h2>Registration Form</h2>
        <span class="close-modal" onclick="togglePopup('registrationPopup')">X</span>
        
        <!-- Registration Form -->
        <form id="registrationForm">
            <input type="text" id="name" name="name" placeholder="Name" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="text" id="phone" name="phone" placeholder="Phone" required>

            <div id="otpSection" style="display:none;">
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
            </div>
            <button type="button" id="sendOtpBtn">Send OTP</button>
            <button type="button" id="verifyOtpBtn" style="display:none;">Verify OTP</button>
        </form>
        
        <p>Already registered? <a onclick="togglePopup('registrationPopup'); togglePopup('loginPopup')">Click here</a></p>
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
        
        <p>Not registered? <a onclick="togglePopup('loginPopup'); togglePopup('registrationPopup')">Click here</a></p>
    </div>
</div>

<!-- start of places-videos-->
<section class="places-videos-section">
    <div class="container">
        <div class="booking-form-area">
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
        $("#time").val(decodedTime);
        $("#time").niceSelect('update');
    }

    generateTimeslot();

    $(".popup-overlay").on("click", function (event) {
        if ($(event.target).closest(".popup-box").length === 0) {
            $(this).hide(); // Hide popup when clicking outside
        }
    });
    
    $('.book-ground').on("click", function() {

        var selectedDate = $('#date').val();
        var selectedTime = $('#time').val();
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
                    if(response.status){
                        
                        var data = {
                            date: selectedDate,
                            time: selectedTime,
                            ground_id: groundId
                        };

                        bookGround(data)
                    }else{
                        togglePopup('registrationPopup');
                    }
                }
            });
            
        } else {
            alert('Please select a valid date, time, and ground.');
        }
    });
});

function bookGround(data){
    $.ajax({
        url: baseUrl + '/book-ground',
        type: 'POST',
        data: data,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alert("Booking confirmed.")
            generateTimeslot();
        },
        error: function(xhr, status, error) {
            console.error("Booking failed: " + error);
            alert('An error occurred. Please try again.');
        }
    });
}

$('#sendOtpBtn').on('click', function () {
    var email = $('#email').val();
    var phone = $('#phone').val();
    var name = $('#name').val();

    if (!email || !phone || !name) {
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
            if (response.success) {
                $('#otpSection').show();
                $('#sendOtpBtn').hide();
                $('#verifyOtpBtn').show();
            } else {
                alert('Error sending OTP.');
            }
        },
        error: function() {
            alert('Error sending OTP.');
        }
    });
});

$('#sendOtpBtnLogin').on('click', function () {
    var email = $('#loginEmail').val();

    if (!email) {
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
            alert('Error sending OTP.');
        }
    });
});

$('#verifyOtpBtn').on('click', function () {
    var email = $('#email').val();
    var phone = $('#phone').val();
    var name = $('#name').val();
    var otp = $('#otp').val();

    if (!email || !otp) {
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
            if (response.status == 200) {
                alert('Registration successful!');
            } else {
                alert('Invalid OTP. Please try again.');
            }
        },
        error: function() {
            alert('Error verifying OTP.');
        }
    });
});

$('#verifyOtpBtnLogin').on('click', function () {
    var email = $('#loginEmail').val();
    var otp = $('#loginotp').val();

    if (!email || !otp) {
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
            if (response.status == 200) {
                alert('Login successful!');
                togglePopup('loginPopup');
            } else {
                alert('Invalid User. Please try again.');
            }
        },
        error: function() {
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

            var allTimeSlot = response.allTimeSlot;
            var availableTimeSlot = Object.values(response.availabelTimeSlot);
            $("#time").niceSelect('destroy');
            $("#time").empty().append('<option value="">Select Time</option>');
            allTimeSlot.forEach(function(time) {
                var isAvailable = availableTimeSlot.includes(time);
                var optionText = formatTime(time);

                var option = $("<option></option>").text(optionText).val(time);
                
                if (!isAvailable) {
                    option.prop("disabled", true);
                }

                $("#time").append(option);
            });
            $("#time").niceSelect();
        }
    });
}
</script>
@endsection