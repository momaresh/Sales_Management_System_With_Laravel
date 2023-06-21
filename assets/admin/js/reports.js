$(document).ready(function() {
    $(document).on('change', '#report_type', function(e) {
        if ($(this).val() == 1) {
            $(".related_date").hide();
        }
        else {
            $(".related_date").show();
        }
    });

    $(document).on('change', '#code', function(e) {
        var from_date = $('#code option:selected').data('date');
        var current = $("#current_date").val();
        if ($(this).val() == "") {
            $("#from_date").val("");
            $("#to_date").val("");
        }
        else {
            $("#from_date").val(from_date);
            $("#to_date").val(current);
        }
    });

    $(document).on('click', '#report_btn', function(e) {

        var code = $('#code').val();
        if (code == "") {
            alert('من فضلك اختر صاحب الحساب');
            $("#code").focus();
            return false;
        }

        var report_type = $('#report_type').val();
        if (report_type == "") {
            alert('من فضلك اختر نوع التقرير');
            $("#code").focus();
            return false;
        }

        if (report_type != 1){
            var from_date = $('#from_date').val();
            if (from_date == "") {
                alert('من فضلك اختر تاريخ بداية التقرير');
                $("#code").focus();
                return false;
            }

            var to_date = $('#to_date').val();
            if (to_date == "") {
                alert('من فضلك اختر تاريخ نهاية التقرير');
                $("#code").focus();
                return false;
            }
        }
    });
});
