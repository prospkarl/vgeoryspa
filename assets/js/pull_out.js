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
    newTr += '<td class="quantitybefore">0</td>';
    newTr += '<td><input type="number" class="form-control request_qty" min="0" name="quantity[]" required /></td>';
    newTr += '<td><input type="hidden" class="after_quantity" type="text" name="after_quantity[]"><span class="quantityafter">0</span></td>';
    newTr += '<td class="text-center"><a href="javascript:;" class="remove-item"><i class="fa fa-trash"></i></a></td>';
    newTr += '</tr>';

    $(this).parents('tr').before(newTr);
});

$(document).on('change', 'select[name="location"]', function(){
    $('.purchaseTB tbody').html('<tr style="text-align:center"> <td colspan="9"><b><a class="text-info add_more" name="button"> <i class=" fas fa-plus"></i> Add More</a></b> </td> </tr>');
});

$(document).on('click', '.lister', function(){
    const self = this;

    $.ajax({
        url: BASE_URL + 'common/getiteminfo/' + $(this).data('id'),
        type: 'POST',
        data: {
            location: $('select[name="location"]').val()
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

            $(self).parents('tr').find('.quantityafter').removeAttr('data-currentqty');
            $(self).parents('tr').find('.autocomplete ').val(res.product_name);
            $(self).parents('tr').find('.autocomplete_holder').val($(self).data('id'));
            $(self).parents('tr').find('.request_qty').val('');
            $(self).parents('tr').find('.quantityafter').html(res.current_qty);
            $(self).parents('tr').find('.quantitybefore').html(res.current_qty);
        }
    })
})

$(document).on('click', '.remove-item', function(){
    $(this).parents('tr').remove();
});

$(document).on('keyup', '.request_qty', function(){
    const thisValue = parseFloat($(this).val());
    const currentqty = parseFloat($(this).parents('tr').find('.quantitybefore').html());

    const newValue = currentqty - thisValue;

    if ($(this).val()) {
        if (newValue < 0) {
            $(this).parents('tr').find('.quantityafter').html(currentqty);
            $(this).parents('tr').find('.after_quantity').val(currentqty);
            $(this).val(currentqty);
        }else {
            $(this).parents('tr').find('.quantityafter').html(newValue);
            $(this).parents('tr').find('.after_quantity').val(newValue);
        }
    }else {
        $(this).parents('tr').find('.quantityafter').html(currentqty);
    }
})


$('#pullOutModal form').on('submit', function(e){
    e.preventDefault();

    $.ajax({
        url: BASE_URL + 'pull_out/submit',
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        success: function(res){
            if (res.type == 'success') {
                $('#pullOutModal').modal('hide');
                $('#pullOutModal tbody').html('<tr style="text-align:center"> <td colspan="6"> <a href="javascript:;" class="text-info add_more" name="button"> <i class=" fas fa-plus"></i> Add More </a> </td> </tr>');
                $('#pullOutModal form')[0].reset('');
                initDatatable();
            }
            showNotification(res);
        }
    })
})


function initDatatable(){
    $('#requestitems').DataTable({
        "destroy"        : true,
        "processing"     : true,
        "serverSide"     : true,
        "order"          : [[0,'desc']],
        "columns"        :[
            {"data":"pull_out_id"},
            {"data":"created_by"},
            {"data":"date_created"},
            {"data":"action", "render":function(data, type, row){
                var html = '<a class="btn btn-sm btn-rounded btn-outline-success" href="javascript:;" data-toggle="modal" data-target="#viewModal" onclick="handleClickView('+row.pull_out_id+');"><i class="fa fa-eye"></i> View</a> ';

                return html
            }},
        ],
        "ajax": {
            "url"   : BASE_URL + "pull_out/pulloutDatatable",
            "type"  : "POST",
        },
        "columnDefs": [ {
            "targets"   : [3],
            "orderable" : false,
        }, ],
    });
}

function handleClickView(request_id){
    $.ajax({
        url: BASE_URL + 'pull_out/getPullOutInfo',
        type: 'POST',
        dataType: 'json',
        data: {
            id: request_id
        },
        beforeSend: function(){
            const loader = `<td colspan="2" class="text-center">
                                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                  <span class="sr-only">Loading...</span>
                                </div>
                            </td>`;
            $('#viewModal tbody').html(loader);
        },
        success: function(res){
            if (res.type == 'success') {
                $('#viewModal tbody').html(res.data.html);
                $('#viewModal .remarks').html(res.data.remarks);
                $('#viewModal .location').html(res.data.location);
                $('#viewModal .created_by').html(res.data.created_by);
                $('#viewModal .date_created').html(res.data.date_created);
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
