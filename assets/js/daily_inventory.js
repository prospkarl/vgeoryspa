var url = $('#base_url').val();
$(document).ready(function(){
      dailyDatable(url);

      if ($('#recordDaily').length) {
          $.ajax({
              async: false,
              url: url + "daily_inventory/getalldaily",
              type:'POST',
              data:{
                  action: $('#recordDaily').attr('data-action'),
                  inventory_id: $('input[name="inventory_id"]').val()
              },
              dataType: 'json',
              success: function(data){
                  $('.coverage').html(data.coverage);
                  $('.date_from').val(data.date_from);
                  generateTableInput(data.table, "#recordDaily");
              },
              complete: function(){
                    $('#recordDaily').DataTable({
                        "order" : [[ 0, "asc" ]],
                        "paging" : false,
                    });
              }
          });
      }

      $.ajax({
        async: false,
        url: url + "daily_inventory/recordButton",
        type:'post',
        dataType: 'json',
        data:{},
        success: function(data){
            if (data.has_been_recorded) {
              $(".page-titles .col-md-7 button").hide();
              $('.page-titles .col-md-7').append("<div class='alert hide_me alert-success alert-rounded font-weight-bold' style='width:40%; float:right; text-align:center'><i class='fas fa-check'></i>Successfully Recorded</div>");
            }else {
              $(".page-titles .col-md-7 button").show();
              $('.page-titles .col-md-7 .hide_me').hide();
            }
        }
      });


      var date = 0;
      if ($('#dailyRecord').length) {
          $.ajax({
              async: false,
              url: url + "daily_inventory/displayDaily",
              dataType: 'json',
              type:'post',
              data:{id: $('#dailyRecord').data('id')},
              success: function(data){
                  $('.coverage').html(data.coverage);
                  date = data.coverage;
                  $('.record_by').html(data.recorded_by);
                  $('.status').html(data.verified);
                  $('.system_count').html('₱' + numberWithCommas(data.sytembal));
                  $('.physical_count').html('₱' + numberWithCommas(data.physicalbal));
                  $('.discrepancy_amt').html('₱' + numberWithCommas(data.variancebal));
                  $('.item_count').html(data.system_item);
                  $('.item_phy_count').html(data.physical_item);
                  $('.discrepancy_item').html(data.variance_item);
                  generateTable(data.table,"#all_rec_items");
                  var floatA =  parseFloat(data.variance_item);
                  (floatB < 0) ? $(".discrepancy_amt").addClass("text_red") : "";
                  var floatB = parseFloat(data.variancebal);
                  (floatA < 0) ? $(".discrepancy_item").addClass("text_red") : "";
                  $('.stat > tbody > tr').each(function(index, value) {
                      (parseFloat($('td:eq(4)').html()) < 0) ? $('td:eq(4)').addClass("text_red") : "";
                      (parseFloat($('td:eq(7)').html()) < 0) ? $('td:eq(7)').addClass("text_red") : "";
                  });
                  checkForDividers();
              }
          });
      }

      $.ajax({
        async: false,
        url: url + "daily_inventory/dateTitle_pull",
        type:'post',
        dataType: 'json',
        data:{id: $('#dailyRecord').data('id')},
        success: function(data){
            var str = "";
            var str_b = '';
            str += "<tr>";
            str += "<th></th>";
            $.each(data.days, function (index, value) {
                str += "<th>"+value+"</th>";
            });
            str += "</tr>";
            $('#pulloutTable thead').html(str);

            $.each(data.insideParasite,function(index, value) {
                str_b += "<tr>";
                str_b += "<td>" +value.name+"</td>";
                $.each( value[0],function (index1, value1) {
                    if (parseInt(value1) > 0) {
                        str_b += "<td class='text_good'>" +value1+"</td>";
                    }else {
                        str_b += "<td>" +value1+"</td>";
                    }
                })
                str_b += "</tr>";
            })
            $('#pulloutTable tbody').html(str_b);
        }
      });

      $.ajax({
        async: false,
        url: url + "daily_inventory/dateTitle",
        type:'post',
        dataType: 'json',
        data:{id: $('#dailyRecord').data('id')},
        success: function(data){
            var str = "";
            var str_b = '';
            str += "<tr>";
            str += "<th></th>";
            $.each(data.days, function (index, value) {
                str += "<th>"+value+"</th>";
            });
            str += "</tr>";
            $('#salesTable thead').html(str);

            $.each(data.insideParasite,function(index, value) {
                str_b += "<tr>";
                str_b += "<td>" +value.name+"</td>";
                $.each( value[0],function (index1, value1) {
                    if (parseInt(value1) > 0) {
                        str_b += "<td class='text_good'>" +value1+"</td>";
                    }else {
                        str_b += "<td>" +value1+"</td>";
                    }
                })
                str_b += "</tr>";
            })
            $('#salesTable tbody').html(str_b);
        }
      });


      $('.phy_qty').on('keyup change', function() {
          var ending = 0;
          if ($('.physical_count').length) {
              ending = parseInt($(this).parents('tr').find('.physical_count').html());
          }else {
              ending = parseInt($(this).parents('tr').find('.endind_sys_item').html());
          }

          var end_sys = parseInt($(this).parents('tr').find('.ending_sys').html());
          var price = $(this).parents('tr').find('.price').val();
          var variance_item = $(this).val() - ending;
          var actual_amt = price * $(this).val();
          var actual_amt_bal = actual_amt - end_sys;

          if (variance_item < 0) {
              $(this).parents("tr").addClass("table_warning_error");
              $(this).parents("tr").find('.variance_item').addClass("text_red");
              $(this).parents("tr").find('.variance_bal').addClass("text_red");
          }else {
              $(this).parents("tr").removeClass("table_warning_error");
              $(this).parents("tr").find('.variance_item').removeClass("text_red");
              $(this).parents("tr").find('.variance_bal').removeClass("text_red");
          }

          if (variance_item > 0) {
              $(this).parents("tr").addClass("table_warning_success");
              $(this).parents("tr").find('.variance_item').addClass("text_good");
              $(this).parents("tr").find('.variance_bal').addClass("text_good");
          }else {
              $(this).parents("tr").removeClass("table_warning_success");
              $(this).parents("tr").find('.variance_item').removeClass("text_good");
              $(this).parents("tr").find('.variance_bal').removeClass("text_good");
          }

          $(this).parents('tr').find('.actual_ending_bal').html(actual_amt);
          $(this).parents('tr').find('.variance_item').html(variance_item);
          $(this).parents('tr').find('.variance_bal').html(actual_amt_bal);

          $(this).parents('tr').find('.physicalbal_in').val(actual_amt);
          $(this).parents('tr').find('.varianceitem_in').val(variance_item);
           $(this).parents('tr').find('.varianceBal').val(actual_amt_bal);
      });

      $(".phy_qty").each(function () {
          var ending = 0;
          if ($('.physical_count').length) {
              ending = parseInt($(this).parents('tr').find('.physical_count').html());
          }else {
              ending = parseInt($(this).parents('tr').find('.endind_sys_item').html());
          }

          var end_sys = parseInt($(this).parents('tr').find('.ending_sys').html());
          var price = $(this).parents('tr').find('.price').val();
          var variance_item = $(this).val() - ending;
          var actual_amt = price * $(this).val();
          var actual_amt_bal = actual_amt - end_sys;

          if (variance_item < 0) {
              $(this).parents("tr").addClass("table_warning_error");
              $(this).parents("tr").find('.variance_item').addClass("text_red");
              $(this).parents("tr").find('.variance_bal').addClass("text_red");
          }else {
              $(this).parents("tr").removeClass("table_warning_error");
              $(this).parents("tr").find('.variance_item').removeClass("text_red");
              $(this).parents("tr").find('.variance_bal').removeClass("text_red");
          }

          if (variance_item > 0) {
              $(this).parents("tr").addClass("table_warning_success");
              $(this).parents("tr").find('.variance_item').addClass("text_good");
              $(this).parents("tr").find('.variance_bal').addClass("text_good");
          }else {
              $(this).parents("tr").removeClass("table_warning_success");
              $(this).parents("tr").find('.variance_item').removeClass("text_good");
              $(this).parents("tr").find('.variance_bal').removeClass("text_good");
          }

          $(this).parents('tr').find('.actual_ending_bal').html(actual_amt);
          $(this).parents('tr').find('.variance_item').html(variance_item);
          $(this).parents('tr').find('.variance_bal').html(actual_amt_bal);
      })

      $(document).on('click', '.deleteRecords', function(e){
          e.preventDefault();

          Swal.fire({
              title: "Warning!",
              text: "Are you sure to delete this record?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#A4A23C",
              confirmButtonText: "Yes",
          }).then((confirm) => {
              if (confirm.value) {
                  $.ajax({
                      async: true,
                      url: url + 'daily_inventory/deleteRecord',
                      type: 'post',
                      dataType:'json',
                      data: {id: $(this).data('id')},
                      success: function(data){
                          showNotification(data);
                          setTimeout(function () {
                              window.location.reload();
                          }, 2000);
                          dailyDatable(url)
                      }
                  });
              }
          });
      });

      $(document).on('click', 'input[type="number"]', function(){
          if (parseInt($(this).val()) == 0) {
              $(this).val('');
          }
      })

      $('#form_rec_daily').on('submit', function(e) {
          e.preventDefault();

          Swal.fire({
              title: "Warning!",
              text: "Are you sure to save this record?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#A4A23C",
              confirmButtonText: "Yes",
          }).then((confirm) => {
              if (confirm.value) {
                  $.ajax({
                      async: true,
                      url: url + 'daily_inventory/recordDaily',
                      type: 'post',
                      dataType:'json',
                      data: $(this).serialize(),
                      beforeSend: function(){
                        $('.preloader').show();
                      },
                      success: function(data){
                          if (data.type == 'success') {
                              setTimeout(function () {
                                  window.location.href= url + "daily_inventory";
                              }, 2000);
                          }else {
                              showNotification(data);
                              $('.preloader').hide();
                          }
                      }
                  });
              }
          });

      });

      $('#verifyInventoryForm').on('submit', function(e){
          e.preventDefault();

          Swal.fire({
              title: "Warning!",
              text: "Are you sure to save this record?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#A4A23C",
              confirmButtonText: "Yes",
          }).then((confirm) => {
              if (confirm.value) {
                  $.ajax({
                      url: url + 'daily_inventory/verify_submit',
                      type: 'POST',
                      data: $(this).serialize(),
                      dataType: 'json',
                      beforeSend: function(){
                        $('.preloader').show();
                      },
                      success: function(res){
                          if (res.type == 'success') {
                              setTimeout(function () {
                                  window.location.href= url + "daily_inventory";
                              }, 2000);
                          }else {
                              showNotification(res);
                              $('.preloader').hide();
                          }
                      }
                  })
              }
          });
      })

      if ($('td.physical_count').length) {
          $('td.physical_count').each(function(){
              const phy_count = parseInt($(this).html()),
                    sys_end_count = parseInt($(this).siblings('.endind_sys_item').html());

              if (phy_count != sys_end_count) {
                  $(this).addClass('font-weight-bold');
              }
          })
      }

      if ($('#inventory_items').length) {
          getInventoryItems();
      }
});

