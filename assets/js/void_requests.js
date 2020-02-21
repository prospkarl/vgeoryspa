const BASE_URL = $('#base_url').val();

$(document).ready(function(){
    $('#table_requests').DataTable({
        "columnDefs": [
            {
                "targets"   : [6, 7],
                "orderable" : false,
            },
        ],
    });
});


$(document).on('click', '.action-btn', function(){
    const sales_id = $(this).attr('data-sales_id');
    const action = $(this).attr('data-action');

    Swal.fire({
        title: "Are you sure you want to " + action + " this request?",
        text: "You will not be able to undo this action",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#A4A23C",
        confirmButtonText: "Yes",
    }).then((confirm) => {
        if (confirm.value) {
            $.ajax({
                url: BASE_URL + 'void_requests/submit',
                type: 'POST',
                data: {
                    sales_id: sales_id,
                    action: action
                },
                dataType:'json',
                success:function(res){
                    showNotification(res);

                    if (res.type == 'success') {
                        setTimeout(function () {
                            window.location.href = BASE_URL + 'sales_order';
                        }, 2000);
                    }

                }
            })
        }
    });

});
