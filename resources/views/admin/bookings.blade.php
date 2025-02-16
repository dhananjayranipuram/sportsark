@extends('layouts.admin')

@section('content')

<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div>
                <h5 class="card-title" style="display:inline-block">All Bookings</h5>
                
                <!-- <div class="row g-5"> -->
              </div>
                <form class="row g-5 needs-validation" method="post" action="{{ url('/admin/bookings') }}">
                @csrf <!-- {{ csrf_field() }} -->
                  <div class="col-md-5">
                    <label for="validationDefault02" class="form-label">Date</label>
                    <div id="reportrange" class="word-wrap-custom" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                        <input type="hidden" id="from" name="from">
                        <input type="hidden" id="to" name="to">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label for="validationDefault02" class="form-label">Games</label>
                      <select name="game" class="form-select speciality-select">
                            <option value="">All Games</option>
                            @foreach($games as $key => $value)
                                <option value="{{$value->game_id}}" @if(old('game') == $value->game_id) selected @endif>{{$value->game_name}}</option>
                            @endforeach
                      </select>
                  </div>
                  <div class="col-md-3">
                    <label for="validationDefault02" class="form-label">Grounds</label>
                    <select name="ground" class="form-select doctor-select">
                        <option value="">All Grounds</option>
                        @foreach($grounds as $key => $value)
                            <option value="{{$value->ground_id}}" @if(old('ground') == $value->ground_id) selected @endif>{{$value->ground_name}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-1" style="align-content: end; padding-left: 0px;">
                    <button class="btn btn-primary appt-search-button" type="submit">Search</i></button>
                  </div>
                  
                </form>
              <!-- </div> -->
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Booking ID</th>
                    <th>Ground Name</th>
                    <th>Game Name</th>
                    <th data-type="date" data-format="YYYY/MM/DD">Booked Date</th>
                    <th>Booked Time</th>
                    <!-- <th style="min-width:110px;">Action</th> -->
                  </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $key => $value)
                        <tr>
                            <td>{{$value->booking_id}}</td>
                            <td>{{$value->ground_name}}</td>
                            <td>{{$value->game_name}}</td>
                            <td>{{$value->book_date}}</td>
                            <td>{{$value->book_time}}</td>
                            <td><div >
                              <!-- <a href="{{ url('/admin/edit-booking') }}?id={{$value->booking_id}}" class="btn btn-default"><i class="fa fa-edit"></i></a> -->
                              <!-- <a href="#" class="btn btn-default deleteBooking" data-id="{{$value->booking_id}}"><i class="fa fa-trash"></i></a> -->
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
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript">
    $(function() {
        var fromDate = "{{ old('from') ?? session('bookingFilter.from') ?? '' }}";
        var toDate = "{{ old('to') ?? session('bookingFilter.to') ?? '' }}";

        var start = fromDate === '' ? moment() : moment(fromDate);
        var end = toDate === '' ? moment() : moment(toDate);

        function cb(start, end) {
            if (start.format('DD-MM-Y') == '01-01-1970') {
                $('#reportrange span').html('Starting point - ' + end.format('DD-MM-Y'));
            } else {
                $('#reportrange span').html(start.format('DD-MM-Y') + ' - ' + end.format('DD-MM-Y'));
            }
            $("#from").val(start.format('YYYY-MM-DD')); // Use full YYYY-MM-DD format for backend
            $("#to").val(end.format('YYYY-MM-DD'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Maximum': [moment("1970-01-01"), moment()],
            }
        }, cb);

        cb(start, end);
    });


    $(document).ready(function () { 
    

        $('.deleteBooking').click(function(){
            if(confirm("Do you want to delete this Booking?")){
                $.ajax({
                    url: baseUrl + '/admin/delete-booking',
                    type: 'post',
                    data: {'id':$(this).attr("data-id")},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( html ) {
                        if(html=='1'){
                            location.reload();
                        }
                    }
                });
            }
        });

        $('.speciality-select').change(function(){
            $(".needs-validation").submit();
        });

        $('.doctor-select').change(function(){
            $(".needs-validation").submit();
        });

        $('.ranges li').click(function(){
            if($(this).attr('data-range-key')!='Custom Range'){
                setTimeout(function () {
                    // $("#booking-table").submit();
                    $(".needs-validation").submit();
                }, 200);
            }
        });
        $('.applyBtn').click(function(){
            setTimeout(function () {
                $(".needs-validation").submit();
            }, 200);
        });
    });
    </script>    
@endsection