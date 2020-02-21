const BASE_URL = $('#base_url').val();

function top_selling_products(){
    $.ajax({
        url: BASE_URL + 'dashboard/top_selling_chart',
        type: 'POST',
        dataType: 'json',
        success:function(response){
            if (response.length) {
                Morris.Bar({
                    element: 'morris-bar-chart',
                    data: response,
                    xkey: 'y',
                    ykeys: ['a', 'b', 'c'],
                    labels: ['', 'Sold', ''],
                    barColors:['#55ce63'],
                    hideHover: 'auto',
                    gridLineColor: '#bbb',
                    resize: true
                });
            }else {
                $('#morris-bar-chart').html('No sales this Month');
            }
        }
    });
}

// Listeners
$('select[name="timeframe"], select[name="location"]').on('change',function(){
    render_admin_dashboard();
});

function render_admin_dashboard(){
    const postData = {
        location: $('select[name="location"]').val(),
        timeframe: $('select[name="timeframe"]').val(),
    };

    sales_sold(postData);
    items_sold(postData);
    sales_chart(postData);
}

function sales_chart(postData) {
    $.ajax({
        url: BASE_URL + 'dashboard/get_sales_chart',
        data: postData,
        dataType:'json',
        type:'POST',
        success: function(response){
            var myChart = echarts.init(document.getElementById('bar-chart'));

            // specify chart configuration item and data
            option = {
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data: response.legend
                },
                toolbox: {
                    show : true,
                    feature : {
                        magicType : {show: true, type: ['line', 'bar']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                color: ["#55ce63", "#009efb", "#FF7D47", "#5587E7", 'red'],
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : response.xAxis
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                    }
                ],
                series : response.series,
            };


            // use configuration item and data specified to show chart
            myChart.setOption(option, true), $(function() {
                function resize() {
                    setTimeout(function() {
                        myChart.resize()
                    }, 100)
                }
                $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
            })
        }
    });
}

function sales_sold(postData){
    $.ajax({
        url: BASE_URL + 'dashboard/ajax_dashboard',
        data: postData,
        dataType:'json',
        type:'POST',
        success: function(res){
            $('.sales').html(res.sales.total);
            $('.sales_difference').html(res.sales.difference);
            $('.sales_difference').removeClass('text-info text-danger');
            $('.sales_difference').addClass(res.sales.class);
            $('.target_sales').html(res.target_sales);
            $('.target_sales_value').val(res.target_sales_value);

            $('.sold').html(res.sold.total);
            $('.sold_difference').html(res.sold.difference);
            $('.sold_difference').removeClass('text-info text-danger');
            $('.sold_difference').addClass(res.sold.class);
            $('.display_time_frame').html(res.display_time_frame);
            $('.display_time_frame_value').val(res.display_time_frame);

            $('#top-sellers tbody').html(res.top_sellers);
        }
    })
}

function items_sold(postData){
    $.ajax({
        url: BASE_URL + 'dashboard/get_items_sold/' + $('input[name="timeframe"]').val(),
        data: postData,
        dataType:'json',
        type:'POST',
        success: function(res){
            new Chart(document.getElementById("items-sold"), {
            	"type": "line",
                "data": res
            });
        }
    });
}

function toggleEditTarget(){
    $('.edit-target, .target-input, .target_sales').toggle();
}

$(document).ready(function(){
    render_admin_dashboard();
    top_selling_products();
});

// LISTENERS
$('.edit-target').on('click', function(){
    toggleEditTarget();
})

$('#editTargetForm').on('submit', function(e){
    e.preventDefault();

    $.ajax({
        url: BASE_URL + 'dashboard/updateSalesTarget/' + $('select[name="timeframe"]').val(),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        success : function(res){
            showNotification(res);
            toggleEditTarget();
            render_admin_dashboard();
        }
    })
})


$('.dashboard-btn').on('click',function(){
    const link = BASE_URL + $(this).attr('data-link');

    window.location.href = link;
})
