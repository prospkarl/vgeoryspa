const BASE_URL = $('#base_url').val();

$(document).ready(function(){
  $('select[name="type"]').val(0);
  $('select[name="location"]').trigger('change');
  initDatatable();

  if ($('#monthlyInventory').length) {
    getMonthlyInventories();
  }

  if ($('#viewMonthInventory').length) {
    viewMonthInventory();
  }
});

$(document).on('click mousedown', '.inventory-link', function(e){
  e.preventDefault();
  var newUrl = $(this).attr('href') + '/' + $(this).attr('data-location');
  window.location.href = newUrl;
})

$('select[name="type"]').on('change', function(){
  $('.toggle-hide').toggleClass('hidden');
  updateInventoriesLink();
})

$('select[name="location"]').on('change', function(){
  initDatatable();
  updateInventoriesLink();
})

$('select[name="year"]').on('change', function(){
  getMonthlyInventories();
})

$('.print').on('click', function() {
    var title = $('.tab-pane h6').html() + ' (' + $('.coverage').html() + ')';
    $('.responsive-table').removeClass('responsive-table');
    $('#salesViewRecord .card-body div').first().hide();
    $('.ableprint').hide();
    $('.tab-pane h6').html(title);

    setTimeout(function () {
        $('.print').hide();
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

// FUNCTIONS
function updateInventoriesLink() {
    $('.inventory-link').each(function(){
        $(this).attr('data-location', $('select[name="location"]').val());
    });
}

function viewMonthInventory() {
  $.ajax({
    url: BASE_URL + 'sales_inventory/get_month_data',
    type: 'POST',
    dataType:'json',
    data: {
      start_date: $('#viewMonthInventory').data('start'),
      end_date: $('#viewMonthInventory').data('end'),
      location: $('#viewMonthInventory').data('location'),
    },
    success: function(res){
        if (res.type == 'error') {
            showNotification(res);
            setTimeout(function () {
                window.history.back()
            }, 2000);
        }else {
            generateTable(res.table, '#viewMonthInventory table');
        }
    },
    complete: function(){
      $('.loading').html('');
    }
  })
}

function getMonthlyInventories() {
  $.ajax({
    url       : BASE_URL + 'sales_inventory/get_month_inventories',
    type      : 'POST',
    data      : {
      year: $('select[name="year"]').val()
    },
    success   : function(res){
      $('#monthlyInventory tbody').html(res);
    }
  })
}

function initDatatable() {
  $('#salesInventory').DataTable({
    "destroy"        : true,
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
              str +='<a href="'+BASE_URL+'daily_inventory/viewRecord/'+row.daily_id+'"  class="viewRecords btn" style="background: transparent" data-toggle="tooltip" title="" data-original-title="View"><i class="fas fa-eye"></i></a>';

         return str;
     }},
    ],

    "ajax": {
     "url"   : BASE_URL + "daily_inventory/datatable",
     "type"  : "POST",
     "data"  : {
       location_id: $('select[name="location"]').val()
     }
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
