var url = $("#base_url").val();

$(document).ready(function(){
    $('select[name="status"]').on('change',function(){
        initDatatable();
    });

    $('select[name="loc_from"]').on('change',function(){
        resetTable();
        populateItems();
    });

    $('select[name="loc_to"]').on('change',function(){
        resetTable();
    });

    $('.approveReq').on('click', function() {
        const action = $(this).attr('data-action');

        if (action == 'CSO') {
            $('select[name="loc_from"]').val(1);
            $('select[name="loc_from"] option').each(function(){
                $(this).attr('hidden', 1);
            })
        }else {
            $('select[name="loc_from"] option').each(function(){
                $('select[name="loc_from"]').val(2);
                if ($(this).val() != 1) {
                    $(this).removeAttr('hidden');
                }
            })
        }

        //Select Location
        $('select[name="loc_to"]').val($('input[name="location_requested"]').val());

        populateItems();
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

    $('#transferForm').on('submit',function(e){
        e.preventDefault();
        const self = $(this);

        if ($('select[name="loc_from"]').val() == $('select[name="loc_to"]').val()) {
            var not_dat = {
                message: "You cannot transfer items on the same location",
                type: 'warning'
            };
            showNotification(not_dat);
        }else {
            $.ajax({
                  async:false,
                  url: url + "requests/transferStock",
                  type: "post",
                  data: self.serialize(),
                  dataType: "json",
                  success: function(data) {
                      if (data.type =='success') {
                          setTimeout(function () {
                              window.location.reload();
                          }, 2000);
                      }
                      showNotification(data);

                  }
            });
        }
    });


    $(document).on('click', '.viewRequest',function(){
        var id= $(this).data('id');
        var status = $(this).data('status');
        $('input[name="req_id"]').val(id);

        $.ajax({
            async:false,
            url: url + "requests/viewRequest",
            type: "post",
            data: {id: id},
            dataType:"json",
            success: function(data) {
                $('#req_hid_id').val(data.request);
                $('.req_date').html(data.requested_date);
                $('input[name="location_requested"]').val(data.requested_from_location);
                $('.from').html(data.from);
                $('.by').html(data.requested_by);
                generateTable(data.table, ".reqTbl");
                $('.remark_view').html(data.remark);
                var stat = (data.status == 0) ? "Pending" : (data.status == 1) ? "Approved" : (data.status == 2) ? "Received " :(data.status == 3) ? "Declined" : "";
                if (status == 1 || status == 2 ) {
                    $('.approveReq').hide();
                    $('.btn_decline ').hide();
                }else if(status == 0) {
                    $('.approveReq').show();
                    $('.btn_decline ').show();
                }
                $('.status').html(stat);
                $('.approveReq').attr("data-id", data.request);
                $('table.reqTbl').attr('data-list', JSON.stringify(data.items));
            }
        });
    });

    function populateItems(){
        resetTable();
        $('#outofstockitems').hide();
        $('#outofstockitems tbody').empty();
        const items = JSON.parse($('table.reqTbl').attr('data-list'));

        $(items).each(function(key, value){
            addRow(value.item_id, value.qty);
        })
    }

    $(document).on('click','.viewDecline', function () {
        var id= $(this).data('id');
        var status = $(this).data('status');
        $.ajax({
            async:false,
            url: url + "requests/viewRequest",
            type: "post",
            data: {id: id},
            dataType:"json",
            success: function(data) {
                $('.req_date').html(data.requested_date);
                $('.from').html(data.from);
                $('.by').html(data.requested_by);
                $('.remark_view').html(data.remark);
                var stat = (data.status == 0) ? "Pending" : (data.status == 1) ? "Approved" : (data.status == 2) ? "Received " :(data.status == 3) ? "Declined" : "";

                $('.status').html(stat);
            }
        });
    });

    $(document).on('click','.btn_decline', function () {
        $('#declineModal').modal('show');
        $('#viewRequest').modal('hide');
    });

    $(document).on('click','.btn_cancel_dec', function () {
        $('#declineModal').modal('hide');
        $('#viewRequest').modal('show');
    })

    $('#declineRequest').on('submit', function(e) {
        e.preventDefault();
        var self = $(this);
        $.ajax({
            async:false,
            url: url + "requests/declineRequest",
            type: "post",
            data: self.serialize(),
            dataType:"json",
            success: function(data) {
                if (data.type == "success") {
                    request.ajax.reload();
                    $('#declineModal').modal('hide');
                    $('.remark').val('');
                }
                showNotification(data);
            }
        });
    });

    initDatatable();
});

function initDatatable(){
    $('#requestData').DataTable({
           "destroy"     : true,
           "processing"     : true,
           "serverSide"     : true,
           "order"          : [[0,'desc']],
           "bFilter"        : false,
           "columns"        :[
                 {"data":"request_id"},
                 {"data":"date_requested"},
                 {"data":"total_quantity"},
                 {"data":"requested_by","render": function(data, type, row) {
                     return row.Fname + " " + row.Lname;
                 }},
                 {"data":"Location"},
                 {"data":"status","render": function(data, type, row) {
                     return getStatus(data);
                 }},
                 {"data":"status","render" : function(data, type, row) {
                     var str= '';
                     if (row.status == 0) {
                          str += '<a class="btn btn-sm btn-rounded btn-outline-success viewRequest" href="" data-toggle="modal" data-target="#viewRequest" data-id="'+row.request_id+'" data-status="'+row.status +'""><i class="fas fa-eye"></i> View</a>';
                     }else if (row.status == 1) {
                           str += '<a class="btn btn-sm btn-rounded btn-outline-success viewRequest" href="" data-toggle="modal" data-target="#viewRequest" data-id="'+row.request_id+'" data-status="'+row.status +'""><i class="fas fa-eye"></i> View</a>';
                      }else if (row.status == 2) {
                           str += '<a class="btn btn-sm btn-rounded btn-outline-success viewRequest" href="" data-toggle="modal" data-target="#viewRequest" data-id="'+row.request_id+'" data-status="'+row.status +'""><i class="fas fa-eye"></i> View</a>';
                      }else if (row.status == 3){
                          str += '<a class="btn btn-sm btn-rounded btn-outline-success viewDecline" href="" data-toggle="modal" data-target="#declineView" data-id="'+row.request_id+' data-status="'+row.status +'""><i class="fas fa-eye"></i> View</a>';
                     }
                     return str;
                 }},
           ],

           "ajax": {
                 "url"   : url+"requests/requestDataTable",
                 "type"  : "POST",
                 "data"  : {status: $('select[name="status"]').val()}
           },

           "columnDefs": [
                 {
                      "targets"   : [2,3,4,5,6],
                      "orderable" : false,
                  },
             ],
    });
}


$(document).on('click','.ST_addmore', function(el) {
    el.preventDefault();
    addRow();
});

function resetTable() {
    var str ='<tr style="text-align:center"><td colspan="7"><b><a class="text-info ST_addmore" name="button"><i class=" fas fa-plus"></i> Add More</a></b></td></tr>'
    $('.stockTB tbody').html(str);
}

function addRow(item_id, qty){
    const trCount = $('.stockTB tbody tr').length;

    var str = "";
    str += "<tr id='item_"+trCount+"'>";
    str += "<td>";
    str +="<div class='autocomplete_drp'>";
          str +="<input required class='autocomplete form-control' type='text' placeholder='Type Barcode, SKU, Name'>";
          str +="<input class='autocomplete_holder' type='hidden' name='items[]' value='"+item_id+"'>";
          str +="<div class='autocomplete_drp-content'></div>";
    str +="</div>";
    str += "</td>";
    str += "<td data-qtyReq='"+(qty ? qty : 0)+"'><input required min='0' class='form-control qty_num' value='0' type='number' name='quantity[]' value='"+qty+"'/></td>";
    str += "<td class='warehouse_stock'>0</td>";
    str += "<td class='afterWare'>0</td>";
    str += "<td class='dest_stock'>0</td>";
    str += "<td class='afterDest'>0</td>";
    str += "<td><a class='delBut text-info'><i class='fas fa-trash'></i></a></td>";
    str += "</tr>";
    $('.ST_addmore').parents('tr').before(str);

    if (item_id) {
        itemOnClick(item_id, $('#item_'+trCount), true);
        $('#item_'+trCount).find('.qty_num').val(qty)
        setTimeout(function () {
            $('#item_'+trCount).find('.qty_num').trigger('keyup')
        }, 500);
    }
}

$(document).on('click', '.lister', function() {
    itemOnClick($(this).data('id'), $(this).parents('tr'));
});

function itemOnClick(item_id, elementTr, removeIfEmpty){
    $.ajax({
        url: url + 'stock_transfer/getSelItem/' + item_id,
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
                    text:  '"' + res.product_name + '" is not available on this location',
                })
                $(elementTr).find('.autocomplete').val('');
                $(elementTr).find('.quantitybefore').html('0');
                $(elementTr).find('.request_qty').val('0');
                $(elementTr).find('.quantityafter').html('0');

                if (removeIfEmpty) {
                    $(elementTr).remove();
                }


                let outofstock = '<tr>';
                    outofstock += '<td>' +res.product_name+ '</td>';
                    outofstock += '<td><span class="label label-danger label-rounded">Out of stock</span></td>';
                    outofstock += '</tr>';

                $('#outofstockitems table tbody').append(outofstock);
                $('#outofstockitems').show();
                return false;
            }

            $(elementTr).find('.autocomplete_holder').val(item_id);
            $(elementTr).find('.autocomplete ').val(res.product_name);
            $(elementTr).find(".warehouse_stock").attr('data-stock', res.current_qty);
            $(elementTr).find(".warehouse_stock").html(res.current_qty)
            $(elementTr).find(".afterWare").attr('data-stock', res.current_qty);
            $(elementTr).find(".afterWare").html(res.current_qty);
            $(elementTr).find(".afterDest").attr('data-stock', res.dest_qty);
            $(elementTr).find(".afterDest").html(res.dest_qty);
            $(elementTr).find(".dest_stock").attr('data-stock', res.dest_qty);
            $(elementTr).find(".dest_stock").html(res.dest_qty);
            $(elementTr).find(".qty_num").attr('max', res.current_qty);
        }
    })
}


function getStatus(status) {
    let html = '';

    switch (status) {
        case '0':
            html = '<span class="label label-inverse label-rounded">Pending</span>';
            break;
        case '1':
            html = '<span class="label label-info label-rounded">Approved</span>';
            break;
        case '2':
            html = '<span class="label label-success label-rounded">Received</span>';
            break;
        case '3':
            html = '<span class="label label-danger label-rounded">Cancelled</span>';
            break;
    }

    return html;
}
