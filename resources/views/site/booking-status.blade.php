@extends('layouts.site')

@section('content')
<div class="container">
    <div class="row">
        <div class="booking-form-group col-md-12 text-center" style="padding: 50px 0 50px 0;">
            @if($status === 'success')
                <i class="fas fa-check-circle" style="font-size: 200px; color: #006d09; margin-bottom: 20px;"></i>
                <h3>Booking Successful</h3>
                <p>Your booking has been confirmed. Thank you!</p>
            @else
                <i class="fas fa-times-circle" style="font-size: 200px; color: red; margin-bottom: 20px;"></i>
                <h3>Booking Failed</h3>
                <p>Something went wrong. Please try again.</p>
            @endif
        </div>
    </div>
</div>
@endsection
