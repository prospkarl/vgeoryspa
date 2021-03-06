const BASE_URL = $('#base_url').val();

$(document).ready(function(){
    initDatatable();
});

// Listeners
$(document).on('click', '.add_more',function(){
    let newTr = '<tr>';
    newTr += "<td>";
    newTr +="<div class='autocomplete_drp'>";
    newTr +="<input required class='autocomplete form-control' type='text' placeholder='Type Barcode, SKU, Name'>";
    newTr +="<input class='autocomplete_holder' type='hidden' name='items[]'>";
    newTr +="<div class='autocomplete_drp-content'></div>";
    newTr +="</div>";
    newTr += "</td>";
    newTr += '<td class="warehouse_qty">0</td>';
    newTr += '<td><input type="number" class="form-control request_qty" min="0" name="quantity[]" required /></td>';
    newTr += '<td class="quantityafter">0</td>';
    newTr += '<td class="text-center"><a href="javascript:;" class="remove-item"><i class="fa fa-trash"></i></a></td>';
    newTr += '</tr>';

    $(this).parents('tr').before(newTr);
});

$(document).on('click', '.lister', function(){
    const self = this;

    $.ajax({
        url: BASE_URL + 'request_items/getiteminfo/' + $(this).data('id'),
        type: 'POST',
        dataType:'json',
        success:function(res){
            console.log(res);
            $(self).parents('tr').find('.autocomplete ').val(res.product_name);
            $(self).parents('tr').find('.autocomplete_holder').val($(self).data('id'));
            $(self).parents('tr').find('.warehouse_qty').html(res.warehouse_qty);
            $(self).parents('tr').find('.request_qty').attr('max', res.warehouse_qty);
            $(self).parents('tr').find('.quantityafter').html(res.current_qty);
            $(self).parents('tr').find('.quantityafter').attr('data-currentqty',res.current_qty);
        }
    })
})

$(document).on('click', '.remove-item', function(){
    $(this).parents('tr').remove();
});

$(document).on('keyup change', '.request_qty', function(){
    const thisValue = parseFloat($(this).val());
    const currentqty = parseFloat($(this).parents('tr').find('.quantityafter').attr('data-currentqty'));

    if ($(this).val()) {
        $(this).parents('tr').find('.quantityafter').html(thisValue + currentqty);
    }else {
        $(this).parents('tr').find('.quantityafter').html(currentqty);
    }
})


$('#createRequest form').on('submit', function(e){
    e.preventDefault();
    $('button[type="submit"]').html('Please wait..').prop('disabled', 1);
    $.ajax({
        url: BASE_URL + 'request_items/submit',
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function(){
            $('button[type="submit"]').html('Please wait..').prop('disabled', 1);
        },
        success: function(res){
            if (res.type == 'success') {
                $('#createRequest').modal('hide');
                $('#createRequest tbody').html('<tr style="text-align:center"> <td colspan="6"> <a href="javascript:;" class="text-info add_more" name="button"> <i class=" fas fa-plus"></i> Add More </a> </td> </tr>');
                initDatatable();
            }
            showNotification(res);
        },
        complete: function(){
            $('button[type="submit"]').html('Create').prop('disabled', false);
        },
    })
})


function initDatatable(){
    $('#requestitems').DataTable({
        "destroy"        : true,
        "processing"     : true,
        "serverSide"     : true,
        "order"          : [[0,'desc']],
        "columns"        :[
            {"data":"request_id"},
            {"data":"requested_by", "render": function(data){
                return '<span style="text-transform: capitalize">'+data+'</span>'
            }},
            {"data":"date_requested"},
            {"data":"status", "render": function(data){
                return getStatus(data)
            }},
            {"data":"action", "render":function(data, type, row){
                var html = '<a href="javascript:;" data-toggle="modal" data-target="#viewModal" onclick="handleClickView('+row.request_id+');" class="btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-eye"></i> View</a> ';

                return html
            }},
        ],
        "ajax": {
            "url"   : BASE_URL + "request_items/requestsDatatable",
            "type"  : "POST",
        },
        "columnDefs": [ {
            "targets"   : [3,4],
            "orderable" : false,
        }, ],
    });
}

function handleClickView(request_id){
    $('.receive-btn').hide();

    $.ajax({
        url: BASE_URL + 'request_items/getItems',
        type: 'POST',
        dataType: 'json',
        data: {
            request_id: request_id
        },
        beforeSend: function(){
            const loader = `<td colspan="3" class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                              <span class="sr-only">Loading...</span>
                            </div>`;
            $('#viewModal tbody').html(loader);
        },
        success: function(res){
            if (res.type == 'success') {
                $('#viewModal tbody').html(res.data.html);
                $('#viewModal .requested_by').html(res.data.requested_by);
                $('#viewModal .date_created').html(res.data.date_created);
                $('#viewModal .status').html(getStatus(res.data.status));
                $('#viewModal .remarks').html(res.data.remarks);

                if (res.data.status == '1') {
                    $('.receive-btn').show();
                }
            }else {
                showNotification(res);
            }
        }
    })
}


function getStatus(status){
    let display = '';

    switch (status) {
        case '0':
            display = '<span class="label label-inverse label-rounded">Pending</span>';
            break;
        case '1':
            display = '<span class="label label-info label-rounded">For Delivery</span>';
            break;
        case '2':
            display = '<span class="label label-success label-rounded">Received</span>';
            break;
        case '3':
            display = '<span class="label label-danger label-rounded">Cancelled</span>';
            break;

    }

    return display;
}
