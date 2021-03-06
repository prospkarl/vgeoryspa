const BASE_URL = $('#base_url').val();

$(document).ready(function(){
    getData();
})

function getData(){
    $.ajax({
        url: BASE_URL + 'inventory_movement/get_data',
        dataType:'json',
        beforeSend: function(){
            $('.preloader').show();
        },
        success:function(res){
            generateTableInput(res, "#inventory_movement_table");
            $('#inventory_movement_table').DataTable({
                "paging" : false,
                "order" : [[ 0, "asc" ]],
            })
        },
        complete: function(){
            setTimeout(function () {
                $('.preloader').hide();
            }, 1500);
        },
    })
}
