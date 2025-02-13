$(document).ready(function () { 
    $(document).on("click", ".booking-count" , function(e) { 
        $(".overlay").show();
        var dayValue = $(this).attr('data-value');
        $.ajax({
            url: baseUrl + '/admin/get-dashboard-booking-data',
            type: 'post',
            data: {'period':$(this).attr('data-value'),'card':'booking-count'},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( html ) {
                if(html){
                    $("#bookingCount").html(html.booking.today_cnt);
                    $(".booking-count-per").html(html.booking.increase + '%');
                    
                    if(dayValue=='today'){
                        $(".booking-day-label").html('| Today');
                    }else if(dayValue=='thismonth'){
                        $(".booking-day-label").html('| This Month');
                    }else if(dayValue=='thisyear'){
                        $(".booking-day-label").html('| This Year');
                    }
                    
                    if(html.booking.increase>=0){
                        $(".booking-count-per").removeClass('text-success');
                        $(".booking-count-per").removeClass('text-danger');
                        $(".booking-count-per").addClass('text-success');
                        $(".booking-count-trend").html('Increase');
                    }else{
                        $(".booking-count-per").removeClass('text-success');
                        $(".booking-count-per").removeClass('text-danger');
                        $(".booking-count-per").addClass('text-danger');
                        $(".booking-count-trend").html('Decrease');
                    }                    
                }
                $(".overlay").hide();
            }
        });
    });

    $(document).on("click", ".customer-count" , function(e) { 
        $(".overlay").show();
        var dayValue = $(this).attr('data-value')
        $.ajax({
            url: baseUrl + '/admin/get-dashboard-booking-data',
            type: 'post',
            data: {'period':$(this).attr('data-value'),'card':'customer-count'},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( html ) {
                if(html){
                    $("#customerCount").html(html.customer.today_cnt);
                    $(".customer-count-per").html(html.customer.increase + '%');

                    if(dayValue=='today'){
                        $(".customer-day-label").html('| Today');
                    }else if(dayValue=='thismonth'){
                        $(".customer-day-label").html('| This Month');
                    }else if(dayValue=='thisyear'){
                        $(".customer-day-label").html('| This Year');
                    }

                    if(html.customer.increase>=0){
                        $(".customer-count-per").removeClass('text-success');
                        $(".customer-count-per").removeClass('text-danger');
                        $(".customer-count-per").addClass('text-success');
                        $(".customer-count-trend").html('Increase');
                    }else{
                        $(".customer-count-per").removeClass('text-success');
                        $(".customer-count-per").removeClass('text-danger');
                        $(".customer-count-per").addClass('text-danger');
                        $(".customer-count-trend").html('Decrease');
                    }
                }
                $(".overlay").hide();
            }
        });
    });

    $(document).on("click", ".doc-wise-appt" , function(e) { 
        $(".overlay").show();
        var dayValue = $(this).attr('data-value')
        $.ajax({
            url: baseUrl + '/admin/get-dashboard-booking-data',
            type: 'post',
            data: {'period':$(this).attr('data-value'),'card':'pie-chart'},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( html ) {
                
                if(html){
                    if(dayValue=='today'){
                        $(".doc-wise-day-label").html('| Today');
                    }else if(dayValue=='thismonth'){
                        $(".doc-wise-day-label").html('| This Month');
                    }else if(dayValue=='thisyear'){
                        $(".doc-wise-day-label").html('| This Year');
                    }
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
                          name: 'Doctor wise appointment',
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
                          data: html.doc_appt
                        }]
                      });
                    
                }
                $(".overlay").hide();
            }
        });
    });

    $(document).on("click", ".recent-appt" , function(e) { 
        $(".overlay").show();
        var dayValue = $(this).attr('data-value')
        $.ajax({
            url: baseUrl + '/admin/get-dashboard-booking-data',
            type: 'post',
            data: {'period':$(this).attr('data-value'),'card':'recent-appt'},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( html ) {
                if(html){
                    if(dayValue=='today'){
                        $(".recent-appt-day-label").html('| Today');
                    }else if(dayValue=='thismonth'){
                        $(".recent-appt-day-label").html('| This Month');
                    }else if(dayValue=='thisyear'){
                        $(".recent-appt-day-label").html('| This Year');
                    }
                    var str = '';
                    $.each(html.list, function (key, val) {
                        str += '<tr>';
                            str +='<td scope="row">'+val.appointment_id+'</td>';
                            str +='<td>'+val.patient_name+'</td>';
                            str +='<td>'+val.book_date+'</td>';
                            str +='<td>'+val.book_time+'</td>';
                            str +='<td><span class="badge bg-success">Booked</span></td>';
                        str +='</tr>';
                    });
                    $('#recent-appt').DataTable().destroy();
                    $("#recent-appt tbody").html(str);
                    $("#recent-appt").dataTable();
                }
                $(".overlay").hide();
            }
        });
    });
});