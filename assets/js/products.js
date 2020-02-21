const base_url = $('#base_url').val();

initDatatable();

// LISTENERS
$('select[name="location"], select[name="stock_level"]').on('change', function(){
    initDatatable();
});

$('#addProduct, #editProduct').on('hidden.bs.modal', function () {
    $('.custom-modal').modal('hide');
});

$(document).on('keydown', function(e) {
    if ($('.custom-modal.hidewhentyped').hasClass('show')) {
        $('.custom-modal.hidewhentyped').modal('hide');

        $('input[name="barcode_no"]').val('');
        $('input[name="barcode_no"]').focus();
    }
});

$('.calibrate-btn').on('click',  function(){
    Swal.fire({
        title: "Temporary Functionality",
        html: "<h3 class='font-weight-bold'>\"Beginning Balance\"</h3>Beginning balance will be updated according to current quantity of the location",
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#A4A23C",
        confirmButtonText: "Proceed",
    }).then((confirm) => {
        if (confirm.value) {
            $.ajax({
                url: base_url + 'products/update_beg_bal',
                dataType: 'json',
                success: function(response){
                    showNotification(response);
                    if (response.type == 'success') {
                        initDatatable();
                    }
                }
            } )
        }
    });
})

$('input[name="barcode_no"]').on('keydown', function(e){
    if (!$.isNumeric(e.key) && e.keyCode != 8) {
        e.preventDefault();
        $('input[name="sku"]').focus();
    }
})

$('#void_password').on('keypress', function(e){
    let message = {};

    if (e.charCode == 13) {
        if ($(this).val() == 'vgepassword') {
            message = {
                type: 'success',
                message: 'You can now edit Warehouse quantity'
            };

            $('#void').modal('hide');
            $('#openvoid').remove();
            $('input[name="quantity"]').attr('disabled', false);
        }else {
            message = {
                type: 'error',
                message: 'Please input administrator password'
            };
        }

        showNotification(message);
    }
});

$('#addProduct form').on('submit', function(e){
    e.preventDefault();
    const self = $(this);

    $.ajax({
        url: self.attr('action'),
        type: 'POST',
        dataType: 'json',
        data: self.serialize(),
        success: function(res){
            $.toast({
                heading: res.type.charAt(0).toUpperCase() + res.type.slice(1),
                text: res.message,
                position: 'top-right',
                loaderBg: '#fff',
                icon: res.type,
                hideAfter: 5000,
                stack: 6
            })

            if (res.type == 'success') {
                self[0].reset();
                initDatatable();
            }
        },
        error: function(){
            $.toast({
                heading: 'error',
                text: 'Something went wrong',
                position: 'top-right',
                loaderBg: '#fff',
                icon: res.type,
                hideAfter: 2000,
                stack: 6
            })
        }
    })
});

$('#editProduct form').on('submit', function(e){
    e.preventDefault();
    const self = $(this);

    $.ajax({
        url: self.attr('action'),
        type: 'POST',
        dataType: 'json',
        data: self.serialize(),
        success: function(res){
            $.toast({
                heading: res.type.charAt(0).toUpperCase() + res.type.slice(1),
                text: res.message,
                position: 'top-right',
                loaderBg: '#fff',
                icon: res.type,
                hideAfter: 5000,
                stack: 6
            })

            if (res.type == 'success') {
                self[0].reset();
                initDatatable();
                $('#editProduct').modal('hide');
            }
        },
        error: function(){
            $.toast({
                heading: 'error',
                text: 'Something went wrong',
                position: 'top-right',
                loaderBg: '#fff',
                icon: res.type,
                hideAfter: 2000,
                stack: 6
            })
        }
    })
});

$(document).on('click', '.action-edit',function(){
    const product_id = $(this).data('edit');

    $.ajax({
        url: base_url + 'products/getproductinfo',
        type: 'POST',
        data: {
            product_id: product_id,
            location_id : $('select[name="location"]').val()
        },
        dataType: 'json',
        success:function(res){
            $.each(res, function(key, value){
                $('.data-'+key).val(value);
            })
        }
    })
})

$(document).on('click', '.action-delete',function(){
    const product_id = $(this).data('delete');

    Swal.fire({
        title: "Wait!",
        text: "Are you sure to delete this record?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#A4A23C",
        confirmButtonText: "Yes",
    }).then((confirm) => {
        if (confirm.value) {
            $.ajax({
                url: base_url + 'products/delete',
                type: 'POST',
                data: {
                    product_id: product_id,
                },
                dataType: 'json',
                success:function(res){
                    showNotification(res);
                    if (res.type == 'success') {
                        initDatatable();
                    }
                }
            })
        }
    });

})
// END OF LISTENERS

// #################################################################################

// FUNCTIONS
function handleClickView(product_id){
    $.ajax({
        url: base_url + 'products/getproductinfo',
        type: 'POST',
        dataType: 'json',
        data: {
            product_id: product_id,
            location_id: $('select[name="location"]').val()
        },
        success:function(res){
            console.log(res);
            $.each( res, function( key, value ) {
                $('#'+key).html(value);
            });

            $('#viewProduct .action-edit').attr('data-edit', res.product_id);
        }
    })
}

function initDatatable(){
    let columns = [], targets = [3, 4, 5, 6, 7];

    columns.push(
        {"data":"category_name"},
        {"data":"name"},
        {"data":"price"},
        {"data":"beg_balance", "render":function(data){
            return data
        }},
        {"data":"qty"},
        {"data":"qty", "render": function(data, type, row){
            return getStatus(data, row)
        }},
        {"data":"date_modified"},
        {"data":"action", "render": function(data, type, row){
            var html = '<a href="javascript:;" data-toggle="modal" data-target="#viewProduct" onclick="handleClickView('+row.product_id+')"><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></a> ';

                if (row.editable) {
                    html += ' <a href="javascript:;" class="action-edit" data-toggle="modal" data-target="#editProduct" class="m-l-5" data-edit="'+row.product_id+'""><i class="fa fa-edit" data-toggle="tooltip" title="Edit"></i></a>';
                    html += ' <a href="javascript:;" class="action-delete" class="m-l-5" data-delete="'+row.product_id+'""><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i></a>';
                }

            return html
        }},
    );


    $('#products').DataTable({
        "destroy"        : true,
        "processing"     : true,
        "serverSide"     : true,
        "order"          : [[1, 'ASC'], [0 , 'ASC']],
        "columns"        : columns,
        "ajax": {
            "url"   : base_url + "products/productDataTable",
            "type"  : "POST",
            "data"  : {
                location: $('select[name="location"]').val(),
                stock_level: $('select[name="stock_level"]').val()
            }
        },
        "columnDefs": [{
            "targets"   : targets,
            "orderable" : false,
        }],
        "initComplete" : function () {
            $('[data-toggle="tooltip"]').tooltip()
        }
    });
}

function getStatus(qty, data){
    const selected_location = $('select[name="location"]').val();

    let display = '';

    const compareTo = parseInt(selected_location) == 1 ? data.min_stock_warehouse : data.min_stock_kiosk;

    if (parseInt(qty) != 0) {
        if (parseInt(qty) > parseInt(compareTo)) {
            display = '<span class="label label-success label-rounded">In Stock</span>';
        }else {
            display = '<span class="label label-danger label-rounded">Low Stock</span>';
        }
    }else {
        display = '<span class="label label-inverse label-rounded">Out of Stock</span>';
    }

    return display;
}
// END OF FUNCTIONS
