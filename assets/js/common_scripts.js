function debounce(func, wait, immediate) {
  var timeout;

  return function() {
    var context = this,
      args = arguments;

    var callNow = immediate && !timeout;

    clearTimeout(timeout);

    timeout = setTimeout(function() {

      timeout = null;

      if (!immediate) {
        func.apply(context, args);
      }
    }, wait);

    if (callNow) func.apply(context, args);
  }
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function checkForDividers(){
    $('.with-divider').each(function(){
        const col_num = $(this).data('divide');
        $(this).find('tr th:nth-child('+col_num+')').addClass('border-left');
        $(this).find('tr td:nth-child('+col_num+')').addClass('border-left');
    })
}

function showNotification(response){
    $.toast({
        heading: response.type.charAt(0).toUpperCase() + response.type.slice(1),
        text: response.message,
        position: 'top-right',
        loaderBg: '#fff',
        icon: response.type,
        hideAfter: 2000,
        stack: 6
    })
}

function generateTableInput(data, id) {
    var table = '';
    table += '<thead>';
        table += '<tr>';
        for (var i = 0; i < data.header.length; i++) {
            table += '<th>'+ data.header[i] +'</th>'
        }
    table += '</thead>';
    table += "<tbody>";
    if (data.data.length != 0) {
        $.each(data.data, function(index, value) {
            table += "<tr>";
            $.each(value, function(index, value_tab){
             var td_class = (value_tab.class !=' ') ? value_tab.class : '';
                table += "<td class='"+td_class+"'>";
                if (typeof value_tab.data == 'object') {
                    $.each(value_tab.data, function(index, value) {
                        if (typeof value == 'object') {
                            switch (value.kind) {
                                case "input":
                                    var classname = (value.class != '') ? value.class :' ';
                                    var name =  (value.name != ' ') ? value.name : ' ';
                                    var data_val =  (value.value != ' ') ? value.value : ' ';
                                    table +=  "<input min='0'  type='"+value.type+"' class='"+classname +"' name='"+name +"' value='"+data_val +"'>";
                                    break;
                                default:
                            }
                        }else {
                            table +=  value;
                        }
                    })
                }else {
                    table += value_tab.data;
                }
                table += "</td>";
            });
            table += "</tr>";
        });
    }else {
        table += "<tr><td style='text-align: center' colspan='"+data.header.length+"'>NO RECORDS FOUND</td></tr>";
    }
    table += "</tbody>";
    $(id).html(table);
}

function generateTable(data, id) {
    var table = '';
    table += '<thead>';
        table += '<tr>';
        for (var i = 0; i < data.header.length; i++) {
            table += '<th>'+ data.header[i] +'</th>'
        }
    table += '</thead>';
    table += "<tbody>";
    if (data.data.length != 0) {
        $.each(data.data, function(index, value) {
            table += "<tr>";
            if (value.length != 0) {
                for (var i = 0; i < value.length; i++) {
                    table += "<td>"+ value[i] +"</td>";
                }
            }else {
                table += "<td colspan='"+data.header.length+"'>"+ value[i] +"</td>";
            }

            table += "</tr>";
        });
    }else {
        table += "<tr><td style='text-align: center' colspan='"+data.header.length+"'>NO RECORDS FOUND</td></tr>";
    }

    table += "</tbody>";
    $(id).html(table);
}

$(document).ready(function() {
    //Validation
    var url = $("#base_url").val();

    $("input,select,textarea").not(".esc-input, [type=submit], a").jqBootstrapValidation();

    //SelectPicker
    if ($('.selectpicker').length) {
         $('.selectpicker').selectpicker();
    }

    $(document).on('click','.delBut',function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    $('body').on('click', function() {
        $('.autocomplete_drp-content').css('display','none');
    });

    $(document).on('keydown','.autocomplete', function(e){
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });

    $(document).on('keyup','.autocomplete', debounce(function(e) {
        $(this).siblings('.autocomplete_holder').val('');

        $('.autocomplete_drp-content').removeClass('has-items');
        var val = $(this).val();
        var self = this;

        if (this.value.length >= 3) {
            $(this).parent().find('.autocomplete_drp-content').css('display','block');
            $.ajax({
                async:false,
                url: url + "common/autocomplete",
                type: "post",
                data: {input: val},
                dataType:"json",
                beforeSend: function(){
                    $('.autocomplete_drp-content').addClass('has-items');
                    let str = '';
                        str += "<div class=\"row list\" >";
                            str += "<div class='col-md-12 lister first'>";
                                str += '<h3>Please Wait...</h3>'
                            str += "</div>";
                        str += "</div>";

                    $('.autocomplete_drp-content').html(str);
                },
                success: function(data) {
                    var str='';

                    if (data.length != 0 ) {
                        for (var i = 0; i < data.length; i++) {
                            str += "<div class=\"row list\" >";
                                str += "<div class=\"col-md-12 lister "+ "first" +"\" data-id='"+data[i]['product_id']+"' data-name='"+ data[i]['name'] +"'>";
                                    str += "<div class=\"row\">";
                                        str += "<div class=\"col-md-12\">";
                                            str += "<h5 class='font-weight-bold'>" + data[i]['name'] + "</h5>";
                                        str += "</div>";
                                    str += "</div>";
                                    str += "<div class=\"row\">";
                                        str += "<div class=\"col-md-6\">";
                                            str += "<b>Barcode:</b>"+ data[i]['barcode'];
                                        str += "</div>";
                                        str += "<div class=\"col-md-6\">";
                                            str += "<b>Sku:</b>" +  data[i]['barcode'];
                                        str += "</div>";
                                    str += "</div>";
                                str += "</div>";
                            str += "</div>";
                        }
                        $('.autocomplete_drp-content').addClass('has-items');
                    }else if(data.length == 0){
                        str += "<div class=\"row list\"><strong>NO DATA FOUND</strong></div>";
                    }

                    $('.autocomplete_drp-content').html(str);
                },
                complete:function(){
                    if (e.keyCode == '13') {
                        setTimeout(function () {
                            $(self).siblings('.autocomplete_drp-content').find('.lister').trigger('click');
                        }, 1000);
                    }
                }
            });


        }else{
            $('.autocomplete_drp-content').css('display','none');
        }
    }, 500));


    if ($('table').length) {
      if ($('#user_type').val() == 2) {
        $('table').parent().addClass('responsive-table');
      }
    }
});


$('#changePassword form').on('submit', function(e){
    e.preventDefault();
    const self = $(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType:'json',
        data: $(this).serialize(),
        beforeSend: function(){
            $(this).find('.action-btn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        },
        success: function(response){
            if (response.type == 'success') {
                self[0].reset();
            }

            showNotification(response);
        },
        complete:function(){
            $(this).find('.action-btn').html('Submit');
        }
    })
})

$(document).on('submit', '#loginPassword form', function(e){
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(res){
            if (res.type == 'success') {
                window.location.href = $('#base_url').val();
            }else {
                $('input[name="password"]').val('');
                showNotification(res);
            }
        }
    })
})

$(document).on('click', '.click-user', function(){
    $('#loginPassword input[name="username"]').val($(this).data('username'));
    $('.full-name').html($(this).data('fullname'));
})


$(document).on('click', '.toggle-log', function(){
    var data = {
        referrer_id: $(this).attr('data-referrer'),
        tablename: $(this).attr('data-tablename')
    };

    $.ajax({
        url: $('#base_url').val() + 'common/getlogs',
        type:'POST',
        dataType: 'json',
        data: data,
        success:function(res){
            let html = '';

            if (res.length) {
                $(res).each(function(key, value){
                    html += '<tr>';
                    html +=     '<td>';
                    html +=         '<small> ' + value.content + ' </small>';
                    html +=     '</td>';
                    html +=     '<td>';
                    html +=         '<small style="text-transform:capitalize;"> ' + value.logged_by + '</small>';
                    html +=     '</td>';
                    html +=     '<td>';
                    html +=         '<small style="text-transform:capitalize;"> ' + value.date + '</small>';
                    html +=     '</td>';
                    html += '</tr>';
                })
            }else {
                html += '<h4 class="font-weight-bold m-t-30"> No Changes Yet </h4>';
            }

            $('#viewLogs table tbody').html(html);
        },
        complete: function(){
            $('[data-toggle="tooltip"]').tooltip()
        }
    })


})

//domtoimage class
$(document).on('click', '.domtoimage', function(){
    const target = document.getElementById($(this).attr('data-target'));
    const name = $(this).attr('data-name');

    domtoimage.toBlob(target, {quality: 1})
        .then(function (blob) {
            window.saveAs(blob, name);
        });
});
