$(document).ready(function() {
    var url = $("#base_url").val();

    stocktransfer(url, 'all');

    $("button[data-target='#transferMod']").click(function() {
        $('.stockTB tbody tr').not(':last').remove();
    });

    $(document).on('click','.viewTransfer', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            async:false,
            url: url + "stock_transfer/view_transfer",
            type: "post",
            data: {id: id},
            dataType:"json",
            success: function(data) {
                $('.from').html(data.from);
                $('.to').html(data.to);
                $('.dater').html(data.date);
                generateTable(data.table,".stockTbl");
            }
        });
    });

    $(document).on('click','.recTransfer', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            async:false,
            url: url + "stock_transfer/receivedView",
            type: "post",
            data: {id: id},
            dataType:"json",
            success: function(data) {
                $('.from').html(data.from);
                $('.to').html(data.to);
                $('.dater').html(data.date);
                generateTable(data.table, ".recTbl");
            }
        });
    });

    $('#transferForm').on('submit',function(e){
        e.preventDefault();
        if ($('select[name="loc_from"]').val() == $('select[name="loc_to"]').val()) {
            var not_dat = {
                message: "You cannot transfer items on the same location",
                type: 'warning'
            };
            showNotification(not_dat);
        }else {
            if ($('select[name="loc_from"]').val() == 1) {
                $.ajax({
                    async:false,
                    url: url + "stock_transfer/transferItems",
                    type: "post",
                    data: $(this).serialize() + "&type=0",
                    dataType:"json",
                    success: function(data) {
                        if (data.type == "success") {
                            var url_string = window.location.href;
                            var url_base = url_string.split('/');
                            if (url_base[3] == "stock_transfer") {
                                stocktransfer(url, 'all');
                            }else {
                                deliveryData(url);
                            }
                            $('#transferMod').modal('hide');
                        }
                        showNotification(data);
                    },
                })
            }else {
                $.ajax({
                    async:false,
                    url: url + "stock_transfer/transferItems",
                    type: "post",
                    data: $(this).serialize() + "&type=1",
                    dataType:"json",
                    success: function(data) {
                        if (data.type == "success") {
                            stocktransfer(url, 'all');
                            $('#transferMod').modal('hide');
                        }
                        showNotification(data);
                    },
                })
            }
        }
    });

    $(document).on('click','.ST_addmore', function(e) {
        e.preventDefault();
        var str = "";
        str += "<tr>";
        str += "<td>";
        str +="<div class='autocomplete_drp'>";
              str +="<input required class='autocomplete form-control' type='text' placeholder='Type Barcode, SKU, Name'>";
              str +="<input class='autocomplete_holder' type='hidden' name='items[]'>";
              str +="<div class='autocomplete_drp-content'></div>";
        str +="</div>";
        str += "</td>";
        str += "<td><input required min='0' class='form-control qty_num' value='0' type='number' name='quantity[]'/></td>";
        str += "<td class='warehouse_stock'>0</td>";
        str += "<td class='afterWare'>0</td>";
        str += "<td class='dest_stock'>0</td>";
        str += "<td class='afterDest'>0</td>";
        str += "<td><a class='delBut text-info'><i class='fas fa-trash'></i></a></td>";
        str += "</tr>";
        $(this).parents('tr').before(str);
    });



    $(document).on('keyup change', '.qty_num',function() {
        var qty = $(this).val();
        var current_stocks = $(this).parents('tr').find(".warehouse_stock").data('stock');

        var afterWare = parseInt(current_stocks) - parseInt(qty);

        if (afterWare < 0) {
            $(this).val(current_stocks);
            $(this).trigger('keyup');
            return false;
        }

        $(this).parents('tr').find('.afterWare').html(afterWare);

        $(this).parents('tr').find('.afterDest').html(parseInt($(this).parents('tr').find(".dest_stock").data('stock')) + parseInt(qty));
    });

    $('select[name="loc_from"]').on('change',function(){
        var str ='<tr style="text-align:center"><td colspan="7"><b><a class="text-info ST_addmore" name="button"><i class=" fas fa-plus"></i> Add More</a></b></td></tr>'
        $('.stockTB tbody').html(str);
    });

    $('select[name="loc_to"]').on('change',function(){
        var str ='<tr style="text-align:center"><td colspan="7"><b><a class="text-info ST_addmore" name="button"><i class=" fas fa-plus"></i> Add More</a></b></td></tr>'
        $('.stockTB tbody').html(str);

    });

    $('.status').on('change', function() {
        stocktransfer(url,$(this).val());
    });

    $(document).on('click', '.lister', function() {
        const self = this;
        $.ajax({
            url: url + 'stock_transfer/getSelItem/' + $(this).data('id'),
            type: 'POST',
            data: {
                location_from: $('select[name="loc_from"]').val(),
                location_to: $('select[name="loc_to"]').val()
            },
            dataType:'json',
            success:function(res){
                if (res.current_qty == 0) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops!',
                        text: 'This item is out of stock on this location',
                    })
                    $(self).parents('tr').find('.autocomplete').val('');
                    $(self).parents('tr').find('.quantitybefore').html('0');
                    $(self).parents('tr').find('.request_qty').val('0');
                    $(self).parents('tr').find('.quantityafter').html('0');
                    return false;
                }
                $(self).parents('tr').find('.autocomplete ').val(res.product_name);
                $(self).parents('tr').find('.autocomplete_holder').val($(self).data('id'));
                $(self).parents('tr').find(".warehouse_stock").attr('data-stock', res.current_qty);
                $(self).parents('tr').find(".warehouse_stock").html(res.current_qty)
                $(self).parents('tr').find(".afterWare").attr('data-stock', res.current_qty);
                $(self).parents('tr').find(".afterWare").html(res.current_qty);
                $(self).parents('tr').find(".afterDest").attr('data-stock', res.dest_qty);
                $(self).parents('tr').find(".afterDest").html(res.dest_qty);
                $(self).parents('tr').find(".dest_stock").attr('data-stock', res.dest_qty);
                $(self).parents('tr').find(".dest_stock").html(res.dest_qty);
            }
        })
    });
});

