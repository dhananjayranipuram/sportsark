@extends('layouts.site')

@section('content')
<style>
    .booking-form-group {
        padding: 50px 0;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .booking-form-group i {
        font-size: 200px;
        color: #006d09;
        margin-bottom: 20px;
        animation: fadeInDown 1s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .booking-table th, .booking-table td {
        padding: 10px 20px;
        font-size: 18px;
    }

    .booking-table th {
        text-align: right;
        font-weight: 600;
        color: #333;
    }

    .booking-table td {
        text-align: left;
        color: #555;
    }
    #booking-details{
        padding: 0 25% 0 25%;
    }
    @media (max-width: 576px) {
        .booking-table th, .booking-table td {
            font-size: 16px;
            padding: 8px 15px;
        }
        #booking-details{
            padding: 0 0 0 0;
        }
        .booking-form-group i {
            font-size: 150px;
        }
    }
</style>
<div class="container">
    <div class="row">
        <div class="booking-form-group col-md-12 text-center" style="padding: 50px 0 50px 0;">
            @if($status === 'success')
                <i class="fas fa-check-circle" style="font-size: 200px; color: #006d09; margin-bottom: 20px;"></i>
                <h3>Booking Successful</h3>
                <p>Your booking has been confirmed. Thank you!</p>
                <div id="booking-details">
                <table class="table booking-table">
                    <tbody>
                        <tr>
                            <th>Booking Id :</th>
                            <td>{{$id}}</td>
                        </tr>
                        <tr>
                            <th>Ground Name :</th>
                            <td>{{$ground_name}}</td>
                        </tr>
                        <tr>
                            <th>Game :</th>
                            <td>{{$game_name}}</td>
                        </tr>
                        <tr>
                            <th>Booking Date :</th>
                            <td>{{$book_date}}</td>
                        </tr>
                        <tr>
                            <th>Booking Time :</th>
                            <td>{{$book_time}}</td>
                        </tr>
                        <tr>
                            <th>Rate :</th>
                            <td>AED {{$rate}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @else
                <i class="fas fa-times-circle" style="font-size: 200px; color: red; margin-bottom: 20px;"></i>
                <h3>Booking Failed</h3>
                <p>Something went wrong. Please try again.</p>
            @endif
        </div>
    </div>
</div>
@endsection
