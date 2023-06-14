$(document).ready(function() {


    // عندما يتم تغير الصنف يتم تحميل الوحدات الذي يحتوي عليها واضهارها
    $(document).on('change', '#item_code_add', function(e) {
        var item_code = $(this).val();
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_get_item_unit_route').val();

        if (item_code != "") {
            jQuery.ajax({
                url: search_url,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    item_code:item_code,
                    '_token':search_token
                },
                success: function(data) {
                    $("#unit_add").html(data);
                    $('.relatied_item_card').show();

                    var type = $('#item_code_add').children('option:selected').data('type');
                    if(type == 2) {
                        $('.relatied_date').show();
                    }
                    else {
                        $('.relatied_date').hide();
                    }
                },
                error: function() {
                    $("#unit_add").html("");
                    $('.relatied_item_card').hide();
                    $('.relatied_date').hide();
                    alert("حدث خطا ما");
                }
            });
        }
        else {
            $("#unit_add").html("");
            $('.relatied_item_card').hide();
            $('.relatied_date').hide();
        }
    });

    // هذا يقوم بعملية حفظ البيانات تلقائيا باستخدام الاجاكس ويقوم بعمل تحقق من ان جميع البيانات الازمة مدخلة
    $(document).on('click', '#add_to_pill', function() {
        if ($('#item_code_add').val() == '') {
            alert('من فضلك اختر الصنف');
            $('#item_code_add').focus();
            return false;
        }
        if ($('#unit_id_add').val() == '') {
            alert('من فضلك اختر الوحدة');
            $('#unit_id_add').focus();
            return false;
        }
        if ($('#quantity_add').val() == '' || $('#quantity_add').val() == 0) {
            alert('من فضلك ادخل الكمية');
            $('#quantity_add').focus();
            return false;
        }
        if ($('#unit_price_add').val() == '') {
            alert('من فضلك ادخل سعر الوحدة');
            $('#unit_price_add').focus();
            return false;
        }
        var type = $('#item_code_add').children('option:selected').data('type');
        if (type == 2) {
            if ($('#production_date_add').val() == '') {
                alert('من فضلك ادخل تايخ الانتاج');
                $('#production_date_add').focus();
                return false;
            }
            if ($('#expire_date_add').val() == '') {
                alert('من فضلك ادخل تايخ الانتهاء');
                $('#expire_date_add').focus();
                return false;
            }
        }

        var purchase_auto_serial = $('#ajax_purchase_auto_serial').val();
        var search_token = $('#token_search').val();
        var item_code = $('#item_code_add').val();
        var unit_id = $('#unit_id_add').val();
        var quantity = $('#quantity_add').val();
        var unit_price = $('#unit_price_add').val();
        var total_price = $('#total_price_add').val();
        var production_date = $('#production_date_add').val();
        var expire_date = $('#expire_date_add').val();
        var search_url = $('#ajax_add_new_item_route').val();
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'json',
            cache: false,
            data: {
                purchase_auto_serial:purchase_auto_serial,
                item_code:item_code,
                unit_id:unit_id,
                quantity:quantity,
                unit_price:unit_price,
                total_price:total_price,
                production_date:production_date,
                expire_date:expire_date,
                '_token':search_token,
            },
            success: function(data) {
            },
            error: function() {
                alert("تم الاضافة");
                reload_items();
                reload_total_price();
            }
        });
    });

    // يقوم بعملية اضهار مودل التعديل في نفس الصفحة باستخدام الاجاكس
    $(document).on('click', '.edit_item_button', function() {
        var purchase_auto_serial = $('#ajax_purchase_auto_serial').val();
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_edit_item_route').val();
        var purchase_order_detail_id = $(this).data("purchase_order_detail_id");
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                purchase_auto_serial:purchase_auto_serial,
                purchase_order_detail_id:purchase_order_detail_id,
                '_token':search_token,
            },
            success: function(data) {
                $('#edit-item-result').html(data);
                $('#edit-item').modal('show');
            },
            error: function() {
                alert('حدث خطأ');
            }
        });
    });

    // يقوم بعملية اضهار مودل اضافة صنف في نفس الصفحة باستخدام الاجاكس
    $('#create_item_button').on('click', function() {
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_create_item_route').val();
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                '_token':search_token,
            },
            success: function(data) {
                $('#create_item_result').html(data);
                $('#create_item').modal('show');
            },
            error: function() {
                alert();
            }
        });
    });


    // يقوم بعملية اضهار مودل اضافة صنف في نفس الصفحة باستخدام الاجاكس
    $('#approve_pill_button').on('click', function() {
        var auto_serial = $('#ajax_purchase_auto_serial').val();
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_load_modal_approved_route').val();
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                auto_serial:auto_serial,
                '_token':search_token,
            },
            success: function(data) {
                $('#approve_pill_result').html(data);
                $('#approve_pill').modal('show');
            },
            error: function() {
                alert('انت لا تمتلك شفت حالياً');
            }
        });
    });

    // هذا يقوم بعملية التعديل والحفظ للبيانات تلقائيا باستخدام الاجاكس ويقوم بعمل تحقق من ان جميع البيانات الازمة مدخلة
    $(document).on('click', '#update_item', function() {
        if ($('#item_code_add').val() == '') {
            alert('من فضلك اختر الصنف');
            $('#item_code_add').focus();
            return false;
        }
        if ($('#unit_id_add').val() == '') {
            alert('من فضلك اختر الوحدة');
            $('#unit_id_add').focus();
            return false;
        }
        if ($('#quantity_add').val() == '' || $('#quantity_add').val() == 0) {
            alert('من فضلك ادخل الكمية');
            $('#quantity_add').focus();
            return false;
        }
        if ($('#unit_price_add').val() == '') {
            alert('من فضلك ادخل سعر الوحدة');
            $('#unit_price_add').focus();
            return false;
        }
        var type = $('#item_code_add').children('option:selected').data('type');
        if (type == 2) {
            if ($('#production_date_add').val() == '') {
                alert('من فضلك ادخل تايخ الانتاج');
                $('#production_date_add').focus();
                return false;
            }
            if ($('#expire_date_add').val() == '') {
                alert('من فضلك ادخل تايخ الانتهاء');
                $('#expire_date_add').focus();
                return false;
            }
        }

        var purchase_auto_serial = $('#ajax_purchase_auto_serial').val();
        var purchase_order_detail_id = $('#ajax_purchase_order_detail_id').val();
        var search_token = $('#token_search').val();
        var item_code = $('#item_code_add').val();
        var unit_id = $('#unit_id_add').val();
        var quantity = $('#quantity_add').val();
        var unit_price = $('#unit_price_add').val();
        var total_price = $('#total_price_add').val();
        var production_date = $('#production_date_add').val();
        var expire_date = $('#expire_date_add').val();
        var search_url = $('#ajax_update_item_route').val();
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                purchase_auto_serial:purchase_auto_serial,
                purchase_order_detail_id:purchase_order_detail_id,
                item_code:item_code,
                unit_id:unit_id,
                quantity:quantity,
                unit_price:unit_price,
                total_price:total_price,
                production_date:production_date,
                expire_date:expire_date,
                '_token':search_token,
            },
            success: function(data) {
                alert("تم التعديل");
                reload_items();
                reload_total_price();
            },
            error: function() {

            }
        });
    });

    /////////////////////////////////////////////////////////////////////////////

    // تقوم بعملية تحديث للاصناف في كل عملية اضافة وتعديل بدون الحاجة للتحديث
    function reload_items() {
        var purchase_auto_serial = $('#ajax_purchase_auto_serial').val();
        var search_token = $('#token_search').val();
        var reload_items_url = $('#ajax_reload_items_route').val();
        jQuery.ajax({
            url:reload_items_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                purchase_auto_serial:purchase_auto_serial,
                '_token':search_token,
            },
            success: function(data) {
                $('#reload_items_result').html(data);
            },
            error: function() {

            }
        });
    }

    // تقوم بعملية تحديث لاجمالي سعر الفاتورة في كل عملية اضافة وتعديل بدون الحاجة للتحديث
    function reload_total_price() {
        var purchase_auto_serial = $('#ajax_purchase_auto_serial').val();
        var search_token = $('#token_search').val();
        var reload_items_url = $('#ajax_reload_total_price_route').val();
        jQuery.ajax({
            url:reload_items_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                purchase_auto_serial:purchase_auto_serial,
                '_token':search_token,
            },
            success: function(data) {
                $('#reload_total_price_result').text(data);
            },
            error: function() {

            }
        });
    }

    ////////////////////////////////////////////////////////////////////

    $(document).on('input', '#quantity_add', function() {
        calculate_total_price();
    });

    $(document).on('input', '#unit_price_add', function() {
        calculate_total_price();
    });

    $(document).on('input', '#production_date_add', function() {
        if (!valid_date()) {
            alert('من فضلك تاريخ الانتاج اقل من تاريخ الانتهاء');
            $('#production_date_add').val('');
            return false;
        }
    });

    $(document).on('input', '#expire_date_add', function() {
        if (!valid_date()) {
            alert('من فضلك تاريخ الانتهاء اكبر من تاريخ الانتاج');
            $('#expire_date_add').val('');
            return false;
        }
    });

    function calculate_total_price() {
        var quantity = $('#quantity_add').val();
        if (quantity == '') quantity = 0;
        var unit_price = $('#unit_price_add').val();
        if (unit_price == '') unit_price = 0;

        $('#total_price_add').val(parseFloat(quantity) * parseFloat(unit_price));
    }

    function valid_date() {
        var production_date = $('#production_date_add').val();
        var expire_date = $('#expire_date_add').val();

        if (production_date == '')  production_date = new Date(2000, 10, 10);
        if (expire_date == '')  expire_date = new Date(3000, 10, 10);

        if (Date.parse(production_date) < Date.parse(expire_date)) return true;
        else return false;
    }

    ///////////////////////////////////////////////////////////////////////

    $(document).on('input', '#tax-percent', function() {
        if ($(this).val() > 100) {
            alert('لا يمكن ان يكون نسبة الضريبة اكبر من مئة');
            $(this).val('');
            calc_total_cost();
            return;
        }
        else {
            calc_total_cost();
        }
    });

    $(document).on('change', '#discount-type', function() {
        var type = $(this).val();
        if (type == 1) {
            $('#discount-value').val("");
            calc_total_cost();
            $('.discount_percent').show();
            $('.discount_value').hide();
        }
        else if (type == 2) {
            $('#discount-percent').val("");
            calc_total_cost();
            $('.discount_percent').hide();
            $('.discount_value').show();
        }
        else {
            $('.discount_percent').hide();
            $('.discount_value').hide();
        }

    });

    $(document).on('input', '#discount-percent', function() {
        if ($(this).val() > 100) {
            alert('لا يمكن ان يكون نسبة الخصم اكبر من مئة');
            $(this).val('');
            calc_total_cost();
            return;
        }
        else {
            calc_total_cost();
        }
    });

    $(document).on('input', '#discount-value', function() {
        var total = $('#total-before-discount').val();
        total = parseFloat(total);
        if ($(this).val() > total) {
            alert('لا يمكن ان يكون قيمة الخصم اكبر من سعر المنتجات');
            $(this).val('');
            calc_total_cost();
            return;
        }
        else {
            calc_total_cost();
        }
    });

    // يقوم بحسبة المبلغ الاجمالي والمبلغ بعد الضريبة والمتبقي وغيرها
    function calc_total_cost() {
        var total_before_discount = $('#total-before-discount').val();
        if (total_before_discount == '') {
            total_before_discount = 0;
        }
        total_before_discount = parseFloat(total_before_discount);

        var tax_percent = $('#tax-percent').val();
        if (tax_percent == '') {
            tax_percent = 0;
        }
        tax_percent = parseFloat(tax_percent);

        var tax_value = total_before_discount * (tax_percent / 100);
        $('#tax-value').val(tax_value);
        total_after_tax = total_before_discount + tax_value;
        $('#total-after-tax').val(total_after_tax);

        var discount_value = 0;
        var discount_type = $('#discount-type').val();

        if (discount_type == '') {
            discount_value = 0;
        }
        else if (discount_type == 1) {
            var discount_percent = $('#discount-percent').val();;
            if (discount_percent == '') {
                discount_percent = 0;
            }
            discount_percent = parseFloat(discount_percent);
            discount_value = total_after_tax * (discount_percent / 100);
            $('#discount-value-span').text(discount_value);
        }
        else if (discount_type == 2) {
            var discount_val = $('#discount-value').val();;
            if (discount_val == '') {
                discount_val = 0;
            }
            discount_value = parseFloat(discount_val);
        }

        total_cost = total_after_tax - discount_value;
        $('#total-cost').val(total_cost);

        if ($("#pill-type").val() == 1) {
            $("#what-paid").val(total_cost);
        }
        else if ($("#pill-type").val() == 2) {
            var what_paid = $("#what-paid").val();
            what_paid = parseFloat(what_paid);
            if(total_cost == '') total_cost = 0;
            if(what_paid == '') what_paid = 0;

            $("#what-remain").val(total_cost - what_paid);
        }
    }

    ////////////////////////////////////////////////////////////////
    // تغير المدفوع والمتبقي

    $(document).on('change', '#pill-type', function() {
        $('#what-remain-div').show();
        $('#what-paid-div').show();

        if ($('#pill-type').val() == 1) {
            var total_cost = $("#total-cost").val();
            $('#what-paid').val(total_cost);
            $('#what-paid').prop('readonly', true);;
            $('#what-remain').val(0);
        }
        else if ($('#pill-type').val() == 2) {
            var total_cost = $("#total-cost").val();
            $('#what-remain').val(total_cost);
            $('#what-paid').prop('readonly', false);;
            $('#what-paid').val(0);
        }
        else {
            $('#what-remain-div').hide();
            $('#what-paid-div').hide();
        }
    });

    $(document).on('input', '#what-paid', function() {
        var total_cost = $("#total-cost").val();
        var what_paid = $(this).val();
        if(total_cost == '') total_cost = 0;
        if(what_paid == "") {
            what_paid = 0
        };
        total_cost = parseFloat(total_cost);
        what_paid = parseFloat(what_paid);

        if(what_paid >= total_cost) {
            alert('لا يمكن ان يكون المبلغ المدفوع اكبر او يساوي من المبلغ الاجمالي');
            $("#what-paid").val(0);
            $("#what-remain").val(total_cost);
        }
        else {
            $("#what-remain").val(total_cost - what_paid);
        }

    });

    $(document).on('input', '#what-remain', function() {
        var total_cost = $("#total-cost").val();
        var what_remain = $(this).val();
        what_remain = parseFloat(what_remain);
        total_cost = parseFloat(total_cost);
        if(total_cost == '') total_cost = 0;
        if(what_remain == '') what_remain = 0;

        $("#what-paid").val(total_cost - what_remain);
    });


    // check the shift still open and recalculate the treasuries money,
    // when you hover on the approve button
    $(document).on('mouseenter', '#do_approve', function() {
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_check_shift_and_reload_money_route').val();
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                '_token':search_token,
            },
            success: function(data) {
                $('#check_shift_and_reload_money_result').html(data);
            },
            error: function() {
                alert('لقد تم الغاء الشفت الذي كنت تستخدمه');
                $('#approve_pill').modal('hide');
            }
        });


    });

    $(document).on('click', '#do_approve', function() {
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

        if ($("#tax-percent").val() == '') {
            alert('من فضلك ادخل نسبة الضريبة');
            $("#tax-percent").focus();
            return false;
        }

        if ($("#discount-type").val() != '') {

            if ($("#discount-type").val() == 1) {
                if ($("#discount-percent").val() == '') {
                    alert('من فضلك ادخل نسبة الخصم');
                    $("#discount-percent").focus();
                    return false;
                }
            }
            else {
                if ($("#discount-type").val() == 2) {
                    if ($("#discount-value").val() == '') {
                        alert('من فضلك ادخل قيمة الخصم');
                        $("#discount-value").focus();
                        return false;
                    }
                }
            }
        }

        if ($("#total-cost").val() == '') {
            alert('من فضلك ادخل اجمالي المبلغ');
            $("#total-cost").focus();
            return false;
        }

        if ($("#treasuries-id").val() == '') {
            alert('من فضلك ادخل اسم الخزنة');
            $("#treasuries-id").focus();
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

        if ($("#pill-type").val() == '') {
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
    });



    // ajax search
    function make_search() {
        // get the value from the input to search by
        var purchase_code_search = $('#purchase_code').val();
        var supplier_code_search = $('#supplier_code_search').val();
        var store_id_search = $('#store_id_search').val();
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
                purchase_code_search:purchase_code_search,
                supplier_code_search:supplier_code_search,
                store_id_search:store_id_search,
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

            }
        });
    }

    $(document).on('input', '#purchase_code', function() {
        make_search();
    });

    $(document).on('change', '#supplier_code_search', function() {
        make_search();
    });
    $(document).on('change', '#store_id_search', function() {
        make_search();
    });
    $(document).on('input', '#from_date_search', function() {
        make_search();
    });
    $(document).on('input', '#to_date_search', function() {
        make_search();
    });
});

