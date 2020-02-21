var url = $("#base_url").val();

function getPaymentMethod(payment){
    let html = '';
    switch (payment) {
        case 'cash':
            html = '<span class="label label-success label-rounded" style=" FONT-SIZE: 12px; padding: 6px 10px; letter-spacing: 2px; ">CASH</span>';
            break;
        case 'gcash':
            html = '<span class="label label-info label-rounded" style=" FONT-SIZE: 12px; padding: 6px 10px; letter-spacing: 2px; ">GCASH</span>';
            break;
        case 'card':
            html = '<span class="label label-warning label-rounded" style=" FONT-SIZE: 12px; padding: 6px 10px; letter-spacing: 2px; ">CREDIT / DEBIT</span>';
            break;
    }

    return html;
}

function dataTableReports(dates_to ='', dates_from='', location=0) {
    // alert(dates_to + " " + dates_from);
    $('.page-titles ').append("");
    $.ajax({
        async: false,
        url: url + "reports/getTotalSales",
        type: 'post',
        dataType: 'json',
        data: {dates_to: dates_to, dates_from: dates_from,loc:location},
        success: function(data) {
            $('.total_sales').html("&#8369; "+ data.toLocaleString());
        }
    });

    $('#salesOrderTbl').DataTable({
            "destroy"       : true,
           "processing"     : true,
           "serverSide"     : true,
           "order"          : [[0,'desc']],
           "columns"        :[
                 {"data":"display_id"},
                 {"data":"issued_by","render": function( data, type,row) {
                     return row.fname + " " + row.lname;
                 }},
                 {"data":"loc"},
                 {"data":"total_items","render": function( data, type,row) {
                     return parseInt(row.total_items).toLocaleString() + " Items";
                 }},
                 {"data":"total_amount","render":function( data, type,row) {
                     return "&#8369; " + parseInt(row.total_amount).toLocaleString();
                 }},
                 {"data":"payment_method", "render": function(data){
                     return getPaymentMethod(data);
                 }},
                 {"data":"date_issued"},
                 {"data":"date_issued", "render": function(data, type, row) {
                     var str= '';
                          str += '<a class="viewSales" href="" data-toggle="modal" data-target="#viewSales" data-id="'+row.sales_id+'"><i class="fas fa-eye"></i></a>';
                     return str;
                 }},
           ],
           "ajax": {
                 "url"   : url+"reports/reportDatatable",
                 "type"  : "POST",
                 "data"  : {dates_to: dates_to, dates_from: dates_from,loc:location}
           },

           "columnDefs": [
                 {
                      "targets"   : [7],
                      "orderable" : false,
                  },
             ],
    });
}

function toDate(date) {
    $('.toDate').datepicker({
        language: 'en',
        minDate: new Date(date),
        onSelect: function onSelect(fd, date) {
            var dated = new Date(date);
            var mnth = ("0" + (dated.getMonth() + 1)).slice(-2);
            var day = ("0" + dated.getDate()).slice(-2);
            var whole = [mnth, day, dated.getFullYear()].join("/");
            dataTableReports($('.fromDate').val(),whole,$('select[name="loc"]').val());
        }
    });
}

$(document).ready(function() {
    dataTableReports();

    $('.fromDate').datepicker({
        language: 'en',
        onSelect: function onSelect(fd, date) {
            var dated = new Date(date);
            var mnth = ("0" + (dated.getMonth() + 1)).slice(-2);
            var day =  ("0" + dated.getDate()).slice(-2);
            var whole = [dated.getFullYear(), mnth, day].join("/");
            toDate(whole);
            dataTableReports(whole, $('.toDate').val(),$('select[name="loc"]').val());
        }
    });

    $('select[name="loc"]').on('change', function() {
        dataTableReports($('.fromDate').val(), $('.toDate').val(),$(this).val());
    });

    $(document).on('click','.viewSales', function(e){
        e.preventDefault();
        var self = $(this);
        var id = $(this).data('id');
        $.ajax({
            async: false,
            url: url + "reports/getSalesItems",
            type: 'post',
            dataType: 'json',
            data: {id: id},
            success: function(data) {
                var tot_amt = "&#8369; " + parseInt(data.total_amount).toLocaleString();
                $('.sales_id').html("#" + data.sales);
                $('.viewSalesTb tbody').html(data.string);
                $('.unit').html(data.total_items + " items");
                $('.remark_p').html(data.remark);
                $('.amt').html(tot_amt);
            }
        })
    });

});
