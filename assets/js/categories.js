const base_url = $("#base_url").val();

function handleClickDelete(cat_id){
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this category",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#A4A23C",
        confirmButtonText: "Yes",
    }).then((confirm) => {
        if (confirm.value) {
            $.ajax({
                url: base_url + 'categories/deletecategory',
                type:'post',
                dataType:'json',
                data: {
                    category_id: cat_id
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
                    categoryList.ajax.reload();
                }
            })
        }
    });
}

function handleClickEdit(cat_name, cat_id){
    $('input[name="category_id"]').val(cat_id);
    $('input[name="category_name"]').val(cat_name);


    $('.modal-title').html('Update Category');
    $('.action-btn').html('Update');
}

var categoryList = $('#categories').DataTable({
    "processing"     : true,
    "serverSide"     : true,
    "order"          : [[0,'desc']],
    "columns"        :[
        {"data":"category_id"},
        {"data":"name"},
        {"data":"date_added"},
        {"data":"action", "render":function(data, type, row){
            var html = '<a href="javascript:;" data-toggle="modal" data-target="#addCategory" onclick="handleClickEdit(`'+row.name+'`,'+row.category_id+')"><i class="fa fa-edit" data-toggle="tooltip" title="Edit"></i></a> ';
            html += '<a href="javascript:;" class="m-l-5" onclick="handleClickDelete('+row.category_id+')"><i class="fa fa-trash" data-toggle="tooltip" title="Trash"></i></a>';
            return html
        }},
    ],
    "ajax": {
        "url"   : base_url+"categories/categoryDataTable",
        "type"  : "POST"
    },
    "columnDefs": [ {
        "targets"   : [3],
        "orderable" : false,
    }, ],
    "initComplete" : function () {
        $('[data-toggle="tooltip"]').tooltip()
    }
});

$('#addCategory').on('hidden.bs.modal', function () {
    $(this).find('input').val('');


    $('.modal-title').html('Add New Category');
    $('.action-btn').html('Create');
});

$(document).ready(function(){

    $('#addCategory form').on('submit', function(e){
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
                    categoryList.ajax.reload();
                }

                if (res.data.type == 'update') {
                    $('#addCategory').modal('hide');
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
