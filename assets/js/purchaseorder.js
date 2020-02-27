$(document).ready(function() {
    var url = $("#base_url").val();
    var stock ="";
    purchaseOrder(url, "all");
    descTable(url, 3);
    $("button[data-target='#purchaseModal']").click(function() {
        $('#createPurchase')[0].reset();
        $('.type').val("add");
        $('.purchaseTB tbody tr').not(':last').remove();
        $('.dyna_btn').html('Create');
    });

    $('#incidentSector').on('submit', function(e) {
        e.preventDefault();

        var anomaly = 0;

        $(this).find("table tbody tr").not('.escapetr').each(function(index, value) {
            var t1 = parseInt($(value).find('td:eq(1)').html());
            var t2 = parseInt($(value).find('td:eq(2)').find('.qty_num').val());
            if( t1 !== t2 ){
                anomaly = anomaly+1;
            }
        });

        if (anomaly != 0) {
            Swal.mixin({
                confirmButtonText: 'Next &rarr;',
                showCancelButton: true,
                progressSteps: ['1', '2']
            }).queue([
                {
                    title: 'Warning!',
                    text: "It seems you have received short/excess items from the supplier.",
                    confirmButtonColor: "#A4A23C",
                    confirmButtonText: "Record Discrepancy",
                    cancelButtonColor: 'indianred',
                    cancelButtonText: 'I made a mistake',
                },
                {
                    title: 'Wait!',
                    text: "To record this discrepancy, please tell us what happened",
                    input: "textarea",
                    preConfirm: (res) => {
                        let proceed = true;

                        const msg ={
                            message: 'Please tell us the reason',
                            type: 'warning'
                        }

                        if (res == '') {
                            proceed = false;
                            showNotification(msg);
                        }else {
                            $('#incidentSector').prepend(
                                '<input type="hidden" name="reason" value="'+res+'" />'
                            );
                        }
                        return proceed;
                    }
                },
            ]).then((result) => {
                if (result.value) {
                    submitForm();
                }else {
                    $('#receiveModal').modal('show');
                }

                $('input[name="reason"]').remove();
            })
        }else {
            submitForm();
        }
    });

    function submitForm(){
        $.ajax({
            async:false,
            url: url + "purchaseorder/updateQty",
            type:'post',
            dataType:'json',
            data:$('#incidentSector').serialize(),
            beforeSend: function(){
                $('#incidentSector').find('button[type="submit"]').html('Please wait..').prop('disabled', true);
            },
            success: function(data) {
                if (data.type =='success') {
                    purchaseOrder(url, "all");
                    $('#anomalySector1').modal('hide');
                }
                showNotification(data);
                $('input[name="reason"]').remove();
            },
            complete: function(){
                $('#incidentSector').find('button[type="submit"]').html('Confirm').prop('disabled', false);
            }
        })
    }

    $(document).on('click', '.lister', function() {
        $(this).parent().parent().parent().find('.autocomplete').val($(this).data('name'));
        $(this).parent().parent().parent().find('.autocomplete_holder').val($(this).data('id'));
        var itemId = $(this).data('id');
        var total, price;
        $.ajax({
            async: false,
            url: url + "purchaseorder/getSelItem",
            type:'post',
            dataType:'json',
            data: {id: itemId},
            success: function(data) {
                total = data['total'];
                price = data['supplier_price'];
            }
        });

        if (total == null) {
            $(this).parents('tr').find(".stockAfter").attr('data-stock', 0);
            $(this).parents('tr').find(".stockAfter").html(0);
        }else {
            $(this).parents('tr').find(".stockAfter").attr('data-stock', total);
            $(this).parents('tr').find(".stockAfter").html(total);
        }
        $(this).parents('tr').find(".costProd").html(price);
    });

    $(document).on('click','.PO_addmore_sec1',function(e) {
        e.preventDefault();
        var str = "";
        str += "<tr>";
        str += "<td>";
        str +="<div class='autocomplete_drp'>";
              str +="<input required class='autocomplete form-control' type='text' placeholder='Type Barcode, SKU, Name'>";
              str +="<input class='autocomplete_holder' type='hidden' name='item_id[]'>";
              str +="<div class='autocomplete_drp-content'></div>";
        str +="</div>";
        str += "</td>";
        str += "<td class=''>0</td>";
        str += "<td><input required min='0' class='form-control qty_num' value='0' type='number' name='qty_rec[]'/></td>";
        str += "<td>&#8369;<span class='costProd'> 0.00</span> <input class='costper' name='costper[]' type='hidden'></td>";
        str += "<td>&#8369;<span class='totalPri'> 0.00</span> <input class='totPrice' name='totPrice[]' type='hidden'></td>";
        str += "<td><a class='delBut text-info'><i class='fas fa-trash'></i></a></td>";
        str += "</tr>";
        $(this).parents('tr').before(str);
    });

    $(document).on('click','.PO_addmore',function(e) {
        e.preventDefault();
        var str = "";
        str += "<tr>";
        str += "<td>";
        str +="<div class='autocomplete_drp'>";
              str +="<input required class='autocomplete form-control' type='text' placeholder='Type Barcode, SKU, Name'>";
              str +="<input class='autocomplete_holder' type='hidden' name='items[]'>";
              str +="<div class='autocomplete_drp-content'></div>";
        str +="</div>";
        str += "</td>";
        str += "<td><input required min='0' class='form-control qty_num' value='0' type='number' name='quantity[]'/></td>";
        str += "<td class='stockAfter'>0</td>";
        str += "<td>&#8369;<span class='costProd'> 0.00</span> <input class='costper' name='costper[]' type='hidden'></td>";
        str += "<td>&#8369;<span class='totalPri'> 0.00</span> <input class='totPrice' name='totPrice[]' type='hidden'></td>";
        str += "<td><a class='delBut text-info'><i class='fas fa-trash'></i></a></td>";
        str += "</tr>";
        $(this).parents('tr').before(str);
    });

    // $(document).on('change', '.rec_qty', function() {
    //     var toRecieve = parseInt($(this).parents("tr").find("td:eq(1)").text());
    //     if (toRecieve == $(this).val()) {
    //         alert("");
    //     }
    //     alert(toRecieve);
    // });

    $(document).on('click', '.autocomplete', function(){
        $(this).val('');
    })


    $(document).on('change', '.qty_num',function() {
        var qty = $(this).val();
        $(this).parent().parent().find('.stockAfter').html(parseInt($(this).parent().parent().find(".stockAfter").data('stock')) + parseInt(qty));
        $(this).parent().parent().find('.totalPri').html(parseInt($(this).parent().parent().find(".costProd").text()) * parseInt(qty));
        var costper = $(this).parent().parent().find('.costProd').text()
        var totalPrice = $(this).parent().parent().find('.totalPri').text();
        $(this).parent().parent().find('.totPrice').val(totalPrice);
        $(this).parent().parent().find('.costper').val(costper);
    });

    $('#createPurchase').on('submit', function(e) {
            e.preventDefault();
            var self = $(this);
            if ($("select[name='supplier']").val() != "Select Supplier") {
                var ajaxUrl ='';
                switch ($('.type').val()) {
                    case "edit":
                        ajaxUrl = url +"purchaseorder/editPurchase";
                        break;
                    case "add":
                        ajaxUrl = url +"purchaseorder/addPurchaseOrder";
                        break;
                    default:
                }
                $.ajax({
                    async: false,
                    url: ajaxUrl,
                    type:'post',
                    dataType:'json',
                    data: self.serialize() + "&poid=" + self.data('id'),
                    beforeSend: function(){
                        $(this).find('button[type="submit"]').html('Please wait...').prop('disabled', true);
                    },
                    success: function(data) {
                        if (data.type == 'success') {
                            $('#purchaseModal').modal('hide');
                            showNotification(data);
                            purchaseOrder(url, "all");
                        }else {
                            showNotification(data);
                        }
                    },
                    complete:function(){
                        $(this).find('button[type="submit"]').prop('disabled', false);
                    }
                });
            }else {
                var data = {
                    message : "Please fill all necessary fields",
                    type : "warning",
                }
                showNotification(data);
            }

    });

    $(document).on('click','.viewPurchase', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if ($(this).data('stat') == 0) {
            $('.editPurchase').show();
        }else {
            $('.editPurchase').hide();
        }
        $.ajax({
            async: false,
            url: url + "purchaseorder/viewPurchaseOrder",
            type: 'post',
            dataType: 'json',
            data: {id: id},
            success: function (data) {
                $('.amt').html(data.total_unit);
                $('.unit').html(data.total_amount);
                $('.supplier').html(data.supplier);
                $('.editPurchase').attr('data-id', data.poid);
                generateTable(data.table, "#viewTbl");
            }
        })
    });

    $('.status').on('change', function() {
        purchaseOrder(url,$(this).val());
    });

    $(document).on('click','.recPurchase', function(e) {
        e.preventDefault();

        $.ajax({
            async: false,
            url: url + "purchaseorder/returnSector1",
            type: 'post',
            dataType: 'json',
            data: {id: $(this).attr('data-id')},
            success: function (data) {
                $('.anomalySector1 tbody').html(data.string);
                $('.poid_anom').val(data.poid);
            }
        });
        $('.greetSector1').css("display","block");
    });

    $('.showInstruction').on('click', function(e) {
        e.preventDefault();
        $('.instruction').css('display',"block");
        $('.showInstruction').css('display',"none");
        $('.hideInstruction').css('display',"block");

    });
    $('.hideInstruction').on('click', function(e) {
        e.preventDefault();
        $('.showInstruction').css('display',"block");
        $('.hideInstruction').css('display',"none");
        $('.instruction').css('display',"none");
    });

    $('.rec_in').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            async: false,
            url: url + "purchaseorder/returnSector1",
            type: 'post',
            dataType: 'json',
            data: {id: $('.test').val()},
            success: function (data) {

                $('.anomalySector1 tbody').html(data.string);
                $('.poid_anom').val(data.poid);
                // $('.supp').val(data.supplier);
            }
        });
        $('#anomalySector1').modal('show');
        $('#receivePurchaseMod').modal('hide');
        $('.greetSector1').css("display","block");

    });

    $('#recieveForm').on('submit', function (e) {
        e.preventDefault();
        var self = $(this);
        var anomaly = 0;
        $("#recieveForm").find(".uni_rec > tbody > tr").each(function(index, value) {
            var t1 = parseInt($(value).find('td:eq(1)').html());
            var t2 = parseInt($(value).find('td:eq(2)').find('.rec_qty').val());
            if( t1 !== t2 ){
                anomaly = anomaly+1;
            }
        });
        if (anomaly != 0) {
            $('#receivePurchaseMod').modal('hide');
            $.ajax({
                async: false,
                url: url + "purchaseorder/returnSector1",
                type: 'post',
                dataType: 'json',
                data: {id: $('.test').val()},
                success: function (data) {

                    $('.anomalySector1 tbody').html(data.string);
                    $('.poid_anom').val(data.poid);
                    // $('.supp').val(data.supplier);
                }
            });
            $('#anomalySector1').modal('show');
            $('.greetSector1').css("display","block");
        }else {
            $.ajax({
                async: false,
                url: url + "purchaseorder/updateQty",
                type: 'post',
                dataType: 'json',
                data: self.serialize(),
                success: function (data) {
                    purchaseOrder(url, "all");

                    $('#receivePurchaseMod').modal('hide');

                    $.toast({
                        heading: "Added",
                        text: data.message,
                        position: 'top-right',
                        loaderBg: '#fff',
                        icon: "update",
                        hideAfter: 2000,
                        stack: 6
                    })
                }
            })
        }
    });

    $(document).on('click','.editPurchase', function() {
        $('#viewPurchaseMod').modal('hide');
        $('#createPurchase').removeAttr('data-type');
        $('.type').val("edit");
        // $('#underMaintenance').modal('show');
        $('#purchaseModal').modal('show');
        var id = $(this).data('id');
        $('.dyna_btn').html('Save Changes');
        $.ajax({
            async: false,
            url: url + "purchaseorder/editret",
            type: 'post',
            dataType: 'json',
            data: {id: id},
            success: function (data) {
                $('select[name="supplier"]').val(data.supplier);
                $('.purchaseTB tbody').html(data.string);
                $('#createPurchase').attr('data-id', data.poid);
            }
        })
    });

    $(document).on('click','.descModal', function(e) {
        e.preventDefault();
        var id = $(this).data('stat');
        $.ajax({
            async: false,
            url: url + "purchaseorder/getItemsPo",
            type:"post",
            dataType: "json",
            data: {id: id},
            beforeSend: function(){
                const loader = `<td colspan="100%" class="text-center">
                                    <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                </td>`;
                $('.ari_ibutang').html(loader);
            },
            success: function(data){
                var str = "";
                if (data.recieved_items.length != 0) {
                    var i = 0;
                    $.each(data.recieved_items, function(index, value) {
                        var expected = 0;
                        if (data.items.length > i) {
                            expected = data.items[i].qty;
                        }
                        str += "<tr>";
                        str += "<td>"+value.prod_name+"</td>";
                        str += "<td>"+ expected +"</td>";

                        if (parseInt(value.qty) != parseInt(expected)) {
                            str += "<td style='font-weight:bold; color:indianred'>"+ value.qty +"</td>";
                        }else {
                            str += "<td>"+ value.qty +"</td>";
                        }
                        str += "</tr>";
                        i++;
                    });
                }else {
                    str += "<tr>";
                    str += "<td colspan='3'>No Records Found</td>";
                    str += "</tr>";
                }
                // console.log(str);
                $('.ari_ibutang').html(str);
                $('.reason_for_disc').html(data.reason);
            }
        });
    });


});

