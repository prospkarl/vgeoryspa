const base_url = $("#base_url").val();

function handleClickDelete(brand_id){
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this brand",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#1A6519",
        confirmButtonText: "Yes",
    }).then((confirm) => {
        if (confirm.value) {
            $.ajax({
                url: base_url + 'brands/deleteBrand',
                type:'post',
                dataType:'json',
                data: {
                    brand_id: brand_id
                },
                success: function(res){
                    $.toast({
                        heading: res.type.charAt(0).toUpperCase() + res.type.slice(1),
                        text: res.message,
                        position: 'top-right',
                        loaderBg: '#fff',
                        icon: res.type,
                        hideAfter: 2000,
                        stack: 6
                    })
                    brandsList.ajax.reload();
                }
            })
        }
    });
}

function handleClickEdit(brand_name, brand_id){
    $('input[name="brand_id"]').val(brand_id);
    $('input[name="brand_name"]').val(brand_name);
    $('.modal-title').html('Update Brand');
    $('.action-btn').html('Update');
}

var brandsList = $('#brands').DataTable({
    "processing"     : true,
    "serverSide"     : true,
    "order"          : [[0,'desc']],
    "columns"        :[
        {"data":"brand_id"},
        {"data":"name"},
        {"data":"date_added"},
        {"data":"action", "render":function(data, type, row){
            var html = '<a href="javascript:;" data-toggle="modal" data-target="#addBrand" onclick="handleClickEdit(`'+row.name+'`,'+row.brand_id+')"><i class="fa fa-edit"></i></a> ';

            html += '<a href="javascript:;" class="m-l-5" onclick="handleClickDelete('+row.brand_id+')"><i class="fa fa-trash"></i></a>';

            return html
        }},
    ],
    "ajax": {
        "url"   : base_url+"brands/brandDataTable",
        "type"  : "POST"
    },

    "columnDefs": [ {
        "targets"   : [3],
        "orderable" : false,
    },
],
});

$('#addBrand').on('hidden.bs.modal', function () {
    $(this).find('input').val('');

    $('.modal-title').html('Add New Brand');
    $('.action-btn').html('Create');
});

$(document).ready(function(){
    $('#addBrand form').on('submit', function(e){
        e.preventDefault();
        const self = $(this);

        $.ajax({
            url: self.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: self.serialize(),
            success: function(res){
                if (res.type == 'success') {
                    self[0].reset();
                    brandsList.ajax.reload();
                }

                if (res.data.type == 'update') {
                    $('#addBrand').modal('hide');
                }

                $.toast({
                    heading: res.type.charAt(0).toUpperCase() + res.type.slice(1),
                    text: res.message,
                    position: 'top-right',
                    loaderBg: '#178472',
                    icon: res.type,
                    hideAfter: 2000,
                    stack: 6
                })
            }
        })
    })
})
