const BASE_URL = $('#base_url').val();


$(document).ready(function(){
    if ($('input[name="current_location_id"]').val() != '') {
        getSales();
    }

    // Setlocation
    $('#setlocationform').on('submit',function(e){
        e.preventDefault();

        const self = this;
        $.ajax({
            url: BASE_URL + 'sales/setlocation',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success:function(res){
                showNotification(res);

                if (res.type == 'success') {
                    const   current_location = $(self).find('select[name="location"] option:selected').html(),
                            current_location_id = $(self).find('select[name="location"] option:selected').val();

                    $('.modal-mask').fadeOut();
                    $('#current_location').html(current_location);
                    $('input[name="current_location_id"]').val(current_location_id);
                    getSales();
                }
            }
        })
    })
})


function getSales(){
    $.ajax({
        url: BASE_URL + 'sales/get_sales_this_week/' + $('input[name="current_location_id"]').val(),
        type: 'POST',
        success: function(res){
            $('.sales_this_week').html(res)
        }
    })
}
