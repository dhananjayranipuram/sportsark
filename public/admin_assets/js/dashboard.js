$(document).ready(function () { 
    $(document).on("click", ".booking-count", function(e) { 
        $(".overlay").show();
        var dayValue = $(this).attr('data-value');
        
        $.ajax({
            url: baseUrl + '/admin/get-dashboard-booking-data',
            type: 'post',
            data: {
                'period': dayValue,
                'card': 'booking-count'
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(html) {
                if (html) {
                    $("#bookingCount").html(html.booking.today_cnt);
                    $(".booking-count-per").html(html.booking.increase + '%');
    
                    const dayLabels = {
                        today: '| Today',
                        thismonth: '| This Month',
                        thisyear: '| This Year'
                    };
                    $(".booking-day-label").html(dayLabels[dayValue] || '');
    
                    const isIncrease = html.booking.increase >= 0;
                    $(".booking-count-per")
                        .removeClass('text-success text-danger')
                        .addClass(isIncrease ? 'text-success' : 'text-danger');
    
                    $(".booking-count-trend").html(isIncrease ? 'Increase' : 'Decrease');
                }
                $(".overlay").hide();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching booking data:", error);
                $(".overlay").hide();
            }
        });
    });    

    $(document).on("click", ".customer-count", function(e) { 
        $(".overlay").show();
        var dayValue = $(this).attr('data-value');
    
        $.ajax({
            url: baseUrl + '/admin/get-dashboard-booking-data',
            type: 'post',
            data: {
                'period': dayValue,
                'card': 'customer-count'
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(html) {
                if (html) {
                    $("#customerCount").html(html.customer.today_cnt);
                    $(".customer-count-per").html(html.customer.increase + '%');
    
                    const dayLabels = {
                        today: '| Today',
                        thismonth: '| This Month',
                        thisyear: '| This Year'
                    };
                    $(".customer-day-label").html(dayLabels[dayValue] || '');
    
                    const isIncrease = html.customer.increase >= 0;
                    $(".customer-count-per")
                        .removeClass('text-success text-danger')
                        .addClass(isIncrease ? 'text-success' : 'text-danger');
                    
                    $(".customer-count-trend").html(isIncrease ? 'Increase' : 'Decrease');
                }
                $(".overlay").hide();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching customer count data:", error);
                $(".overlay").hide();
            }
        });
    });
    

    $(document).on("click", ".doc-wise-appt", function(e) {
        $(".overlay").show();
        var dayValue = $(this).attr("data-value");
        
        $.ajax({
            url: baseUrl + "/admin/get-dashboard-booking-data",
            type: "post",
            data: { 'period': dayValue, 'card': 'pie-chart' },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(html) {
                if (html && html.doc_appt) {
                    const dayLabels = {
                        today: '| Today',
                        thismonth: '| This Month',
                        thisyear: '| This Year'
                    };
    
                    $(".doc-wise-day-label").html(dayLabels[dayValue] || "");

                    if (Array.isArray(html.doc_appt) && html.doc_appt.length > 0) {

                        echarts.init(document.querySelector("#trafficChart")).setOption({
                            tooltip: {
                                trigger: 'item'
                            },
                            legend: {
                                top: '1%',
                                left: 'left',
                                type: 'scroll'
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
                    } else {
                        console.error("Invalid data format for 'doc_appt'", html.doc_appt);
                    }
                } else {
                    console.error("Invalid response format:", html);
                }
                $(".overlay").hide();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                alert("Something went wrong. Please try again.");
                $(".overlay").hide();
            }
        });
    });
    

    $(document).on("click", ".recent-appt", function (e) {
        $(".overlay").show();
    
        var dayValue = $(this).attr("data-value");
    
        $.ajax({
            url: baseUrl + "/admin/get-dashboard-booking-data",
            type: "POST",
            data: { period: dayValue, card: "recent-appt" },
            headers: { "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content") },
            success: function (html) {
                if (html && html.list) {
                    var labelMap = {
                        today: "| Today",
                        thismonth: "| This Month",
                        thisyear: "| This Year",
                    };
                    $(".recent-appt-day-label").html(labelMap[dayValue] || "");
    
                    var str = "";
                    if (html.list.length > 0) {
                        $.each(html.list, function (key, val) {
                            str += "<tr>";
                            str += "<td scope='row'>" + val.booking_id + "</td>";
                            str += "<td>" + val.customer_name + "</td>";
                            str += "<td>" + val.book_date + "</td>";
                            str += "<td>" + val.book_time + "</td>";
                            str += "<td><span class='badge bg-success'>Booked</span></td>";
                            str += "</tr>";
                        });
                    } else {
                        str += "<tr><td colspan='5' class='text-center'>No Bookings Found</td></tr>";
                    }
    
                    var table = $("#recent-appt").DataTable();
                    table.clear().destroy();
                    $("#recent-appt tbody").html(str);
                    $("#recent-appt").DataTable();
                } else {
                    console.error("Invalid response format:", html);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                alert("Something went wrong. Please try again.");
            },
            complete: function () {
                $(".overlay").hide();
            },
        });
    });
    
});