$(document).ready(function() {

    $(document).on('change', '#customer_code_add', function() {
        var customer_pills_route = $('#ajax_get_customer_pills_route').val();
        var customer_code = $('#customer_code_add').val();
        var ajax_token = $('#ajax_token').val();

        if (customer_code != '') {
            jQuery.ajax({
                url:customer_pills_route,
                type:'post',
                datatype:'html',
                cache:false,
                data:{
                    customer_code:customer_code,
                    '_token':ajax_token
                    },
                // If the form and everything okay
                success:function(data){
                    $('#customer_pills_div').html(data);
                },
                // If the there is an error
                error:function() {
                    alert('error');
                }
            });
        }
        else {
            $('#customer_pills_div').hide();
        }
    });

    $(document).on('change', '#customer_pills_add', function() {
        var pill_details_route = $('#ajax_get_pill_details_route').val();
        var pill_code = $(this).val();
        var ajax_token = $('#ajax_token').val();

        if (pill_code != '') {
            jQuery.ajax({
                url:pill_details_route,
                type:'post',
                datatype:'html',
                cache:false,
                data:{
                    pill_code:pill_code,
                    '_token':ajax_token
                    },
                // If the form and everything okay
                success:function(data){
                    $('#pill_details_div').html(data);
                },
                // If the there is an error
                error:function() {
                    alert('error');
                }
            });
        }
        else {
            $('#pill_details_div').hide();
        }
    });

    $(document).on('input', '#pill_code_add', function() {
        var pill_details_route = $('#ajax_get_pill_details_route').val();
        var pill_code = $(this).val();
        var ajax_token = $('#ajax_token').val();

        if (pill_code != '') {
            jQuery.ajax({
                url:pill_details_route,
                type:'post',
                datatype:'html',
                cache:false,
                data:{
                    pill_code:pill_code,
                    '_token':ajax_token
                    },
                // If the form and everything okay
                success:function(data){
                    $('#pill_details_div').html(data);
                    $('#pill_details_div').show();
                },
                // If the there is an error
                error:function() {
                    alert('لا يوجد بيانات كهذه');
                }
            });
        }
        else {
            $('#pill_details_div').hide();
        }
    });

    $(document).on('input', '.rejected_quantity', function() {
        var rejected = $(this).val();
        rejected = parseFloat(rejected);
        if (rejected == '') rejected = 0;

        var remain_quantity = $(this).data('remain_quantity');
        remain_quantity = parseFloat(remain_quantity);
        if (remain_quantity == '') remain_quantity = 0;

        if (rejected > remain_quantity) {
            alert('يجب ان لا تكون الكمية المرتجعة اكبر من الكمية المتبقية');
            $(this).val('0');
            var close_total_price = $(this).closest('tr').find('.total_price').val();
            if (close_total_price != 0) {
                $(this).closest('tr').find('.total_price').val('0');
            }
            var current_total_pill = $("#total_pill").val();
            if (current_total_pill != 0) {
                close_total_price = parseFloat(close_total_price);
                current_total_pill = parseFloat(current_total_pill);
                $("#total_pill").val(current_total_pill - close_total_price);
            }

            var total_pill = $("#total_pill").val();
            if ($("#pill-type").val() == 1) {
                $("#what-paid").val(total_pill);
            }
            else if ($("#pill-type").val() == 2) {
                var what_paid = $("#what-paid").val();
                what_paid = parseFloat(what_paid);
                if(total_pill == '') total_pill = 0;
                if(what_paid == '') what_paid = 0;

                $("#what-remain").val(total_pill - what_paid);
            }
            return false;
        }

        var unit_price = $(this).data("unit_price");
        unit_price = parseFloat(unit_price);
        if (unit_price == '') unit_price = 0;


        var tax_percent = $("#tax_percent").val();
        tax_percent = parseFloat(tax_percent);
        if (tax_percent == '') tax_percent = 0;

        var discount_percent = $("#discount_percent").val();
        if (discount_percent == '') discount_percent = 0;

        var total_price = unit_price * rejected;
        total_price += total_price * tax_percent / 100;
        total_price -= total_price * discount_percent;

        $(this).closest('tr').find('.total_price').val(Math.round(total_price, 2));

        total_pill = 0;
        $('.total_price').each(function () {
            total_pill += parseFloat($(this).val());
        })

        $("#total_pill").val(total_pill);

        if ($("#pill-type").val() == 1) {
            $("#what-paid").val(total_pill);
        }
        else if ($("#pill-type").val() == 2) {
            var what_paid = $("#what-paid").val();
            what_paid = parseFloat(what_paid);
            if(total_pill == '') total_pill = 0;
            if(what_paid == '') what_paid = 0;

            $("#what-remain").val(total_pill - what_paid);
        }
    });


    $(document).on('click', '#approve_pill', function() {
        var flag = false;
        $('.rejected_quantity').each(function() {
            if ($(this).val() != 0 && $(this).val() != '') {
                flag = true;
            }
        })
        if (!flag) {
            alert('من فضلك ادخل الكمية المرتجعه ولو لصنف واحد');
            return false;
        }

        var flag = false;
        $('.total_price').each(function() {
            if ($(this).val() != 0 && $(this).val() != '') {
                flag = true;
            }
        })
        if (!flag) {
            alert('من فضلك ادخل الاجمالي ');
            return false;
        }

        if ($('#total_pill').val() == '' || $('#total_pill').val() == 0) {
            alert('من فضلك ادخل اجمالي الكمية المرتجعه ');
            return false;
        }



        if ($("#pill-type").val() == "all") {
            alert('من فضلك اختر نوع الفاتورة');
            $("#pill-type").focus();
            return false;
        }
        else {
            if ($("#what-paid").val() == '') {
                alert('من فضلك ادخل المبلغ المستلم');
                $("#what-paid").focus();
                return false;
            }
            if ($("#what-remain").val() == '') {
                alert('من فضلك ادخل المبلغ المتبقي');
                $("#what-remain").focus();
                return false;
            }
        }

        if ($("#treasuries-id").val() == '') {
            alert('من فضلك ادخل اسم الخزنة');
            $("#treasuries-id").focus();
            return false;
        }

        var what_paid = $('#what-paid').val();
        if (what_paid == '') what_paid = 0;
        what_paid = parseFloat(what_paid);

        var treasury_money = $('#treasury-money').val();
        if (treasury_money == '') treasury_money = 0;
        treasury_money = parseFloat(treasury_money);
        if (what_paid > treasury_money) {
            alert('عفوا ليس لديك رصيد كافي في الخزنة');
            $("#what-paid").focus();
            return false;
        }

        if ($("#shift-code").val() == '') {
            alert('من فضلك ادخل كود الشيفت');
            $("#shift-code").focus();
            return false;
        }

        if ($("#treasury-money").val() == '') {
            alert('من فضلك ادخل المبلغ الاجمالي في الخزنة');
            $("#treasury-money").focus();
            return false;
        }
    });

    $(document).on('change', '#pill-type', function() {
        $('#what-remain-div').show();
        $('#what-paid-div').show();

        if ($('#pill-type').val() == 1) {
            var total_pill = $("#total_pill").val();
            $('#what-paid').val(total_pill);
            $('#what-paid').prop('readonly', true);;
            $('#what-remain').val(0);
        }
        else if ($('#pill-type').val() == 2) {
            var total_pill = $("#total_pill").val();
            $('#what-remain').val(total_pill);
            $('#what-paid').prop('readonly', false);;
            $('#what-paid').val(0);
        }
        else {
            $('#what-remain-div').hide();
            $('#what-paid-div').hide();
        }
    });

    $(document).on('input', '#what-paid', function() {
        var total_pill = $("#total_pill").val();
        var what_paid = $(this).val();
        if(total_pill == '') total_pill = 0;
        if(what_paid == "") {
            what_paid = 0
        };
        total_pill = parseFloat(total_pill);
        what_paid = parseFloat(what_paid);

        if(what_paid >= total_pill) {
            alert('لا يمكن ان يكون المبلغ المدفوع اكبر او يساوي من المبلغ الاجمالي');
            $("#what-paid").val(0);
            $("#what-remain").val(total_pill);
        }
        else {
            $("#what-remain").val(total_pill - what_paid);
        }

    });

    $(document).on('input', '#what-remain', function() {
        var total_pill = $("#total_pill").val();
        var what_remain = $(this).val();
        what_remain = parseFloat(what_remain);
        total_pill = parseFloat(total_pill);
        if(total_pill == '') total_pill = 0;
        if(what_remain == '') what_remain = 0;

        $("#what-paid").val(total_pill - what_remain);
    });


    // ajax search
    function make_search() {
        // get the value from the input to search by
        var pill_code_search = $('#pill_code').val();
        var customer_code_search = $('#customer_code_search').val();
        var delegate_code_search = $('#delegate_code_search').val();
        var from_date_search = $('#from_date_search').val();
        var to_date_search = $('#to_date_search').val();
        var ajax_search_route = $('#ajax_search_route').val();
        var ajax_token = $('#ajax_token').val();

        jQuery.ajax({
            // first argument is the where the from route to
            url:ajax_search_route,
            // second argument is sending type of the form
            type:'post',
            // third argument is the type of the returned data from the model
            datatype:'html',
            // first argument is
            cache:false,
            // forth we send the search data and the token
            data:{
                pill_code_search:pill_code_search,
                customer_code_search:customer_code_search,
                delegate_code_search:delegate_code_search,
                from_date_search:from_date_search,
                to_date_search:to_date_search,
                '_token':ajax_token
            },
            // If the form and everything okay
            success:function(data){
                $('#ajax_search_result').html(data);
            },
            // If the there is an error
            error:function() {
                alert('حدث خطا');
            }
        });
    }

    $(document).on('input', '#pill_code', function() {
        make_search();
    });

    $(document).on('change', '#customer_code_search', function() {
        make_search();
    });
    $(document).on('change', '#delegate_code_search', function() {
        make_search();
    });
    $(document).on('input', '#from_date_search', function() {
        make_search();
    });
    $(document).on('input', '#to_date_search', function() {
        make_search();
    });
});

