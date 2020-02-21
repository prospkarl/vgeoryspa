$(document).ready(function (){
    var url = $("#base_url").val();

    $('#loginForm').on('submit',function(e) {
        e.preventDefault();
        const self = $(this);
        $.ajax({
            url: url + 'login/validate',
            type:'post',
            dataType:'json',
            data:self.serialize(),
            beforeSend: function(){
                $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            },
            success: function (data) {
                console.log(data);
                if (data.success) {
                    if (data.is_sales) {
                        window.location.href = url + 'sales';
                    }else {
                        window.location.href = url;
                    }
                }else {
                    $('.error_alert').html(data.msg);
                    $('.error_alert').show();
                }
            },
            complete: function(){
                $('button[type="submit"]').html('Log In');
            }
        });
    });

});
