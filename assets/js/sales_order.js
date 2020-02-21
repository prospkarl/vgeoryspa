const BASE_URL = $('#base_url').val();

// LISTENERS
$(document).ready(function(){
    initDatatable();
})

$('select[name="status"]').on('change', function(){
    initDatatable();
});

$(document).on('click', '.remove-row', function(){
    $(this).parents('tr').remove();
    computeTotal();
});

$(document).on('click', '.add-more', function(){
    addRow();
});

$('.invoice-container').find('.request_qty').trigger('change');

$(document).on('click', '.lister', function(){
    const self = this;

    $.ajax({
        url: BASE_URL + 'common/getiteminfo/' + $(this).data('id'),
        type: 'POST',
        dataType:'json',
        success:function(res){
            if (res.current_qty == 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Oops!',
                    text: 'This item is out of stock',
                })
                $(self).parents('tr').find('.autocomplete ').val('');
                $(self).parents('tr').find('.price ').html('0');
                $(self).parents('tr').find('.available-qty ').html('0');
                return false;
            }
            const product_id = $(self).data('id');

            if ($('.autocomplete_holder[value="' + product_id + '"]').length == 0) {
                $(self).parents('tr').find('.autocomplete').val(res.product_name);
                $(self).parents('tr').find('.autocomplete_holder').val(product_id);
                $(self).parents('tr').find('.available-qty').html(res.current_qty);
                $(self).parents('tr').find('.price').html(res.price);
                $(self).parents('tr').find('.request_qty').val('1');
                $(self).parents('tr').find('.request_qty').trigger('change');
            }else {
                $(self).parents('tr').remove();
                const current_qty = $('.autocomplete_holder[value="' + product_id + '"]').parents('tr').find('.request_qty').val();
                $('.autocomplete_holder[value="' + product_id + '"]').parents('tr').find('.request_qty').val(parseInt(current_qty) + 1);
                $('.autocomplete_holder[value="' + product_id + '"]').parents('tr').find('.request_qty').trigger('change');
            }
        }
    })
});

$(document).on('change keyup', '.request_qty, .discount-input', function(){
    const qty = $(this).parents('tr').find('.request_qty').val();
    const available = $(this).parents('tr').find('.available-qty').html();
    const price = $(this).parents('tr').find('.price').html();

    let discount = $(this).parents('tr').find('.discount-input').val();

    if (qty > parseInt(available)) {
        $(this).val(available);
    }

    let total_amount = 0;

    total_amount = qty * price;
    discount = (discount / 100) * total_amount;


    $(this).parents('tr').find('.discount-value').val(discount);
    $(this).parents('tr').find('.sub-total').val(total_amount);
    $(this).parents('tr').find('.total_amt').val(total_amount - discount);
    $(this).parents('tr').find('.total').html((total_amount - discount).toFixed(2));

    computeTotal();
});

$('#makeapurchase').on('submit', function(e){
    e.preventDefault();
    confirmSubmit($(this));
});

$('.invoice-container form').on('submit', function(e){
    e.preventDefault();
    confirmSubmit($(this));
})

// FUNCTIONS
function confirmSubmit(theForm) {
    let hasItems = true;

    if ($('.autocomplete_holder').length) {
        $('.autocomplete_holder').each(function(){
            if ($(this).val() == '') {
                hasItems = false;
            }
        })
    }else {
        hasItems = false;
    }

    if (hasItems) {
        let html = '';

        html += '<table class="table table-bordered m-t-30">';
        html += '<thead>';
        html += '<tr>';
        html += '<th width="40%">Item Name</th>';
        html += '<th width="10%">Quantity</th>';
        html += '<th>Total</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        html += '<small>Before submitting, please review your order.</small>';

        $('.item').each(function(index, el){
            const
                item_name = $(el).find('.item_name').val(),
                qty = $(el).find('.request_qty').val(),
                total = $(el).find('.total').html();

                html += '<tr>';
                html +=     '<td>';
                html +=         item_name;
                html +=     '</td>';
                html +=     '<td>';
                html +=         qty;
                html +=     '</td>';
                html +=     '<td>';
                html +=        total;
                html +=     '</td>';
                html += '</tr>';
        })

        html += '<tr style="background:#BCEFBB">';
        html +=     '<th>';
        html +=         'Total: ';
        html +=     '</th>';
        html +=     '<td>';
        html +=         $('.total-items').html();
        html +=     '</td>';
        html +=     '<td>';
        html +=        $('.total-amount').html();
        html +=     '</td>';
        html += '</tr>';
        html += '</tbody>';

        Swal.fire({
           title: "Review Order",
           html: html,
           showCancelButton: true,
           confirmButtonColor: "#1E641D",
           confirmButtonText: "Purchase",
        }).then((result) => {
            if (result.value) {
                submitForm(theForm);
            }
        });
    }else {
        const msg = {
            type: 'error',
            message: 'Please select an Item'
        };
        showNotification(msg);
    }
}