function stocktransfer(url, stat) {
    $('#stockTransfer').DataTable({
            "destroy"        : true,
           "responsive"     : true,
           "processing"     : true,
           "serverSide"     : true,
           "order"          : [[0,'desc']],
           "columns"        :[
                 {"data":"transfer_id"},
                 {"data":"transfer_by","render" : function(data,type,row){
                      return row.fname + " " + row.lname;
                 }},
                 {"data":"loc_from"},
                 {"data":"loc_to"},
                 {"data":"date_added"},
                 {"data":"status","render": function(data, type, row) {
                     var str = '';
                     (row.status == 0) ? str = '<span class="label label-info label-rounded">For Delivery</span>' : (row.status == 1) ? str = '<span class="label label-success label-rounded">Received</span>' :
                     "";
                     return str;
                 }},
                 {"data":"transfer_id","render": function(data, type, row) {
                     var str= '';
                     if (row.status != 1) {
                          str += '<a class="viewTransfer" href="" data-toggle="modal" data-target="#viewTransfer" data-id="'+row.transfer_id+'"><i class="fas fa-eye" data-toggle="tooltip" title="View"></i></a>';
                     }else {
                          str += '<a class="recTransfer" href="" data-toggle="modal" data-target="#receivedView" data-id="'+row.transfer_id+'"><i class="fas fa-eye" data-toggle="tooltip" title="View"></i></a>';
                     }

                     return str;
                 }},
           ],

           "ajax": {
                 "url"   : url+"stock_transfer/transferDatatable",
                 "data"  : {status: stat},
                 "type"  : "POST"
           },

           "columnDefs": [
                 {
                      "targets"   : [5, 6],
                      "orderable" : false,
                  },
             ],

             "initComplete" : function () {
                 $('[data-toggle="tooltip"]').tooltip()
             }
    });
}

function deliveryData(url) {
    $.ajax({
      url   : url+"delivery/getTotalCost",
      type  : 'POST',
      data: {
          status: $('select[name="status"]').val(),
          fromDate: $('input[name="from_date"]').val(),
          toDate: $('input[name="to_date"]').val()
      },
      success: function(res){
          $('.total_cost').html(res);
      }
    })


    $('#delivertbl').DataTable({
           "destroy"        : true,
           "responsive"     : true,
           "processing"     : true,
           "serverSide"     : true,
           "order"          : [[0,'desc']],
           "columns"        :[
                 {"data":"transfer_id"},
                 {"data":"transfer_by","render" : function(data,type,row){
                      return row.fname + " " + row.lname;
                 }},
                 {"data":"loc_from"},
                 {"data":"loc_to"},
                 {"data":"date_added"},
                 {"data":"total_amount", "render" : function(data){
                     return numberWithCommas(data);
                 }},
                 {"data":"status","render": function(data, type, row) {
                     var str = '';
                     (row.status == 0) ? str = '<span class="label label-info label-rounded">For Delivery</span>' : (row.status == 1) ? str = '<span class="label label-success label-rounded">Received</span>' :
                     "";
                     return str;
                 }},
                 {"data":"transfer_id","render": function(data, type, row) {
                     var str= '';
                     if (row.status != 1) {
                          str += '<a class="viewTransfer" href="" data-toggle="modal" data-target="#viewTransfer" data-id="'+row.transfer_id+'"><i class="fas fa-eye" data-toggle="tooltip" title="View"></i></a>';
                     }else {
                          str += '<a class="recTransfer" href="" data-toggle="modal" data-target="#receivedView" data-id="'+row.transfer_id+'"><i class="fas fa-eye" data-toggle="tooltip" title="View"></i></a>';
                     }

                     return str;
                 }},
           ],
           "ajax": {
                 "url"   : url+"delivery/transferDatatable",
                 "data"  : {
                     status: $('select[name="status"]').val(),
                     fromDate: $('input[name="from_date"]').val(),
                     toDate: $('input[name="to_date"]').val()
                 },
                 "type"  : "POST"
           },

           "columnDefs": [
                 {
                      "targets"   : [1,2,3,4,5,6,7],
                      "orderable" : false,
                  },
             ],
             "initComplete" : function () {
                 $('[data-toggle="tooltip"]').tooltip()
             }
    });
}
