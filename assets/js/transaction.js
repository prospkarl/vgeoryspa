$(document).ready(function() {
    var url = $("#base_url").val();
    chart('',$('select[name="location"]').val());
    chartSales();
    chartAvg();
    $('.yearSel').datepicker({
        language: 'en',
        view: 'years',
        minView: 'years',
        dateFormat: 'yyyy',
        onSelect: function onSelect(fd, date) {
            var dated = new Date(date);
            $(".preloader").fadeIn();
            $.ajax({
                async: false,
                url: url + "transaction/central_main",
                dataType: 'json',
                type: "POST",
                data: {date: dated.getFullYear(), loc: $('select[name="location"]').val()},
                success: function(data) {
                    $(".preloader").fadeOut()
                    $("#transactiontbl tbody").html(data.string);
                    $(".debitcard tbody").html(data.card);
                    $(".gcash tbody").html(data.gcash);
                    $(".cash tbody").html(data.cash);
                    $(".cheque tbody").html(data.cheque);
                    chart(dated.getFullYear(), $('select[name="location"]').val());
                    chartSales(dated.getFullYear());
                    chartAvg(dated.getFullYear());

                }
            });

        }
    });



    $('select[name="location"]').on('change', function() {
        var self = $(this);
        document.body.style.cursor = "wait";
        $.ajax({
            async: false,
            url: url + "transaction/central_main",
            dataType: 'json',
            type: "POST",
            data: {date: $(".yearSel").val(), loc: $(this).val()},
            success: function(data) {
                document.body.style.cursor = "pointer";
                $(".preloader").fadeOut();
                $("#transactiontbl tbody").html(data.string);
                $(".debitcard tbody").html(data.card);
                $(".gcash tbody").html(data.gcash);
                $(".cash tbody").html(data.cash);
                $(".cheque tbody").html(data.cheque);
                chart($('.yearSel').val(), self.val());
            }
        });
    })

    function chart(year, loc) {
        var data_db = [];
        $.ajax({
            async: false,
            url: url + "transaction/linegraphData",
            dataType: 'json',
            type: "POST",
            data: {data: year, loc: loc},
            success: function(data) {
                var colors = ['#2fb7b7', '#fe6f7c', '#70c8f0', '#f75717'];
                var tri = {
                    "label":"All",
                    "data":data.all['data'],
                    "fill":false,
                    "borderColor":'#2fb7b7',
                    "lineTension":0.1
                    };
                data_db.push(tri);
            }
        });
        new Chart(document.getElementById("morris-line-chart"),
            {
                "type":"line",
                "data":{"labels":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                "datasets":data_db
            },"options":{}});
    }

    function chartSales(year) {
        var data_db = [];
        $.ajax({
            async: false,
            url: url + "transaction/lineLocation",
            dataType: 'json',
            type: "POST",
            data: {data: year},
            success: function(data) {
                var colors = ['#fe6f7c', '#70c8f0', '#f75717'];
                var i = 0;
                $.each(data, function (key) {
                    var tri = {
                            "label":data[key]['method'],
                            "data":data[key]['data'],
                            "fill":false,
                            "borderColor":colors[i],
                            "lineTension":0.1
                        };
                    data_db.push(tri);
                    i++;
                })
            }
        });
        new Chart(document.getElementById("sales_chart"),
            {
                "type":"line",
                "data":{"labels":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                "datasets":data_db
            },"options":{}});
    }


    function chartAvg(year) {
        var data_db = [];
        $.ajax({
            async: false,
            url: url + "transaction/avgCheck",
            dataType: 'json',
            type: "POST",
            data: {data: year},
            success: function(data) {
                console.log(data);
                var tri = {
                        "label":"All",
                        "data":data.all['data'],
                        "fill":false,
                        "borderColor":'#fe6f7c',
                        "lineTension":0.1
                };
                data_db.push(tri);
            }
        });
        new Chart(document.getElementById("avg_check"),
            {
                "type":"line",
                "data":{"labels":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                "datasets":data_db
            },"options":{}});
    }
});
