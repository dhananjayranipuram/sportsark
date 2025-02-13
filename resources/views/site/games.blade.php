@extends('layouts.site')

@section('content')
<!-- start of breadcumb-section -->
<div class="wpo-breadcumb-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="wpo-breadcumb-wrap">
                    <h2>Games</h2>
                    <ul>
                        <li><a href="{{ url('/home') }}">Home</a></li>
                        <li><span>Games</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of wpo-breadcumb-section-->
<section class="places-videos-section section-padding">
    <div class="container">
        <div class="row align-items-center">

            @foreach($games as $key => $value)
                @if(strtolower($value->game_name) !== 'all')
                    <div class="col-xl-3  col-12">
                        <div class="featured-card">
                            <div class="image">
                                <a href="football.html"><img src="{{asset($value->image_path)}}" alt="">
                                    <h3 style=" text-align: center; color: black; padding: 13px 0px 0px 0px; ">{{$value->game_name}}</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach                       

        </div>
    </div>
</section>
@endsection