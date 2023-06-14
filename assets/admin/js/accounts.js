$(document).ready(function() {
    $(document).on('change', '#is_parent', function(e) {
        if ($(this).val() == 1 || $(this).val() == null) {
            $(".parentDiv").hide();
        }
        else {
            $(".parentDiv").show();
        }
    });

    $(document).on('change', '#start_balance_status', function(e) {
        $("#start_balance").val('');
        if ($(this).val() == "") {
            $("#start_balance").val("");
        }
        else {
            if ($(this).val() == 3) {
                $("#start_balance").val(0);
            }
        }
    });

    $(document).on('input', '#start_balance', function(e) {
        var start_balance_status = $("#start_balance_status").val();
        if (start_balance_status == "") {
            alert("من فضلك اختر حالة الحساب اولا");
            $(this).val("");
            return false;
        }
        if ($(this).val() == 0 && start_balance_status != 3) {
            alert("يجب ادخال مبلغ اكبر من الصفر");
            $(this).val("");
            return false;
        }
    });

    $(document).on('change', '#account_type', function(e) {
        var is_internal = $("#account_type").children('option:selected').data('is_internal');
        if (is_internal == 1) {
            $(".parentDiv").show();
        }
        else if (is_internal == 0) {
            $(".parentDiv").hide();
        }
    });

});
