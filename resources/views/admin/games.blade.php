@extends('layouts.admin')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Games</h5>
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div style="text-align: right;"><a style="padding-right: 10px;" href="{{ url('/admin/add-games') }}" class="addNew" data-bs-toggle="modal" data-bs-target="#addGameModal"><i class="bi bi-person-plus"></i> Add New Game</a></div>
                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                            <th>Game ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th style="width: 20%; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $key => $value)
                                <tr >
                                    <td>{{$value->game_id}}</td>
                                    <td>{{$value->game_name}}</td>
                                    <td>{{$value->status}}</td>
                                    <td style="width: 20%; text-align: center;"><div >
                                        <a href="#" class="btn btn-default edit-game" data-id="{{$value->game_id}}"><i class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-default deleteGame" data-id="{{$value->game_id}}"><i class="fa fa-trash"></i></a>
                                    </div></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="editGameModal" tabindex="-1" aria-labelledby="editGameModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editGameModalLabel">Edit Game</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editGameForm">
          @csrf
          <input type="hidden" name="game_id" id="game_id">
          
          <div class="mb-3">
            <label for="game_name" class="form-label">Game Name</label>
            <input type="text" class="form-control" id="game_name" name="game_name">
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Add New Game Modal -->
<div class="modal fade" id="addGameModal" tabindex="-1" aria-labelledby="addGameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGameModalLabel">Add New Game</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('/admin/add-games') }}" id="addGameForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Game Name</label>
                            <input type="text" class="form-control" name="name" value="{{old('name')}}">
                            <span class="text-danger gameName"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Game Image</label>
                            <input type="file" name="images[]" multiple="multiple" accept="image/*"><br>
                            <span class="text-danger gameImage"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-primary saveGame">Submit</button>
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
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.saveGame').on('click', function (e) {
            // Prevent the form from submitting immediately
            e.preventDefault();

            // Get the form and inputs
            var form = $('#addGameForm');
            var data = [];
            data['name'] = $('input[name="name"]');
            data['image'] = $('input[name="images[]"]');
            if(validateForm(data)){
                form.submit();
            }
            
        });
    });

    $('.deleteGame').on('click', function () {
        var gameId = $(this).data('id');

        if (confirm("Are you sure you want to delete this game?")) {
            $.ajax({
                url: baseUrl + '/admin/delete-game',
                method: 'POST',
                dataType: "json",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: gameId
                },
                success: function (response) {
                    if (response.status == 200) {
                        $('tr').find('td').filter(function() {
                            return $(this).text() == gameId;
                        }).closest('tr').remove();
                        alert('Game deleted successfully.');
                    } else {
                        alert('An error occurred while deleting the game.');
                    }
                },
                error: function (xhr, status, error) {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });

    $(".edit-game").on("click", function() {
        var gameId = $(this).data('id');
        $.ajax({
            url: baseUrl + '/admin/get-game-data',
            method: 'POST',
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: gameId
            },
            success: function (response) {
                var game = response.data;
                $('#game_id').val(game.game_id);
                $('#game_name').val(game.game_name);
                $('#status').val(game.active);
                $('#editGameModal').modal('show');
            },
            error: function (xhr, status, error) {
                alert('An error occurred. Please try again.');
            }
        });
        
    });

    $('#editGameForm').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: baseUrl + '/admin/update-game',
            method: 'POST',
            data: formData,
            success: function(response) {
                if(response.status === 200) {
                    alert("Game updated successfully!");
                    $('#editGameModal').modal('hide');
                    location.reload();
                } else {
                    alert("Something went wrong!");
                }
            },
            error: function() {
                alert("An error occurred while updating the game.");
            }
        });
    });

    function validateForm(data){
        var chk = 0;
        if (data['name'].val().trim() === '') {
            $(".gameName").html("Game name is required.");
            chk = 1;
        }

        if (data['image'].get(0).files.length === 0) {
            $(".gameImage").html("Game name is required.");
            chk = 1;
        }

        if (chk == 1){
            return false;
        }else{
            return true;
        }
    }
</script>
@endsection