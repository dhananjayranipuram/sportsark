@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<section class="add-doctor">
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit Ground</h5>
            <form method="post" action="{{ url('/admin/edit-ground', $grounds->ground_id ?? '') }}" enctype="multipart/form-data">

            @csrf <!-- {{ csrf_field() }} -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="name" class="form-label">Ground Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $grounds->ground_name ?? '') }}">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="last_name" class="form-label">Game Name</label>
                        <select name="game" class="form-select">
                            <option value="" selected>Choose Game</option>
                            @foreach($game as $key => $value)
                                <option value="{{ $value->game_id }}" 
                                    @if(old('game', $grounds->category_id ?? '') == $value->game_id) selected @endif>
                                    {{ $value->game_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('game') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="rate" class="form-label">Rate</label>
                        <input type="text" class="form-control" name="rate" value="{{$grounds->rate}}">
                        @error('rate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Available Days</label>
                        @php
                            $selectedDays = old('available_days', explode(',', $grounds->working_days ?? ''));
                        @endphp

                        <select class="form-select select2" multiple="multiple" name="available_days[]">
                            <option value="">Select Available Week Days</option>
                            @foreach([6 => 'Sunday', 0 => 'Monday', 1 => 'Tuesday', 2 => 'Wednesday', 3 => 'Thursday', 4 => 'Friday', 5 => 'Saturday'] as $key => $day)
                                <option value="{{ $key }}" 
                                    @if(in_array($key, $selectedDays)) selected @endif>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                        @error('available_days') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="start" class="form-label">Start Time</label>
                        <input type="time" class="form-control" name="start" value="{{ old('start', $grounds->start_time ?? '') }}">
                        @error('start') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="end" class="form-label">End Time</label>
                        <input type="time" class="form-control" name="end" value="{{ old('end', $grounds->end_time ?? '') }}">
                        @error('end') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="duration" class="form-label">Duration (hh:mm)</label>
                        <input type="text" class="form-control" name="duration" value="{{ old('duration', $grounds->duration ?? '') }}">
                        @error('duration') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <textarea class="form-control" name="description" placeholder="Enter description">{{ old('description', $grounds->description ?? '') }}</textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <input type="file" name="images[]" multiple="multiple" accept="image/*">
                        @error('images') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                @php
                    $existingImages = explode(', ', $grounds->ground_images ?? '');
                @endphp
                @if(!empty($existingImages) &&  $grounds->ground_images != null)
                    <div class="row">
                        @foreach($existingImages as $image)
                            <div class="col-md-2 position-relative">
                                <img src="{{ asset($image) }}" alt="Ground Image" class="img-thumbnail">
                                <button type="button" class="btn btn-danger btn-sm delete-image" data-image="{{ $image }}" data-id="{{ $grounds->ground_id }}" style="position: absolute; top: 10px; right: 25px;">✖</button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <div class="col-12" style="color:red;">
                    @if ($errors->any())
                    <label>{{ $errors }}</label>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select Available Week Days",
        allowClear: true
    });

    $(".delete-image").click(function () {
        var image = $(this).data("image");
        var groundId = $(this).data("id");
        var button = $(this);

        
        $.ajax({
            url: "{{ url('/admin/delete-ground-image') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                image: image,
                ground_id: groundId
            },
            success: function (response) {
                if (response.status === 200) {
                    button.closest(".col-md-2").remove();
                    // alert("Image deleted successfully.");
                } else {
                    alert("Failed to delete the image.");
                }
            },
            error: function () {
                alert("An error occurred. Please try again.");
            }
        });
        
    });
});
</script>
@endsection