const BASE_URL = $('#base_url').val();
const spinner_loader = `<td colspan="100%" style="text-align:center"> <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"> <span class="sr-only">Loading...</span> </div> </td>`;

const DiscrepancyRow = (data) => {
    let html;

    return data.map(info => {
        return `
            <tr>
                <td>` + info.prod_name + `</td>
                <td>` + (info.requested_qty != null ? info.requested_qty : 0) + `</td>
                <td class="text_red">` + info.qty + `</td>
            </tr>
        `;
    })
}

$(document).ready(function() {
    getData();

    $('.fromDate').datepicker({
        language: 'en',
        onSelect: function onSelect(fd, date) {
            var dated = new Date(date);
            var mnth = ("0" + (dated.getMonth() + 1)).slice(-2);
            var day =  ("0" + dated.getDate()).slice(-2);
            var whole = [dated.getFullYear(), mnth, day].join("/");
            toDate(whole);
            getData();
        }
    });
});

// functions
function toDate(date) {
    $('.toDate').datepicker({
        language: 'en',
        minDate: new Date(date),
        onSelect: function onSelect(fd, date) {
            var dated = new Date(date);
            var mnth = ("0" + (dated.getMonth() + 1)).slice(-2);
            var day = ("0" + dated.getDate()).slice(-2);
            var whole = [mnth, day, dated.getFullYear()].join("/");
            getData();
        }
    });
}

function getData() {
    const data = {
        status      : $('select[name="status"]').val(),
        from_date   : $('input[name="from_date"]').val(),
        to_date     : $('input[name="to_date"]').val()
    };

    $.ajax({
        url         : BASE_URL + 'discrepancy/getDiscrepancies',
        type        : 'POST',
        data        : data,
        dataType    : 'json',
        beforeSend  : function(){
            $('#discrepancy_table').find('tbody').html(spinner_loader);
        },
        success     : function(res){
            generateTableInput(res, '#discrepancy_table');
        },
        complete    : function(){
            $('#discrepancy_table').DataTable({
                destroy: true,
                order:      [[3, 'DESC']],
                columnDefs: [
                        {
                        "targets"   : [5],
                        "orderable" : false,
                    },
              ],
            })
        }
    })
}

$('select[name="status"]').on('change', function(){
    getData();
});

$(document).on('click', '.view-discrepancy', function(){
    const disc_id = $(this).attr('data-discrepancy_id');

    $.ajax({
        url         : BASE_URL + 'discrepancy/getDiscrepancyItems',
        type        : 'POST',
        dataType    : 'json',
        data        : {
            disc_id : disc_id
        },
        beforeSend  : function(){
            $('#discrepancy_modal').find('tbody').html(spinner_loader);
        },
        success     : function(res){
            $('#discrepancy_modal').find('tbody').html(DiscrepancyRow(res.discrepancy_items));
            $('#discrepancy_modal').find('.reason_for_disc').html(res.reason);
        },
    })
})
