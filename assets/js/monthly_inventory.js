$(document).ready(function() {
    var url = $("#base_url").val();

    $.ajax({
        async: false,
        url: url + "monthly_inventory/tables",
        type:'post',
        dataType:'json',
        data: {id: $("#salesViewRecord").data('id')},
        success: function(data) {
            generateTable(data.display_item, "#all_rec_items");
            generateTable(data.display_pur, "#all_purchase");
            generateTable(data.display_pull, "#all_pull_items");
            generateTableInput(data.display_summary, "#inventory_summary");
            generateTableInput(data.display_goods, "#all_goods");

            var floatA =  parseFloat(data.variance_items);
            (floatA < 0) ? $(".discrepancy_item").addClass("text_red") : "";
            var floatB = parseFloat(data.variance_amt.substring(9, data.variance_amt.length));
            (floatB < 0) ? $(".discrepancy_amt").addClass("text_red") : "";
            var floatC = parseFloat(data.profit.substring(9, data.profit.length));
            (floatC < 0) ? $(".profit").addClass("text_red") : "";

            $('#all_rec_items > tbody > tr').each(function(index, value) {
                // $('td:eq(2)', this)
                (parseFloat($('td:eq(4)').html()) < 0) ? $('td:eq(4)').addClass("text_red") : "";
                (parseFloat($('td:eq(7)').html()) < 0) ? $('td:eq(7)').addClass("text_red") : "";
            });

            $(".month").html(data.month);
            $(".coverage").html(data.coverage);
            $(".record_by").html(data.recorded);
            $(".physical_count").html(data.physical_count);
            $(".system_count").html(data.system_count);
            $(".discrepancy_amt").html(data.variance_amt);
            $(".item_count").html(data.system_count_items);
            $(".item_phy_count").html(data.physical_count_items);
            $(".discrepancy_item").html(data.variance_items);
            $(".total_sales").html(data.total_sales);
            $(".total_purchase").html(data.total_purchase);
            $(".profit").html(data.profit);
        }
    });

    $.ajax({
        async: false,
        url: url + "monthly_inventory/recordContent",
        type:'post',
        dataType:'json',
        data: {
            date_from: $('input[name="date_from"]').val(),
            date_to: $('input[name="date_to"]').val(),
        },
        success: function(data) {
          generateTableInput(data.table,"#recordSales");
      }
    });

    $.ajax({
        async: false,
        url: url + "monthly_inventory/daysLeft",
        type:'post',
        dataType:'json',
        data: {},
        success: function(data) {
            var temp = new Date();
            var cur_date = temp.getFullYear() + "-" + (temp.getMonth() + 1) + "-" + ('0' +temp.getDate()).slice(-2);

            if (data.istoday_record == "success") {
                $("button[data-target='#recMonthly']").hide();
                $('.page-titles .col-md-7').append("<div class='alert alert-success alert-rounded font-weight-bold' style='width:40%; float:right; text-align:center'><i class='fas fa-check'></i>Successfully Recorded</div>");
            }else if (data.daysleft != 0) {
                $("button[data-target='#recMonthly']").hide();
                $('.page-titles .col-md-7').append("<div class='alert alert-success alert-rounded font-weight-bold' style='width:40%; float:right; text-align:center'><i class='fas fa-info-circle'></i> " + data.daysleft + " day(s) left</div>");
                // $('.page-titles .col-md-7').append("<div class='alert alert-success alert-rounded font-weight-bold' style='width:40%; float:right; text-align:center'><i class='fas fa-info-circle'></i> " + data.daysleft + " day(s) left</div><button type='button' style='margin-right: 20px' class='m-l-10 btn btn-info' data-toggle='modal' data-target='#recMonthly'><i class='fa fa-plus-circle'></i> Test Record</button>");
            }else {
                $("button[data-target='#recMonthly']").show();
            }
        }
    });


    $('.print').on('click', function() {
        var title = $('.tab-pane h6').html() + ' (' + $('.coverage').html() + ')';
        $('.responsive-table').removeClass('responsive-table');
        $('#salesViewRecord .card-body div').first().hide();
        $('.ableprint').hide();
        $('.tab-pane h6').html(title);

        setTimeout(function () {
            $('.print').hide();
            $('.print_to').print({
            	globalStyles: true,
            	mediaPrint: false,
            	stylesheet: null,
            	noPrintSelector: ".no-print",
            	iframe: false,
            	append: null,
            	prepend: null,
            	manuallyCopyFormValues: true,
            	deferred: $.Deferred(),
            	timeout: 750,
            	title: null,
            	doctype: '<!doctype html>'
    	    });
        }, 1000);


        setTimeout(function () {
            window.location.reload();
        }, 1900);
    });

    $('.print-all').on('click', function() {
        var id = $(this).data('trig');
        $('#list_of_items').show();
        $('#statistics').show();
        $('#pullouts').show();
        $('#purchase').show();
        $('#summary').show();
        $('.responsive-table').removeClass('responsive-table');

        setTimeout(function () {
            $('.print-all').hide();
            $('.ableprint').hide();
            $('.print_to').print({
            	globalStyles: true,
            	mediaPrint: false,
            	stylesheet: null,
            	noPrintSelector: ".no-print",
            	iframe: false,
            	append: null,
            	prepend: null,
            	manuallyCopyFormValues: true,
            	deferred: $.Deferred(),
            	timeout: 750,
            	title: null,
            	doctype: '<!doctype html>'
    	    });
        }, 1000);


        setTimeout(function () {
            window.location.reload();
        }, 1900);
    });

    $("button[data-target='#recMonthly']").click(function() {
        window.location.href = url + "monthly_inventory/recordView";
    });

    $(document).on('submit', '#form_rec_mo',function(e) {
        e.preventDefault();

        var self = $(this);
        Swal.fire({
            title: "Warning!",
            text: "Are you sure save this record?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#A4A23C",
            confirmButtonText: "Yes",
        }).then((confirm) => {
            if (confirm.value) {
                $.ajax({
                    async: false,
                    url: url + "monthly_inventory/recordInventory",
                    type:'post',
                    dataType:'json',
                    data: self.serialize(),
                    success: function(data) {
                        setTimeout(showNotification(data), 10000);
                        window.location = url + 'monthly_inventory';
                    }
                });
            }
        });
    });


    $(document).on('change', '.count_qty', function() {
        var total_amount = $(this).val() * $(this).data("price");
        var variance_item = $(this).val() - $(this).data("end_item");
        var variance_amount = total_amount - parseInt($(this).data("end_amt"));
        $(this).parents('tr').find('.var_item').val(variance_item);
        $(this).parents('tr').find('.physical_bal').val(total_amount);
        $(this).parents('tr').find('.variance_amt').val(variance_amount);
        $(this).parents('tr').find('.physical').html("&#x20B1;" + " " + total_amount );
        $(this).parents('tr').find('.variance_item').html(variance_item);
        $(this).parents('tr').find('.variance_amt').html("&#x20B1;" + " " + variance_amount );
    });



    var inventory = $('#monthlyInventory').DataTable({
           "processing"     : true,
           "serverSide"     : true,
           "order"          : [[0,'desc']],
           "columns"        :[
                 {"data":"date_from", "render": function( data, type,row) {
                     return row.date_from + " To " + row.date_to;
                 }},
                 {"data":"month_of_record"},
                 {"data":"recorded_by","render": function( data, type,row) {
                     return row.fname + " " + row.lname;
                 }},
                 {"data":"end_items","render": function( data, type,row) {
                     return parseInt(row.end_items).toLocaleString() + " Items";
                 }},
                 {"data":"phy_items","render": function( data, type,row) {
                     return parseInt(row.phy_items).toLocaleString() + " Items";
                 }},
                 {"data":"variance_item","render": function( data, type,row) {
                     return parseInt(row.variance_item).toLocaleString() + " Items";
                 }},
                 {"data":"variance_amount", "render": function(data,type,row) {
                     var str= '';
                          str +='<a href="'+url+'monthly_inventory/viewRecord/'+row.monthly_id+'"  class="viewRecords btn" style="background: transparent"><i class="fas fa-eye"></i></a>';
                     return str;
                 }},
           ],

           "ajax": {
                 "url"   : url+"monthly_inventory/recordDatatable",
                 "type"  : "POST"
           },

           "columnDefs": [
                 {
                      "targets"   : [5],
                      "orderable" : false,
                  },
             ],
    });

    $(".count_qty_mon").on("keyup change", function () {
        var ending_balance_items = $(this).parents("tr").find('.ending_balance').val();
        var ending_amt = $(this).parents("tr").find('.ending_amt').val();
        var price = $(this).parents("tr").find('.price').val();

        var variance_items =  $(this).val()- ending_balance_items;
        var phy_balance_amt = $(this).val() * price;
        var variance_amt = phy_balance_amt - ending_amt;

        if (variance_items < 0) {
            $(this).parents("tr").addClass("table_warning_error");
            $(this).parents("tr").find('.variance').addClass("text_red");
            $(this).parents("tr").find('.variance_amt').addClass("text_red");
        }else {
            $(this).parents("tr").removeClass("table_warning_error");
            $(this).parents("tr").find('.variance').removeClass("text_red");
            $(this).parents("tr").find('.variance_amt').removeClass("text_red");
        }

        if (variance_items > 0) {
            $(this).parents("tr").addClass("table_warning_success");
            $(this).parents("tr").find('.variance').addClass("text_good");
            $(this).parents("tr").find('.variance_amt').addClass("text_good");
        }else {
            $(this).parents("tr").removeClass("table_warning_success");
            $(this).parents("tr").find('.variance').removeClass("text_good");
            $(this).parents("tr").find('.variance_amt').removeClass("text_good");
        }
        $(this).parents("tr").find('.var_item').val(variance_items);
        $(this).parents("tr").find('.physical_bal').val(phy_balance_amt);
        $(this).parents("tr").find('.variance_amount_total').val(variance_amt);

        $(this).parents("tr").find('.variance').html(variance_items);
        $(this).parents("tr").find('.end_bal_res').html("&#x20B1;" + phy_balance_amt);
        $(this).parents("tr").find('.variance_amt').html("&#x20B1;" + variance_amt);
    });

    $(".count_qty_mon").each(function () {
        var ending_balance_items = $(this).parents("tr").find('.ending_balance').val();
        var ending_amt = $(this).parents("tr").find('.ending_amt').val();
        var price = $(this).parents("tr").find('.price').val();
        var variance_items =  $(this).val()-ending_balance_items;
        var phy_balance_amt = $(this).val() * price;
        var variance_amt = phy_balance_amt - ending_amt;

        if (variance_items < 0) {
            $(this).parents("tr").addClass("table_warning_error");
            $(this).parents("tr").find('.variance').addClass("text_red");
            $(this).parents("tr").find('.variance_amt').addClass("text_red");
        }else {
            $(this).parents("tr").removeClass("table_warning_error");
            $(this).parents("tr").find('.variance').removeClass("text_red");
            $(this).parents("tr").find('.variance_amt').removeClass("text_red");
        }

        if (variance_items > 0) {
            $(this).parents("tr").addClass("table_warning_success");
            $(this).parents("tr").find('.variance').addClass("text_good");
            $(this).parents("tr").find('.variance_amt').addClass("text_good");
        }else {
            $(this).parents("tr").removeClass("table_warning_success");
            $(this).parents("tr").find('.variance').removeClass("text_good");
            $(this).parents("tr").find('.variance_amt').removeClass("text_good");
        }
        $(this).parents("tr").find('.var_item').val(variance_items);
        $(this).parents("tr").find('.physical_bal').val(phy_balance_amt);
        $(this).parents("tr").find('.variance_amount_total').val(variance_amt);

        $(this).parents("tr").find('.variance').html(variance_items);
        $(this).parents("tr").find('.end_bal_res').html("&#x20B1;" + phy_balance_amt);
        $(this).parents("tr").find('.variance_amt').html("&#x20B1;" + variance_amt);
    });

});
