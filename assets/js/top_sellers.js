var url = $("#base_url").val();

$(document).ready(function() {
    initDatatable(url);

    $('.select-date').datepicker({
        language: 'en',
        dateFormat: 'yyyy-mm-dd',
        onSelect: function onSelect(fd, date) {
            initDatatable();
        }
    });
});


function initDatatable() {
    var x = 1;
    var dataTable_top = $('#topsellers').DataTable({
           "destroy"        : true,
           "paging"         : false,
           "processing"     : true,
           "serverSide"     : true,
           "columns"        :[
                 {"data":"user_id","render":function (data, type, row, meta) {
                     var rank = meta.row + meta.settings._iDisplayStart + 1;
                     return "# " + rank;
                 }},
                 {"data":"fname", "render": function( data, type, row) {
                     return "<span style='text-transform: Capitalize;'>" + row.fname + " " + row.lname + "</span>";
                 }},
                 {"data":"total_sales","render" : function( data, type, row) {
                     var x = 0;
                     if (row.total_sales != null) {
                         x = "&#8369; " + row.total_sales;
                     }
                     return x;
                 }},
                 {"data":"total_items","render" : function( data, type, row) {
                     var x = 0;
                     if (row.total_items != null) {
                         x = row.total_items;
                     }
                     return x;
                 }},
           ],
           "ajax": {
                 "url"   : url + "top_sellers/topseller",
                 "type"  : "POST",
                 "data"  : {
                     start_date: $('input[name="start_date"]').val(),
                     end_date: $('input[name="end_date"]').val()
                 }
           },

           "columnDefs": [
                 {
                      "targets"   : [0,1,2,3],
                      "orderable" : false,
                  },
             ],
    });

    var y = 1;
    var dataTable_topLoc = $('#toplocation').DataTable({
           "destroy"        : true,
           "processing"     : true,
           "serverSide"     : true,
           "paging"         : false,
           "order"          : [[0,'desc']],
           "columns"        :[
                 {"data":"location_id", "render":function (data, type, row, meta) {
                     var rank = meta.row + meta.settings._iDisplayStart + 1;
                     return "# " + rank;
                 }},
                 {"data":"name"},
                 {"data":"total_sales","render" : function( data, type, row) {
                     var x = 0;
                     if (row.total_sales != null) {
                         x = "&#8369; " + row.total_sales;
                     }
                     return x;
                 }},
                 {"data":"total_items","render" : function( data, type, row) {
                     var x = 0;
                     if (row.total_items != null) {
                         x = row.total_items;
                     }
                     return x;
                 }},
           ],
           "ajax": {
                 "url"   : url+"top_sellers/toplocation",
                 "type"  : "POST",
                 "data"  : {
                     start_date: $('input[name="start_date"]').val(),
                     end_date: $('input[name="end_date"]').val()
                 }
           },

           "columnDefs": [
                 {
                      "targets"   : [0,1,2,3],
                      "orderable" : false,
                  },
             ],
    });
}