function submitForm(form) {
    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: $(form).serialize(),
        dataType:'json',
        success: function(res){
            if (res.type == 'success') {
                Swal.fire({
                    title: "Purchase Successful!",
                    html: res.data.totals,
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#1E641D",
                    confirmButtonText: "View Invoice",
                }).then((result) => {
                    if (result.value) {
                        window.location.href = BASE_URL + 'sales_order/view/' + res.data.invoice_id
                    }else {
                        window.location.href = BASE_URL + 'sales_order/makeapurchase'
                    }
                });
            }else {
                showNotification(res);
            }
        },
    })
}

function clearItems(){
    let html = '';

    html += '<tr>';
        html += '<td colspan="100%" class="text-center add-more" style="cursor: pointer;background:#bcefbb;">';
            html += '<button class="btn btn-sm btn-outline text-green" type="button" name="button"><i class="fa fa-plus"></i> Add Item</button>';
        html += '</td>';
    html += '</tr>';

    $('#items tbody').html(html);
    computeTotal();
}


function addRow(){
    let html = '';

    html += "<tr class='item'>";
    html += "<td>";
    html += "<div class='autocomplete_drp'>";
    html += "<input required class='autocomplete form-control form-control-sm item_name' type='text' placeholder='Type Barcode, SKU, Name'>";
    html += "<input class='autocomplete_holder' type='hidden' name='items[]'>";
    html += "<div class='autocomplete_drp-content'></div>";
    html += "</td>";
    html += "<td><input type='number' name='quantity[]' class='request_qty form-control form-control-sm' min='1' /></td>";
    html += "<td class='available-qty'>0</td>";
    html += "<td class='price'>0</td>";
    html += "<td><input type='hidden' name='discount[]' class='discount-value'/><input type='number' class='discount-input form-control form-control-sm' min='0' max='100' value='0' required/></td>";
    html += "<td><input type='hidden' class='sub-total' /><input type='hidden' class='total_amt' name='total[]' /><span class='total'>0</span></td>";
    html += "<td class='text-center'><a href='javascript:;' class='remove-row'><i class='fa fa-trash' /><a/></td>";
    html += "</tr>";

    $('#items tbody .add-more').parents('tr').before(html);
}

function computeTotal(){
    const itemsTable = $('#items');

    let total_qty = 0;
    let total_amt = 0;
    let subtotal = 0;
    let overall_discount = 0;

    $(itemsTable).find('.item').each(function(){
        total_qty = total_qty + parseInt($(this).find('.request_qty').val());
        subtotal = subtotal + parseFloat($(this).find('.sub-total').val());
        total_amt = total_amt + parseFloat($(this).find('.total').html());
        overall_discount = overall_discount + parseFloat($(this).find('.discount-value').val());
    });

    $('.total-items').html(total_qty);
    $('.total-amount').html(numberWithCommas(total_amt.toFixed(2)));
    $('.subtotal').html(subtotal.toFixed(2));
    $('input[name="total-discount"]').val(overall_discount.toFixed(2));
    $('.overall_discount').html(overall_discount.toFixed(2));
    $('input[name="total-items"]').val(total_qty);
    $('input[name="total-amount"]').val(total_amt);
}

function initDatatable(){
    if ($('#transfers').hasClass('datatable')) {
        $('#transfers').DataTable({
            "destroy"       : true,
            "processing"     : true,
            "serverSide"     : true,
            "order"          : [[0, 'desc']],
            "columns"        :[
                 {"data":"display_id"},
                 {"data":"location"},
                 {"data":"issued_by"},
                 {"data":"customer_name"},
                 {"data":"date_issued"},
                 {"data":"total_items"},
                 {"data":"total_amount"},
                 {"data":"status" , "render" : function(data){
                     let html = '';

                     switch (data) {
                         case '0':
                             html = '<span class="label label-warning label-rounded">Pending</span>'
                             break;
                         case '1':
                             html = '<span class="label label-info label-rounded">Completed</span>'
                             break;
                         case '2':
                             html = '<span class="label label-danger label-rounded">Void</span>'
                             break;

                     }

                     return html;
                 }},
                 {"data":"sales_id","render" : function(data, type, row) {
                     let html = '<a href="'+ BASE_URL + 'sales_order/view/' + data + '" class="btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-eye"></i> View</a>';
                     return html;
                 }},
            ],

            "ajax": {
                 "url"   : BASE_URL + "sales_order/salesDatatable",
                 "type"  : "POST",
                 "data" : {
                     status: $('select[name="status"]').val()
                 }
            },
            "columnDefs": [
                {
                    "targets"   : [8],
                    "orderable" : false,
                },
            ],
        });
    }else {
        $('#transfers').DataTable({
           "columnDefs": [
                {
                  "targets"   : [6],
                  "orderable" : false,
                },
            ],
        });
    }
}

$('.print-invoice').on('click', function(){
    var id = $(this).data('trig');

    setTimeout(function () {
        $('#invoiceContainer').print({
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
})
