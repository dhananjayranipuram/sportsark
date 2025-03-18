@extends('layouts.admin')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Customers</h5>
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Table with stripped rows -->
                    <table class="table datatable" id="customerTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $key => $value)
                                <tr >
                                    <td>{{$value->id}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->email}}</td>
                                    <td>{{$value->phone}}</td>
                                    <td><span id="status-{{ $value->id }}" class="badge {{ $value->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $value->status }}
                                        </span></td>
                                    <td><input type="checkbox" class="user-status" data-id="{{ $value->id }}" 
                                    {{ $value->status == 'Active' ? 'checked' : '' }}></td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#customerTable').DataTable({
            "columnDefs": [
                { "className": "text-start", "targets": 0 }
            ]
        });
    });

    $(".user-status").on("change", function () {
        var userId = $(this).data("id");
        var isChecked = $(this).prop("checked");
        var newStatus = isChecked ? 1 : 0;

        $.ajax({
            url: baseUrl + "/admin/update-user-status",
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
    
</script>
@endsection