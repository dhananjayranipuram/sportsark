@extends('layouts.admin')

@section('content')

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-6 col-xl-12">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item booking-count" data-value="today" href="#">Today</a></li>
                    <li><a class="dropdown-item booking-count" data-value="thismonth" href="#">This Month</a></li>
                    <li><a class="dropdown-item booking-count" data-value="thisyear" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Booking <span class="booking-day-label">| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="bookingCount">{{$booking->today_cnt}}</h6>
                      @if($booking->increase>=0)
                          @php($bookingClass = 'text-success')
                          @php($increase = 'Increase')
                      @else
                          @php($bookingClass = 'text-danger')
                          @php($increase = 'Decrease')
                      @endif
                      <span class="{{$bookingClass}} small pt-1 fw-bold booking-count-per">{{$booking->increase}}%</span> <span class="text-muted small pt-2 ps-1 booking-count-trend">{{$increase}}</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Customers Card -->
            <div class="col-xxl-6 col-xl-12">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item customer-count" data-value="today" href="#">Today</a></li>
                    <li><a class="dropdown-item customer-count" data-value="thismonth" href="#">This Month</a></li>
                    <li><a class="dropdown-item customer-count" data-value="thisyear" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Customers <span class="customer-day-label">| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                    <h6 id="customerCount">{{$customer->today_cnt}}</h6>
                      @if($customer->increase>=0)
                          @php($customerClass = 'text-success')
                          @php($increase = 'Increase')
                      @else
                          @php($customerClass = 'text-danger')
                          @php($increase = 'Decrease')
                      @endif

                      <span class="{{$customerClass}} small pt-1 fw-bold customer-count-per">{{$customer->increase}}%</span> <span class="text-muted small pt-2 ps-1 customer-count-trend">{{$increase}}</span>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

            

            <!-- Recent Sales -->
            <div class="col-lg-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item recent-appt" data-value="today" href="#">Today</a></li>
                    <li><a class="dropdown-item recent-appt" data-value="thismonth" href="#">This Month</a></li>
                    <li><a class="dropdown-item recent-appt" data-value="thisyear" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Recent Bookings <span class="recent-appt-day-label">| Today</span></h5>

                  <table class="table datatable" id="recent-appt">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Book Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $key => $value)
                        <tr>
                            <td scope="row">{{$value->booking_id}}</td>
                            <td>{{$value->customer_name}}</td>
                            <td>{{$value->book_date}}</td>
                            <td>{{$value->book_time}}</td>
                            <td><span class="badge bg-success">Booked</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

            
            

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- Website Traffic -->
          <div class="col-lg-12">
              <div class="card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item doc-wise-appt" data-value="today" href="#">Today</a></li>
                    <li><a class="dropdown-item doc-wise-appt" data-value="thismonth"href="#">This Month</a></li>
                    <li><a class="dropdown-item doc-wise-appt" data-value="thisyear" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body pb-0">
                  <h5 class="card-title">Ground wise Appt <span class="doc-wise-day-label">| Today</span></h5>

                  <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

                  <script>
                    var doc_appt = @json($doc_appt);
                    document.addEventListener("DOMContentLoaded", () => {
                      echarts.init(document.querySelector("#trafficChart")).setOption({
                        tooltip: {
                          trigger: 'item'
                        },
                        legend: {
                          top: '1%',
                          left: 'left',
                          type:'scroll'
                        },
                        series: [{
                          name: 'Ground wise appointment',
                          type: 'pie',
                          radius: ['40%', '70%'],
                          avoidLabelOverlap: true,
                          label: {
                            show: false,
                            position: 'center'
                          },
                          emphasis: {
                            label: {
                              show: true,
                              fontSize: '18',
                              fontWeight: 'bold'
                            }
                          },
                          labelLine: {
                            show: false
                          },
                          data: doc_appt
                        }]
                      });
                    });
                  </script>

                </div>
              </div>
            </div><!-- End Website Traffic -->

          

          

          

        </div><!-- End Right side columns -->

      </div>
    </section>
    


<script>
$(document).ready(function () { 
    $("#recent-appt").DataTable();
});
</script>

@endsection