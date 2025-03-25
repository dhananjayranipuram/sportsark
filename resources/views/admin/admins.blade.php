@extends('layouts.admin')

@section('content')
<style>
.table-responsive {
    width: 100%;
    overflow-x: auto;
}
.table {
    width: 100%;
    min-width: 600px; /* Prevents column collapsing */
    border-collapse: collapse;
}
</style>
<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Admins</h5>
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div style="text-align: right;"><a style="padding-right: 10px;" href="{{ url('/admin/add-games') }}" class="addNew" data-bs-toggle="modal" data-bs-target="#addAdminModal"><i class="bi bi-person-plus"></i> Add New Admin</a></div>
                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table class="table datatable" id="customerTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $key => $value)
                                    <tr >
                                        <td>{{$value->id}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->email}}</td>
                                        <td><span id="status-{{ $value->id }}" class="badge {{ $value->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $value->status }}
                                            </span></td>
                                        <td><input type="checkbox" class="user-status" data-id="{{ $value->id }}" 
                                        {{ $value->status == 'Active' ? 'checked' : '' }}></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table with stripped rows -->

                </div>
            </div>

        </div>
    </div>
</section>
<!-- Add New Game Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addGameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGameModalLabel">Add New Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('/admin/add-admins') }}" id="addGameForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" value="{{old('name')}}">
                            <span class="text-danger name"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" value="{{old('email')}}">
                            <span class="text-danger email"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="password" name="password" value="{{ old('password') }}">
                                <button type="button" class="btn btn-primary" id="generatePassword">Generate</button>
                            </div>
                            <span class="text-danger password"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-primary saveAdmin">Submit</button>
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
    $('#customerTable').DataTable({
        "columnDefs": [
            { "className": "text-start", "targets": 0 }
        ]
    });

    $("#generatePassword").click(function () {
        $("#password").val(generatePassword());
    });

    $(".saveAdmin").click(function (e) {
        e.preventDefault();

        let form = $("#addGameForm");
        let formData = form.serialize();
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    alert(response.message);
                    $("#addAdminModal").modal("hide");
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                
                $(".text-danger").html("");

                $.each(errors, function (key, value) {
                    $("." + key).html(value[0]);
                });

                setTimeout(function () {
                    $.each(errors, function (key, value) {
                        $("." + key).html("");
                    });
                }, 5000);
            }
        });
    });
});


$(".user-status").on("change", function () {
    var userId = $(this).data("id");
    var isChecked = $(this).prop("checked");
    var newStatus = isChecked ? 1 : 0;

    $.ajax({
        url: baseUrl + "/admin/update-admin-status",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            id: userId,
            status: newStatus
        },
        success: function (response) {
            if (response.status === 200) {
                $("#status-" + userId)
                    .removeClass("bg-success bg-secondary")
                    .addClass(newStatus == 1 ? "bg-success" : "bg-secondary")
                    .text(newStatus == 1 ? "Active" : "Inactive");
                
            } else {
                alert("Error updating customer status.");
            }
            
        },
        error: function () {
            alert("Error updating status.");
        }
    });
});
    
function generatePassword(length = 12) {
    const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%&*!?";
    let password = "";
    for (let i = 0; i < length; i++) {
        password += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return password;
}    
</script>
@endsection