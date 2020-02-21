const base_url = $("#base_url").val();

function handleClickDelete(location_id){
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this Location",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#1A6519",
        confirmButtonText: "Yes",
    }).then((confirm) => {
        if (confirm.value) {
            $.ajax({
                url: base_url + 'locations/deletelocation',
                type:'post',
                dataType:'json',
                data: {
                    location_id: location_id
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
                    locationList.ajax.reload();
                }
            })
        }
    });
}

function handleClickEdit(location_name, location_id){
    $('input[name="location_id"]').val(location_id);
    $('input[name="location_name"]').val(location_name);


    $('.modal-title').html('Update Location');
    $('.action-btn').html('Update');
}

var locationList = $('#locations').DataTable({
    "processing"     : true,
    "serverSide"     : true,
    "order"          : [[0,'desc']],
    "columns"        :[
        {"data":"location_id"},
        {"data":"name"},
        {"data":"date_added"},
        {"data":"action", "render":function(data, type, row){
            var html = '<a href="javascript:;" data-toggle="modal" data-target="#addLocation" onclick="handleClickEdit(`'+row.name+'`,'+row.location_id+')"><i class="fa fa-edit" data-toggle="tooltip" title="Edit"></i></a> ';
            html += '<a href="javascript:;" class="m-l-5" onclick="handleClickDelete('+row.location_id+')"><i class="fa fa-trash" data-toggle="tooltip" title="Trash"></i></a>';

            return html
        }},
    ],
    "ajax": {
        "url"   : base_url+"locations/locationDataTable",
        "type"  : "POST"
    },
    "columnDefs": [ {
        "targets"   : [3],
        "orderable" : false,
    },],
    "initComplete" : function () {
        $('[data-toggle="tooltip"]').tooltip()
    }
});

$('#addLocation').on('hidden.bs.modal', function () {
    $(this).find('input').val('');


    $('.modal-title').html('Add New Location');
    $('.action-btn').html('Create');
});

$(document).ready(function(){
    $('#addLocation form').on('submit', function(e){
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
                    locationList.ajax.reload();
                }

                if (res.data.type == 'update') {
                    $('#addLocation').modal('hide');
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
