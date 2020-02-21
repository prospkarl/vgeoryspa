$(document).ready(function() {
    var url = $("#base_url").val();

    $.ajax({
        async: false,
        url: url + "inventory_analysis/checkAvg",
        type:'post',
        dataType:'json',
        data: {},
        success: function(data) {
            $('.slow_mov tbody').html(data.slow);
            $('.fast_mov tbody').html(data.fast);

        }
    });
});