$('.print').on('click', function() {
    $('#list_of_items').show();
    $('#salesDaily').show();
    $('#pullout').show();

    if ($('.status span').html() == 'UNVERIFIED') {
        $('.status span').prop('style', 'color:red');
    }else {
        $('.status span').prop('style', 'color:green');
    }

    $('.responsive-table').removeClass('responsive-table');

    setTimeout(function () {

        $('.print').hide();
        $('.ableprint').hide();
        $('.print_to').print({
            globalStyles: true,
            mediaPrint: false,
            stylesheet: null,
            noPrintSelector: ".no-print",
            iframe: false,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
        });
    }, 1000);


    setTimeout(function () {
        window.location.reload();
    }, 1900);
});

$(document).on('change keyup', '.actual_qty', function(){
    const system_count = parseInt($(this).parents('tr').find('.sys_count').html());

    const variance = $(this).val() - system_count;

    let rowClasses = {
        tr: '',
        td: '',
    };

    if (variance < 0) {
        rowClasses.tr = 'table_warning_error';
        rowClasses.td = 'text_red';
    }else if (variance > 0) {
        rowClasses.tr = 'table_warning_success';
        rowClasses.td = 'text_good';
    }

    $(this).parents('tr').attr('class', rowClasses.tr);
    $(this).parents('tr').find('.variance').attr('class', 'variance ' + rowClasses.td).html(variance);
});

