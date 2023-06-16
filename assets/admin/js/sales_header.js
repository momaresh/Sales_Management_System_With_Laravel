$(document).ready(function() {
    // يقوم بعملية اضهار مودل اضافة صنف في نفس الصفحة باستخدام الاجاكس
    $(document).on('click', '#create_pill_button', function() {
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_create_pill_route').val();
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                '_token':search_token,
            },
            success: function(data) {
                $('#create_pill_result').html(data);
                $('#create_pill').modal('show');
            },
            error: function() {
                alert();
            }
        });
    });

    $(document).on('click', '#pill_mirror_button', function() {
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_pill_mirror_route').val();
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                '_token':search_token,
            },
            success: function(data) {
                $('#pill_mirror_result').html(data);
                $('#pill_mirror').modal('show');
            },
            error: function() {
                alert();
            }
        });
    });
    // عندما يتم تغير الصنف يتم تحميل الوحدات الذي يحتوي عليها واضهارها
    $(document).on('change', '#item_code_add', function(e) {
        var item_code = $(this).val();
        var search_token = $('#token_search').val();
        if (item_code != "") {
            var search_url = $('#ajax_get_item_unit_route').val();
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
                    if (type == 2) {
                        $('.related_date').show();
                    }
                    else {
                        $('.related_date').hide();
                    }
                },
                error: function() {
                    $("#unit_add").html("");
                    $('.relatied_item_card').hide();
                    $('.related_date').hide();
                    alert("حدث خطا ما");
                }
            });

            var item_code = $('#item_code_add').val();
            var search_url = $('#ajax_get_item_batch_route').val();
            var store_id = $('#store_id_add').val();
            jQuery.ajax({
                url: search_url,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    item_code:item_code,
                    store_id:store_id,
                    '_token':search_token
                },
                success: function(data) {
                    $("#batch_add").html(data);
                },
                error: function() {
                    $("#batch_add").html("");
                    $('.relatied_item_card').hide();

                    alert("حدث خطا ما");
                }
            });

            var search_url = $('#ajax_get_item_price_route').val();
            var sales_type = $('#sales_type').val();
            jQuery.ajax({
                url: search_url,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    item_code:item_code,
                    sales_type:sales_type,
                    '_token':search_token
                },
                success: function(data) {
                    $("#unit_price_add").val(data);
                    calculate_total_price();
                },
                error: function() {
                    $("#batch_add").html("");
                    $('.relatied_item_card').hide();
                    alert("حدث خطا ما");
                }
            });

        }
        else {
            $("#unit_add").html("");
            $('.relatied_item_card').hide();

        }



    });

    $(document).on('change', '#unit_id_add', function() {
        var item_code = $('#item_code_add').val();
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_get_item_batch_route').val();
        var store_id = $('#store_id_add').val();
        var unit_id = $(this).val();
        jQuery.ajax({
            url: search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                item_code:item_code,
                store_id:store_id,
                unit_id:unit_id,
                '_token':search_token
            },
            success: function(data) {
                $("#batch_add").html(data);
            },
            error: function() {
                $("#batch_add").html("");
                $('.relatied_item_card').hide();

                alert("حدث خطا ما");
            }
        });


        var search_url = $('#ajax_get_item_price_route').val();
        var sales_type = $('#sales_type').val();
        jQuery.ajax({
            url: search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                item_code:item_code,
                sales_type:sales_type,
                unit_id:unit_id,
                '_token':search_token
            },
            success: function(data) {
                $("#unit_price_add").val(data);
                calculate_total_price();
            },
            error: function() {
                $("#batch_add").html("");
                $('.relatied_item_card').hide();

                alert("حدث خطا ما");
            }
        });

    })

    $(document).on('change', '#store_id_add', function() {
        var item_code = $('#item_code_add').val();
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_get_item_batch_route').val();
        var store_id = $(this).val();
        var unit_id = $('#unit_id_add').val();
        jQuery.ajax({
            url: search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                item_code:item_code,
                store_id:store_id,
                unit_id:unit_id,
                '_token':search_token
            },
            success: function(data) {
                $("#batch_add").html(data);
            },
            error: function() {
                $("#batch_add").html("");
                $('.relatied_item_card').hide();

                alert("حدث خطا ما");
            }
        });
    })


    $(document).on('input', '#quantity_add', function() {
        calculate_total_price();
    });

    $(document).on('input', '#unit_price_add', function() {
        calculate_total_price();
    });

    function calculate_total_price() {
        var quantity = $('#quantity_add').val();
        if (quantity == '') quantity = 0;
        var unit_price = $('#unit_price_add').val();
        if (unit_price == '') unit_price = 0;

        $('#total_price_add').val(parseFloat(quantity) * parseFloat(unit_price));
    }

    // هذا يقوم بعملية حفظ البيانات تلقائيا باستخدام الاجاكس ويقوم بعمل تحقق من ان جميع البيانات الازمة مدخلة
    $(document).on('click', '#add_to_detail', function() {
        var store_id = $('#store_id_add').val();
        if (store_id == '') {
            alert('من فضلك اختر المخزن');
            $('#store_id_add').focus();
            return false;
        }

        var sales_type = $('#sales_type').val();
        if (sales_type == '') {
            alert('من فضلك اختر نوع البيع');
            $('#sales_type').focus();
            return false;
        }

        var item_code = $('#item_code_add').val();
        if (item_code == '') {
            alert('من فضلك اختر الصنف');
            $('#item_code_add').focus();
            return false;
        }

        var unit_id = $('#unit_id_add').val();
        if (unit_id == '') {
            alert('من فضلك اختر الوحدة');
            $('#unit_id_add').focus();
            return false;
        }

        var batch_id = $('#batch_id_add').val();
        if (batch_id == '') {
            alert('من فضلك اختر الباتش من المخزن');
            $('#batch_id_add').focus();
            return false;
        }

        var quantity = $('#quantity_add').val();
        if (quantity == '' || $('#quantity_add').val() == 0) {
            alert('من فضلك ادخل الكمية');
            $('#quantity_add').focus();
            return false;
        }

        var unit_price = $('#unit_price_add').val();
        if (unit_price == '') {
            alert('من فضلك ادخل سعر الوحدة');
            $('#unit_price_add').focus();
            return false;
        }

        var total_price = $('#total_price_add').val();
        if (total_price == '') {
            alert('من فضلك ادخل السعر الاجمالي');
            $('#total_price_add').focus();
            return false;
        }

        var batch_quantity = $('#batch_id_add').children('option:selected').data('quantity');
        if (parseFloat(quantity) > parseFloat(batch_quantity)) {
            alert('من فضلك الكمية غير كافية في الباتش الحالي');
            $('#quantity_add').focus();
            return false;
        }

        var production_date = $('#batch_id_add').children('option:selected').data('production_date');
        var expire_date = $('#batch_id_add').children('option:selected').data('expire_date');
        var invoice_order_id = $('#invoice_order_id').val();

        var store_name = $('#store_id_add option:selected').text();
        var sales_type_name = $('#sales_type option:selected').text();
        var item_name = $('#item_code_add option:selected').text();
        var unit_name = $('#unit_id_add option:selected').text();
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_add_new_item_row_route').val();
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                invoice_order_id:invoice_order_id,
                item_code:item_code,
                unit_id:unit_id,
                quantity:quantity,
                unit_price:unit_price,
                total_price:total_price,
                batch_id:batch_id,
                store_id:store_id,
                sales_type:sales_type,
                production_date:production_date,
                expire_date:expire_date,
                store_name:store_name,
                sales_type_name:sales_type_name,
                item_name:item_name,
                unit_name:unit_name,
                '_token':search_token
            },
            success: function(data) {
                $('#add_new_item_row_result').append(data);
                calc_total_cost();
            },
            error: function(data) {

            }
        });
    });

    $(document).on('click', '.remove_item', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        calc_total_cost();
    });

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

    // يقوم بحسبة المبلغ الاجمالي والمبلغ بعد الضريبة والمتبقي وغيرها
    function calc_total_cost() {
        var total_before_discount = 0;
        var all_items = 0;
        $('.total_price_array').each(function() {
            var total = $(this).val();
            total = parseFloat(total);
            total_before_discount += total;
            all_items++;
        });

        $('#all-items').val(all_items);
        $('#total-before-discount').val(total_before_discount);
        if (total_before_discount == '') {
            total_before_discount = 0;
        }

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

    $(document).on('click', '#store', function() {
        var search_token = $("#token_search").val();
        var search_url = $("#ajax_store_route").val();
        var pill_number = $("#pill_n").val();
        var pill_type = $("#pill_type").val();
        var pill_date = $("#pill_date").val();
        var customer_code = $("#customer_code").val();
        var delegate_code = $("#delegate_code").val();
        var notes = $("#notes").text();

        var sales_type = $('#sales_type').val();
        if (sales_type == '') {
            alert('من فضلك اختر نوع البيع');
            $('#sales_type').focus();
            return false;
        }

        if (pill_date == '') {
            alert('من فضلك ادخل تاريخ الفاتورة');
            $("#pill_date").focus();
            return false;
        }

        if (delegate_code == '') {
            alert('من فضلك ادخل اسم المندوب');
            $("#delegate_code").focus();
            return false;
        }

        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'json',
            cache: false,
            data: {
                pill_number:pill_number,
                pill_type:pill_type,
                pill_date:pill_date,
                sales_type:sales_type,
                customer_code:customer_code,
                delegate_code:delegate_code,
                notes:notes,
                '_token':search_token
            },
            success: function(data) {
                load_pill_adding_items_modal(data);
                make_search();
            },
            error: function() {
            }
        });


    });

    $(document).on('click', '#update_pill', function() {
        var id = $(this).data('id');
        load_pill_adding_items_modal(id);
    });

    function load_pill_adding_items_modal(id) {
        var search_token = $("#token_search").val();
        var search_url = $("#ajax_load_pill_adding_items_modal_route").val();

        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                id:id,
                '_token':search_token
            },
            success: function(data) {
                $("#create_pill_result").html('');
                $("#create_pill").modal('hide');

                $("#pill_adding_items_result").html(data);
                $("#pill_adding_items_modal").modal('show');
            },
            error: function() {
            }
        });


    };

    function reload_batches() {
        var search_token = $('#token_search').val();
        var item_code = $('#item_code_add').val();
        var unit_id = $('#unit_id_add').val();
        var search_url = $('#ajax_get_item_batch_route').val();
        var store_id = $('#store_id_add').val();
        jQuery.ajax({
            url: search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                unit_id:unit_id,
                item_code:item_code,
                store_id:store_id,
                '_token':search_token
            },
            success: function(data) {
                $("#batch_add").html(data);
            },
            error: function() {
                $("#batch_add").html("");
                $('.relatied_item_card').hide();
                $('.related_date').hide();
                alert("حدث خطا ما");
            }
        });
    }

    $(document).on('click', '#add_to_detail_active', function() {
        var store_id = $('#store_id_add').val();
        if (store_id == '') {
            alert('من فضلك اختر المخزن');
            $('#store_id_add').focus();
            return false;
        }

        var item_code = $('#item_code_add').val();
        if (item_code == '') {
            alert('من فضلك اختر الصنف');
            $('#item_code_add').focus();
            return false;
        }

        var unit_id = $('#unit_id_add').val();
        if (unit_id == '') {
            alert('من فضلك اختر الوحدة');
            $('#unit_id_add').focus();
            return false;
        }

        var batch_id = $('#batch_id_add').val();
        if (batch_id == '') {
            alert('من فضلك اختر الباتش من المخزن');
            $('#batch_id_add').focus();
            return false;
        }

        var quantity = $('#quantity_add').val();
        if (quantity == '' || $('#quantity_add').val() == 0) {
            alert('من فضلك ادخل الكمية');
            $('#quantity_add').focus();
            return false;
        }

        var total_price = $('#total_price_add').val();
        if (total_price == '') {
            alert('من فضلك ادخل السعر الاجمالي');
            $('#total_price_add').focus();
            return false;
        }

        // in case is not return pill
        if (batch_id != 'new') {
            var batch_quantity = $('#batch_id_add').children('option:selected').data('quantity');
            if (parseFloat(quantity) > parseFloat(batch_quantity)) {
                alert('من فضلك الكمية غير كافية في الباتش الحالي');
                $('#quantity_add').focus();
                return false;
            }
        }

        // in case is return pill
        if (batch_id != 'new') {
            var production_date = $('#batch_id_add').children('option:selected').data('production_date');
            var expire_date = $('#batch_id_add').children('option:selected').data('expire_date');
        }
        else {
            var production_date = $('#production_date_add').val();
            var expire_date = $('#expire_date_add').val();
        }

        var unit_price = $('#unit_price_add').val();
        if (unit_price == '') {
            alert('من فضلك ادخل سعر الوحدة');
            $('#unit_price_add').focus();
            return false;
        }


        var invoice_order_id = $('#invoice_order_id').val();
        var store_name = $('#store_id_add option:selected').text();
        var sales_type_name = $('#sales_type option:selected').text();
        var item_name = $('#item_code_add option:selected').text();
        var unit_name = $('#unit_id_add option:selected').text();
        var search_token = $('#token_search').val();
        var search_url = $('#ajax_add_new_item_row_route').val();

        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                item_code:item_code,
                unit_id:unit_id,
                quantity:quantity,
                unit_price:unit_price,
                total_price:total_price,
                batch_id:batch_id,
                store_id:store_id,
                store_name:store_name,
                sales_type_name:sales_type_name,
                item_name:item_name,
                unit_name:unit_name,
                '_token':search_token
            },
            success: function(data) {
                $('#add_new_item_row_result').append(data);
                calc_total_cost();
            },
            error: function(data) {
                alert('حدث جطأ ما');
            }
        });


        var search_url = $('#ajax_store_item_route').val();
        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                invoice_order_id:invoice_order_id,
                item_code:item_code,
                unit_id:unit_id,
                quantity:quantity,
                unit_price:unit_price,
                total_price:total_price,
                batch_id:batch_id,
                store_id:store_id,
                production_date:production_date,
                expire_date:expire_date,
                '_token':search_token
            },
            success: function(data) {
                reload_batches();
            },
            error: function(data) {

            }
        });
    });

    $(document).on('click', '.remove_item_active', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        calc_total_cost();

        var search_token = $("#token_search").val();
        var search_url = $("#ajax_remove_item_route").val();
        var invoice_order_id = $('#invoice_order_id').val();
        var id = $(this).data('id');

        jQuery.ajax({
            url:search_url,
            type:'post',
            dataType:'html',
            cache: false,
            data: {
                invoice_order_id:invoice_order_id,
                id:id,
                '_token':search_token
            },
            success: function(data) {
                reload_batches();
            },
            error: function(data) {

            }
        });

    });

    // check the shift still open and recalculate the treasuries money,
    // when you hover on the approve button
    $(document).on('mouseenter', '#approve_pill', function() {
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

    $(document).on('click', '#approve_pill', function() {

        var what_paid = $('#what-paid').val();
        if (what_paid == '') what_paid = 0;
        what_paid = parseFloat(what_paid);

        if ($("#tax-percent").val() == '') {
            alert('من فضلك ادخل نسبة الضريبة');
            $("#tax-percent").focus();
            return false;
        }

        var treasury_money = $('.treasury_money_return').val();
        if (treasury_money == '') treasury_money = 0;
        treasury_money = parseFloat(treasury_money);
        if (what_paid > treasury_money) {
            alert('عفوا ليس لديك رصيد كافي في الخزنة');
            $("#what-paid").focus();
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
        var sales_code_search = $('#sales_code').val();
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
                sales_code_search:sales_code_search,
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

            }
        });
    }

    $(document).on('input', '#sales_code', function() {
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

    $(document).on('click', '#add_new_customer_btn', function(e) {
        e.preventDefault();
        $('#add_new_customer_modal').modal('show');
    });

    $(document).on('click', '#add_to_customer', function() {
        var first_name = $("#first_name").val();
        if (first_name == "") {
            alert("اسم العميل مطلوب ");
            $("#first_name").focus();
            return false;
        }

        var last_name = $("#last_name").val();
        if (last_name == "") {
            alert("اسم العميل مطلوب ");
            $("#last_name").focus();
            return false;
        }

        var start_balance_status = $("#start_balance_status").val();
        if (start_balance_status == "") {
            alert("حالة رصيد العميل اول المدة مطلوبة !!! ");
            $("#start_balance_status").focus();
            return false;
        }
        var start_balance = $("#start_balance").val();
        if (start_balance == "") {
            alert("رصيد العميل اول المدة مطلوب !!! ");
            $("#start_balance").focus();
            return false;
        }
        if (start_balance_status == 3 && start_balance != 0) {
            alert("عفوا لابد ان يكون رصيد اول المده صفر في حالة الاتزان");
            $("#start_balance").val(0);
            $("#start_balance").focus();
            return false;
        }
        var active = $("#active").val();
        if (active == "") {
            alert("حالة تفعيل العميل مطلوبة !!! ");
            $("#active").focus();
            return false;
        }
        var address = $("#address").val();
        var phone = $("#phone").val();
        var notes = $("#notes").val();
        var search_token = $("#token_search").val();
        var search_url = $("#ajax_add_to_customer_route").val();
        jQuery.ajax({
            url: search_url,
            type: 'post',
            dataType:'json',
            cache:false,
            data: {
                first_name:first_name,
                last_name:last_name,
                start_balance_status:start_balance_status,
                start_balance:start_balance,
                active:active,
                address:address,
                phone:phone,
                notes:notes,
                "_token":search_token,
            },
            success: function(data) {
                $("#active").val("");
                $("#address").val("");
                $("#phones").val("");
                $("#notes").val("");
                $("#first_name").val("");
                $("#last_name").val("");
                $("#start_balance").val("");
                $("#start_balance_status").val("");
                $("#add_new_customer_modal").modal("hide");
                get_last_added_customer(data)
            },
            error: function() {
                alert("حدث خطا ما");
            }
        });
    });

    function get_last_added_customer(id) {
        var search_token = $("#token_search").val();
        var search_url = $("#ajax_get_added_customer_route").val();
        jQuery.ajax({
            url: search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                id:id,
                "_token": search_token
            },
            success: function(data) {
                $("#customer_code_div").html(data);
            },
            error: function() {
                alert("حدث خطاما");
            }
        });
    }

});

