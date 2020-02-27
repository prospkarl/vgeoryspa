const base_url = $("#base_url").val();

function handleClickReceive(transfer_id){
    $('input[name="transfer_id"]').val(transfer_id);

    $.ajax({
        async: false,
        url: base_url + "receive/returnSector1",
        type: 'post',
        dataType: 'json',
        data: {id: transfer_id},
        success: function (data) {
            $('.anomalySector1 tbody').html(data.string);
            $('.poid_anom').val(data.poid);
        }
    });
    $('#anomalySector1').modal('show');
    $('.greetSector1').css("display","block");
}

function handleClickView(transfer_id){
    $.ajax({
        url: base_url + 'receive/getItems',
        type: 'POST',
        dataType: 'json',
        data: {
            transfer_id: transfer_id
        },
        beforeSend: function(){
            const loader = `<td colspan="3" class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                              <span class="sr-only">Loading...</span>
                            </div>`;
            $('#viewTransfer tbody').html(loader);
        },
        success: function(res){
            if (res.type == 'success') {
                $('#viewTransfer tbody').html(res.data.html);
                $('#viewTransfer .transfer_by').html(res.data.transfer_by);
                $('#viewTransfer .date_created').html(res.data.date_created);
                $('#viewTransfer .remarks').html(res.data.remarks);
                $('#viewTransfer .toggle-log').attr('data-referrer', transfer_id);
            }else {
                showNotification(res);
            }
        }
    })
}

function initDatatable_a(){
    $('#descItems').DataTable({
        "destroy"        : true,
        "processing"     : true,
        "serverSide"     : true,
        "order"          : [[0,'desc']],
        "columns"        :[
            {"data":"transfer_id"},
            {"data":"transfer_by"},
            {"data":"date_added"},
            {"data":"action", "render":function(data, type, row){
                var html = '';
                var html = '<button type="button" class="descModal btn"  data-toggle="modal" data-stat="'+row.transfer_id+'" data-target="#anomalySector2" style="margin-right: 12px;" data-id="'+row.transfer_id+'"><i class="fas fa-eye" data-toggle="tooltip" title="View"></i></button>';
                return html;
            }},
        ],
        "ajax": {
            "url"   : base_url + "receive/receiveDatatable",
            "type"  : "POST",
            "data"  : {
                status: 1,
                with_discrepancy: true
            }
        },
        "columnDefs": [ {
            "targets"   : [3],
            "orderable" : false,
        },
    ],
});
}

function ajax_a_f_name(url, id) {
    var name ='';
    $.ajax({
        async: false,
        url: url + "receive/getName",
        type:"post",
        dataType: "json",
        data: {id: id},
        success: function(data){
            name = data;
        }
    })
    return name;
}

function initDatatable(){
    $('#transfers').DataTable({
        "destroy"        : true,
        "processing"     : true,
        "serverSide"     : true,
        "order"          : [[0,'desc']],
        "columns"        :[
            {"data":"transfer_id"},
            {"data":"transfer_by"},
            {"data":"date_added"},
            {"data":"action", "render":function(data, type, row){
                var html = '';

                if (row.status == 0) {
                    html = '<a href="javascript:;" data-toggle="modal" data-target="#receiveModal" class="btn btn-sm btn-rounded btn-outline-success" onclick="handleClickReceive('+row.transfer_id+');"><i class="mdi mdi-inbox-arrow-down"></i> Receive</a> ';
                }else {
                    html = '<a href="javascript:;" data-toggle="modal" data-target="#viewTransfer" class="btn btn-sm btn-rounded btn-outline-success" onclick="handleClickView('+row.transfer_id+');"><i class="fa fa-eye"></i> View</a> ';
                }

                return html
            }},
        ],
        "ajax": {
            "url"   : base_url + "receive/receiveDatatable",
            "type"  : "POST",
            "data"  : {
                status: $('select[name="status"]').val()
            }
        },
        "columnDefs": [ {
            "targets"   : [3],
            "orderable" : false,
        },
    ],
});
}

$(document).on('click', '.edit-row-btn', function(){
    $(this).parents('.edit-row').hide();
    $(this).parents('tr').find('.edit-form').show();
});

$(document).on('click', '.action-cancel', function(){
    $(this).parents('.edit-form').hide();
    $(this).parents('tr').find('.edit-row').show();
});