function getInventoryItems(){
    const _id = $('#inventory_items').attr('data-id');

    $.ajax({
        url: url + 'daily_inventory/get_inventory_items',
        dataType: 'json',
        data: {daily_id: _id},
        type:'POST',
        success: function(res){
            $(res).each(function(key, item_info){
                console.log('item_info', item_info);
                let html = '';

                html += '<td class="">PRODUCT SAMPLE</td>';
                html += '<td class="">0</td>';
                html += '<td class="test">0</td>';
                html += '<td class="endind_sys_item">400</td>';
                html += '<td class="test">';
                html += '<input min="0" type="number" class="phy_qty" name="qty[]" value="0">';
                html += '<input min="0" type="hidden" class="" name="item_id[]" value="'+item_info.item_id+'">';
                html += '<input min="0" type="hidden" class="begbal" name="begbal[]" value="'+item_info.beg_bal+'">';
                html += '<input min="0" type="hidden" class=" " name="actualending_sys[]" value="">';
                html += '<input min="0" type="hidden" class="varianceitem_in" name="variance_item[]" value="0">';
                html += '<input min="0" type="hidden" class=" " name="endbal[]" value="480000">';
                html += '<input min="0" type="hidden" class="physicalbal_in" name="phybal[]" value="">';
                html += '<input min="0" type="hidden" class="varianceBal" name="variancebal[]" value="">';
                html += '<input min="0" type="hidden" class="price" name="" value="1200"></td>';
                html += '<td class="variance_item text_red">-400</td>';
                html += '<td class="ending_sys format-money">480000</td>';
                html += '<td class="actual_ending_bal">0</td>';
                html += '<td class="variance_bal text_red">-480000</td>';

                $('<tr/>', {
                   'html': html
               }).prependTo('#inventory_items tbody');
            })
        },
        complete: function(){
            $('#inventory_items .pre-loader').remove();
            $('.actual_qty').trigger('change');
        }
    })
}


