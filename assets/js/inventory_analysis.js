var url = $("#base_url").val();

$(document).ready(function() {
    fetchData();

    $('select[name="location"], .date-ranges').on('change', function(){
        fetchData();
    });
    // $.ajax({
    //     async: false,
    //     url: url + "inventory_analysis/checkAvg",
    //     type:'post',
    //     dataType:'json',
    //     data: {},
    //     success: function(data) {
    //         $('.slow_mov tbody').html(data.slow);
    //         $('.fast_mov tbody').html(data.fast);
    //     }
    // });
    $('.fromDate').datepicker({
        language: 'en',
        onSelect: function onSelect(fd, date) {
            var dated = new Date(date);
            var mnth = ("0" + (dated.getMonth() + 1)).slice(-2);
            var day =  ("0" + dated.getDate()).slice(-2);
            var whole = [dated.getFullYear(), mnth, day].join("/");
            toDate(whole);
            fetchData();
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
            fetchData();
        }
    });
}


function fetchData() {
    $.ajax({
        url: url + 'inventory_analysis/get_data',
        type: 'POST',
        dataType:'json',
        data: {
            location: $('select[name="location"]').val(),
            date_from : $('input[name="from_date"]').val(),
            date_to : $('input[name="to_date"]').val(),
        },
        beforeSend: function(){
            const loader = `<td colspan="100%" style="text-align:center">
                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
            </td>`;

            $('#topSellingItems').html(loader);
        },
        success:function(res){
            generateTableInput(res, '#topSellingItems');
        },
        complete: function(){
            $('#topSellingItems').DataTable({
                "destroy": true,
                "order" : [[ 2, "desc" ]],
                "paging" : false,
                "columnDefs": [ {
                    "orderable" : true,
                }]
            })
        }
    })
}