$(document).on('submit', '.edit-form', function(e){
    e.preventDefault();

    if ($(this).find('input[name="quantity"]').val() == $(this).parents('tr').find('.edit-row span').html()) {
        $('.action-cancel').trigger('click');
        return false;
    }

    $.ajax({
        url: base_url + 'receive/updateItems',
        type: 'POST',
        dataType:'json',
        data: $(this).serialize(),
        success : function(res){
            showNotification(res);
            $('.action-cancel').trigger('click');
            handleClickView(res.data);
        }
    })
})

$(document).ready(function(){
    initDatatable();
    initDatatable_a();

    $(document).on('click','.descModal', function(e) {
        e.preventDefault();
        var id = $(this).data('stat');
        $.ajax({
            async: false,
            url: base_url + "receive/getItemsPo",
            type:"post",
            dataType: "json",
            data: {id: id},
            success: function(data){
                // console.log(data);
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
                    str += "<td colspan='3' style='text-align:center'>No Records Found</td>";
                    str += "</tr>";
                }
                // console.log(str);
                $('.ari_ibutang').html(str);
                $('.reason_for_disc').html(data.reason);
            }
        });
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
        str += "<td><a class='delBut text-info'><i class='fas fa-trash'></i></a></td>";
        str += "</tr>";
        $(this).parents('tr').before(str);
    });

    $('#incidentSector').on('submit', function(e) {
        e.preventDefault();

        var anomaly = 0;

        $('.anomalySector1').find("tbody > tr").not('.escapetr').each(function(index, value) {
            var t1 = parseInt($(value).find('td:eq(1)').html());
            var t2 = parseInt($(value).find('td:eq(2)').find('.qty_num').val());

            if( t1 !== t2 ){
                anomaly = anomaly+1;
            }
        });

        if (anomaly) {
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
                    confirmButtonColor: "#A4A23C",
                    confirmButtonText: "Submit",
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


            // Swal.fire({
            //     title: "Warning!",
            //     text: "It seems you have received short/excess items.",
            //     type: "warning",
            //     showCancelButton: true,
            //     cancelButtonColor: 'indianred',
            //     cancelButtonText: 'I made a mistake',
            //     confirmButtonColor: "#A4A23C",
            //     confirmButtonText: "Record Discrepancy",
            // }).then((confirm) => {
            //     if (confirm.value) {
            //         submitForm();
            //     }else {
            //         $('#receiveModal').modal('show');
            //     }
            // });
        }else {
            submitForm();
        }
    });


    function submitForm(){
        $.ajax({
            async:false,
            url:base_url + "receive/receiveItems",
            type:'post',
            dataType:'json',
            data:$('#incidentSector').serialize(),
            beforeSend: function(){
                $('#incidentSector').find('button[type="submit"]').html('Please wait...').prop('disabled', 1);
            },
            success: function(data) {
                initDatatable();
                showNotification(data);
            },
            complete: function(){
                $('#incidentSector').find('button[type="submit"]').html('Confirm').prop('disabled', false);
            },
        })
    }

    $(document).on('click', '.lister', function() {
        $(this).parent().parent().parent().find('.autocomplete').val($(this).data('name'));
        $(this).parent().parent().parent().find('.autocomplete_holder').val($(this).data('id'));
        var itemId = $(this).data('id');
        var total, price;
        $.ajax({
            async: false,
            url: base_url + "purchaseorder/getSelItem",
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

    $('#receiveModal form').on('submit', function(e){
        e.preventDefault();
        const self = $(this);

        var anomaly = 0;

        $("#receiveModal").find("table > tbody > tr").each(function(index, value) {
            var t1 = parseInt($(value).find('td:eq(1)').html());
            var t2 = parseInt($(value).find('td:eq(2)').find('.rec_qty').val());
            if( t1 !== t2 ){
                anomaly = anomaly+1;
            }
        });

        $('#receiveModal').modal('hide');
        if (anomaly != 0) {
            $.ajax({
                async: false,
                url: base_url + "receive/returnSector1",
                type: 'post',
                dataType: 'json',
                data: {id: $('.test').val()},
                success: function (data) {
                    $('.anomalySector1 tbody').html(data.string);
                    $('.poid_anom').val(data.poid);
                }
            });
            $('#anomalySector1').modal('show');
            $('.greetSector1').css("display","block");
        }else {
            $.ajax({
                url: self.attr('action'),
                type: 'POST',
                dataType: 'json',
                data: self.serialize(),
                success: function(res){
                    showNotification(res);
                    initDatatable();
                    $('#receiveModal').modal('hide');
                }
            })
        }
    });

    $('select[name="status"]').on('change', function(){
        initDatatable();
    })
});