function ajax_a_f_name(url, id) {
    var name ='';
    $.ajax({
        async: false,
        url: url + "purchaseorder/getName",
        type:"post",
        dataType: "json",
        data: {id: id},
        success: function(data){
            name = data;
        }
    })
    return name;
}

function purchaseOrder(url, status) {
    $('#purchaseOrder').DataTable({
        "destroy"        : true,
       "responsive"     : true,
       "processing"     : true,
       "serverSide"     : true,
       "order"          : [[0,'desc']],
       "columns"        :[
             {"data":"poid"},
             {"data":"supplier"},
             {"data":"total_qty"},
             {"data":"total_cost"},
             {"data":"last_updated"},
             {"data":"status","render": function(data, type, row) {
                 return getStatus(row.status);
             }},
             {"data":"status", "render" : function(data, type, row) {
                 var str = '<a class="viewPurchase" href="javascript:;" data-toggle="modal" data-stat="'+row.status+'" data-target="#viewPurchaseMod" style="margin-right: 12px;" data-id="'+row.poid+'"><i class="fas fa-eye" data-toggle="tooltip" title="View"></i></a>';

                 (row.status != 3) ? str += '<a class="recPurchase" href="" data-toggle="modal" data-target="#anomalySector1" data-id="'+row.poid+'"><i class="mdi mdi-inbox-arrow-down" data-toggle="tooltip" title="Receive"></i></a>' : str += '';
                 return str;
             }}
       ],
       "ajax": {
             "url"   : url+"purchaseorder/purchaseDataTable",
             "data"  : {status: status},
             "type"  : "POST"
       },
       "columnDefs": [
             {
                  "targets"   : [5, 6],

                  "orderable" : false,
              },
         ],
         "initComplete" : function () {
             $('[data-toggle="tooltip"]').tooltip()
         }
    });
}

