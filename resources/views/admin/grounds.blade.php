@extends('layouts.admin')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Grounds</h5>
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div style="text-align: right;"><a style="padding-right: 10px;" href="{{ url('/admin/add-ground') }}" class="addNew"><i class="bi bi-person-plus"></i> Add New Ground</a></div>
                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                            <th>Ground ID</th>
                            <th>Name</th>
                            <th>Rate</th>
                            <th>Category</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grounds as $key => $value)
                                <tr >
                                    <td>{{$value->ground_id}}</td>
                                    <td>{{$value->ground_name}}</td>
                                    <td>{{$value->rate}}</td>
                                    <td>{{$value->category_name}}</td>
                                    <td><div >
                                        <a href="{{ url('/admin/edit-ground') }}/{{$value->ground_id}}" class="btn btn-default"><i class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-default deleteDoc" data-id="{{$value->ground_id}}"><i class="fa fa-trash"></i></a>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('.deleteDoc').on('click', function () {
        var groundId = $(this).data('id');

        if (confirm("Are you sure you want to delete this ground?")) {

            $.ajax({
                url: baseUrl + '/admin/delete-ground',
                method: 'POST',
                dataType: "json",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: groundId
                },
                success: function (response) {
                    if (response.status == 200) {
                        $('tr').find('td').filter(function() {
                            return $(this).text() == groundId;
                        }).closest('tr').remove();
                        alert('Ground deleted successfully.');
                    } else {
                        alert('An error occurred while deleting the ground.');
                    }
                },
                error: function (xhr, status, error) {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });
});
</script>
@endsection