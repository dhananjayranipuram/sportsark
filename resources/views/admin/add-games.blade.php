@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<section class="add-doctor">
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Add New Game</h5>
            <form method="post" action="{{ url('/admin/add-games') }}" enctype="multipart/form-data">
            @csrf <!-- {{ csrf_field() }} -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Game Name</label>
                        <input type="text" class="form-control" name="name" value="{{old('name')}}">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">Game Image</label>
                        <input type="file" name="images[]" multiple="multiple" accept="image/*">
                        @error('images') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                
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
});
</script>
@endsection