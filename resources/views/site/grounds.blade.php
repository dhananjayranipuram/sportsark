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
</style>
<!-- start of breadcumb-section -->
<div class="wpo-breadcumb-area">
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
</div>
<!-- end of wpo-breadcumb-section-->
<section id="ground" class="featured-section section-padding">
    <div class="container">
        <div class="booking-form-area">
            <center><h2>Check Ground Avilability</h2></center>
            <form method="post" class="booking-form" id="booking-form-main" novalidate="novalidate">
                <div class="form-row">
                    <div class="form-field">
                        <label for="date">Select Date*</label>
                        <input type="date" class="form-control date-input" name="date" id="date" placeholder="Select Date" required>
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
                                <ul>
                                    <li>
                                        <span>{{ $value->category_name }}</span>
                                        <span class="date">{{ $value->ground_name }}</span>
                                    </li>
                                    <li>
                                        <span>AED {{ $value->rate }}</span>
                                        <span class="date">Per Hour</span>
                                    </li>
                                </ul>
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
        displayData();
    });

    $('#time').on("change", function() {
        displayData(); 
    });
});

function displayData() {
    var date = $("#date").val();
    var time = $("#time").val();
    $.ajax({
        url: baseUrl + '/grounds', // Ensure baseUrl is correct
        type: 'POST',
        data: { 
            'date': date,
            'time': time,
            'game_id': getUrlParameter('game_id')
        },
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(res) {
            console.log("API Response:", res); // Debugging log

            if (!res.grounds || res.grounds.length === 0) {
                console.warn("No grounds available.");
                $(".gallery-container").html("<p>No grounds available.</p>");
                return;
            }

            var html = '';

            res.grounds.forEach(function(ground) {
                var imageArray = ground.ground_images ? ground.ground_images.split(',') : [];
                var firstImage = imageArray.length > 0 ? imageArray[0].trim() : 'default-image.jpg';

                html += `
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all ${ground.classname} zoomIn" 
                         data-wow-duration="2000ms">
                        <a href="${baseUrl}/ground-details?id=${window.btoa(ground.ground_id)}&date=${window.btoa(date)}&time=${window.btoa(time)}">
                            <div class="featured-card">
                                <div class="image">
                                    <img src="${baseUrl}/${firstImage}" alt="${ground.ground_name}" onerror="this.onerror=null; this.src='default-image.jpg';">
                                </div>
                                <div class="content">
                                    <div class="top-content">
                                        <ul>
                                            <li>
                                                <span>${ground.category_name}</span>
                                                <span class="date">${ground.ground_name}</span>
                                            </li>
                                            <li>
                                                <span>AED ${ground.rate}</span>
                                                <span class="date">Per Hour</span>
                                            </li>
                                        </ul>
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


function getUrlParameter(name) {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}
</script>
@endsection