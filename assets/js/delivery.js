var url = $("#base_url").val();

$(document).on('change', '.status, .date-ranges', function() {
    deliveryData(url);
});

$(document).ready(function() {
    $('.status').trigger('change');

    $('.fromDate').datepicker({
        language: 'en',
        onSelect: function onSelect(fd, date) {
            var dated = new Date(date);
            var mnth = ("0" + (dated.getMonth() + 1)).slice(-2);
            var day =  ("0" + dated.getDate()).slice(-2);
            var whole = [dated.getFullYear(), mnth, day].join("/");
            toDate(whole);
            deliveryData(url);
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
            deliveryData(url);
        }
    });
}