function descTable(url, status) {
    $('#descItems').DataTable({
        "destroy"        : true,
       "responsive"     : true,
       "processing"     : true,
       "serverSide"     : true,
       "order"          : [[0,'desc']],
       "columns"        :[
             {"data":"poid"},
             {"data":"last_updated"},
             {"data":"name"},
             {"data":"received_by", "render" : function(data, type, row) {
                 var str = '<button type="button" class="descModal btn"  data-toggle="modal" data-stat="'+row.poid+'" data-target="#anomalySector2" style="margin-right: 12px;" data-id="'+row.poid+'"><i class="fas fa-eye" data-toggle="tooltip" title="View"></i></button>';
                 return str;
             }},
       ],
       "ajax": {
             "url"   : url+"purchaseorder/purchaseDataTable",
             "data"  : {status: status, with_discrepancy: true},
             "type"  : "POST"
       },
       "columnDefs": [
             {
                  "targets"   : [3],
                  "orderable" : false,
              },
         ],
         "initComplete" : function () {
             $('[data-toggle="tooltip"]').tooltip()
         }
    });
}


function getStatus(status){
    let html = '';

    switch (status) {
        case '0':
            html += '<span class="label label-inverse label-rounded">Pending</span>'
            break;
        case '1':
            html += '<span class="label label-info label-rounded">Approved</span>'
            break;
        case '2':
            html += '<span class="label label-warning label-rounded">For Delivery</span>'
            break;
        case '3':
            html += '<span class="label label-success label-rounded">Received</span>'
            break;
    }

    return html;
}