function dailyDatable(url) {
    var control = 1;
  $('#dailyInventory').DataTable({
         "destroy"         : true,
         "processing"     : true,
         "serverSide"     : true,
         "order"          : [[0,'desc']],
         "columns"        :[
               {"data":"date"},
               {"data":"recorded_by", "render" : function(data) {
                   var str= '';
                        str += '<span style="text-transform:capitalize">';
                        str += data;
                        str += '</span>';

                   return str;
               }},
               {"data":"end_items"},
               {"data":"phy_items"},
               {"data":"variance_item"},
               {"data":"end_balance"},
               {"data":"phy_balance"},
               {"data":"verified", "render" : function(data) {
                 return '<span class="label label-'+(data == '1' ? 'success' : 'danger')+' label-rounded">' + (data == '1' ? 'VERIFIED' : 'UNVERIFIED') + '</span>';
               }},
               {"data":"phy_balance","render" : function(data,type,row) {
                   var str= '';
                        str +='<a href="'+url+'daily_inventory/viewRecord/'+row.daily_id+'"  class="viewRecords btn" style="background: transparent" data-toggle="tooltip" title="" data-original-title="View"><i class="fas fa-eye"></i></a>';
                        if (control == 1 && row.verified != "1") {
                                str +='<a href="" data-id='+row.daily_id+' class="deleteRecords btn" style="background: transparent" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fas fa-trash"></i></a>';
                                control = 2;
                        }
                   return str;
               }},
         ],

         "ajax": {
               "url"   : url+"daily_inventory/datatable",
               "type"  : "POST"
         },

         "columnDefs": [
               {
                    "targets"   : [8],
                    "orderable" : false,
                },
           ],
           "initComplete" : function () {
               $('[data-toggle="tooltip"]').tooltip()
           }
  });
}
