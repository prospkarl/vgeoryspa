$(document).ready(function() {
    var url = $("#base_url").val();

    $.ajax({
        async: false,
        url: url + "acquisition/setCost",
        type:'post',
        dataType:'json',
        data: {},
        success: function(data) {
          generateTableInput(data, "#acquisition");
      }
    });

    $('.passOn').attr('readonly', true);
    $('.per_cost').attr('readonly', true);
    $('button[data-id="pa"]').css('pointer-events', 'none');

    $(".set_it").on('click', function(e) {
        e.preventDefault();
        $('.per_cost').removeAttr('readonly');
        $('.set_it').hide();
        $('.set_save').show();
        $('.set_cancel').show();

    });

    $(".set_cancel").on('click', function(e) {
        e.preventDefault();
        window.location.reload();
    });

    $(".per_cost").on('change keydown', function() {
        var supplier = $(this).parents("tr").find('.supplier').html();
        var acquistion_percentage =$(this).val()/100;
        var srp = $(this).parents("tr").find('.priced_static').val();
        var passon_percentage =$(this).val()/100;
        var acquistion = $(this).parents("tr").find('.ac_cost').val();
        var pass_total = acquistion * passon_percentage;
        var acquistion_total = srp * acquistion_percentage;
        $(this).parents("tr").find('.ac_cost').val(acquistion_total.toFixed(2));
        $(this).parents("tr").find('.ac_cost_html').html("&#x20B1; " + acquistion_total.toFixed(2));
        $(this).parents("tr").find('.passOn').removeAttr('readonly');

    });

    $(".passOn ").on('change keydown', function() {
        var supplier = $(this).parents("tr").find('.supplier').html();
        var acquistion_percentage =$(this).val()/100;
        var srp = $(this).parents("tr").find('.priced_static').val();
        var passon_percentage =$(this).val()/100;
        var acquistion = $(this).parents("tr").find('.ac_cost').val();
        var pass_total = (acquistion * passon_percentage) + parseFloat(acquistion);
        var acquistion_total = srp * acquistion_percentage;
        $(this).parents("tr").find('.passOn_html').val(pass_total.toFixed(2));
        $(this).parents("tr").find('.pass_cost').html("&#x20B1; " + pass_total.toFixed(2));
    });
    var type = '';
    $('.set_all').on('click', function(e) {
        e.preventDefault();
        $('.per_cost').removeAttr('readonly');
        var title = '';

        switch ($(this).data("id")) {
            case "ac":
                title= "Acquisition";
                type ='ac';
                break;
            case "pa":
                title= "Passed on Cost";
                type ='pa';
                break;
            default:
        }
        $('.set_it').hide();
        $('.set_save').show();
        $('.set_cancel').show();
        $("#vcenter").html(title);
        $('#setModal').modal('show');
    });

    $('.setBtn').on('click', function(e) {
        e.preventDefault();
        $('#setModal').modal('hide');
        switch (type) {
            case "ac":
                $("input[name='cost[]']").val($('.setPer').val());
                $('.per_cost').trigger('change');
                $('button[data-id="pa"]').css('pointer-events', 'auto');
                break;
            case "pa":
                $(".passOn").val($('.setPer').val());
                $('.passOn').trigger('change');
                break;
            default:
        }
        $('.setPer').val('');

    });



    $(".setAc").on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            async: false,
            url: url + "acquisition/submitAcquisition",
            type:'post',
            dataType:'json',
            data: $(this).serialize(),
            success: function(data) {
                showNotification(data);
                    window.location.reload();

            }
        });
    })



})
