@extends('layouts.admin')

@section('content')

<!-- <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet"> -->
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    
    
<script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>

<script>
$(document).ready(function () { 
    $("#recent-appt").DataTable();
});
</script>

@endsection