$(function() {
    $(document).on('change', '#does_has_retailunit', function() {
        if ($('#unit_id').val() == '') {
            $('#does_has_retailunit').val('');
            alert('اختر وحدة القياس الجملة اولا');
        }
        else {
            var value = $(this).val();

            if (value == 1) {
                $('.relatied_retial_counter').show();
            }
            else {
                $('.relatied_retial_counter').hide();
            }
        }
    });

    $(document).on('change', '#unit_id', function() {

        if ($(this).val() != "") {
            var name = $('#unit_id option:selected').text();

            $('.parent_unit_name').text(name);
            $('.relatied_parent_counter').show();
        }
        else {
            $('.parent_unit_name').text('اختر وحدة القياس الجملة');
            $('.relatied_parent_counter').hide();

        }
    });

    $(document).on('change', '#retail_unit_id', function() {

        if ($(this).val() != "") {
            var name = $('#retail_unit_id option:selected').text();
            $('.child_unit_name').text(name);
            $('.relatied_retial_counter').show();
        }
        else if($('#retail_unit_id').val() == "") {
            $('#does_has_retailunit').val('');
            $('.child_unit_name').text('اختر وحدة القياس التجزئة');
            $('.relatied_retial_counter').hide();
        }
    });

    $(document).on('click', '#do_add_item_card', function() {

        if ($('#name').val() == "") {
            alert('من فضلك ادخل اسم الصنف');
            $('#name').focus();
            return false;
        }

        if ($('#item_type').val() == "") {
            alert('من فضلك ادخل نوع الصنف');
            $('#item_type').focus();
            return false;
        }

        if ($('#inv_itemcard_categories_id').val() == "") {
            alert('من فضلك ادخل فئة الصنف');
            $('#inv_itemcard_categories_id').focus();
            return false;
        }

        if ($('#unit_id').val() == "") {
            alert('من فضلك ادخل وحدة القياس الاساسية');
            $('#unit_id').focus();
            return false;
        }

        if ($('#does_has_retailunit').val() == "") {
            alert('من فضلك ادخل حالة هل للصنف وحدة تجزئة');
            $('#does_has_retailunit').focus();
            return false;
        }

        if ($('#does_has_retailunit').val() == 1) {
            if ($('#retail_unit_id').val() == "") {
                alert('من فضلك ادخل الوحدة التجزئة');
                $('#retail_unit_id').focus();
                return false;
            }
            if ($('#retail_uom_quntToParent').val() == "" || $('#retail_uom_quntToParent').val() == 0) {
                alert('من فضلك ادخل عدد وحدات التجزئة بالنسبة للاساسية');
                $('#retail_uom_quntToParent').focus();
                return false;
            }
            if ($('#price_per_one_in_retail_unit').val() == "") {
                alert('من فضلك ادخل السعر بالحبة  للتجزئة');
                $('#price_per_one_in_retail_unit').focus();
                return false;
            }

            if ($('#price_per_half_group_in_retail_unit').val() == "") {
                alert('من فضلك ادخل السعر لنص جملة  للتجزئة');
                $('#price_per_half_group_in_retail_unit').focus();
                return false;
            }

            if ($('#price_per_group_in_retail_unit').val() == "") {
                alert('من فضلك ادخل السعر  بالجملة  للتجزئة');
                $('#price_per_group_in_retail_unit').focus();
                return false;
            }

            if ($('#cost_price_in_retail').val() == "") {
                alert('من فضلك ادخل سعر تكلفة الشراء للتجزئة');
                $('#cost_price_in_retail').focus();
                return false;
            }

        }

        if ($('#price_per_one_in_master_unit').val() == "") {
            alert('من فضلك ادخل السعر بالحبة  للوحدة الاب');
            $('#price_per_one_in_master_unit').focus();
            return false;
        }

        if ($('#price_per_half_group_in_master_unit').val() == "") {
            alert('من فضلك ادخل السعر لنص جملة  للوحدة الاب');
            $('#price_per_half_group_in_master_unit').focus();
            return false;
        }

        if ($('#price_per_group_in_master_unit').val() == "") {
            alert('من فضلك ادخل السعر  بالجملة  للوحدة الاب');
            $('#price_per_group_in_master_unit').focus();
            return false;
        }

        if ($('#cost_price_in_master').val() == "") {
            alert('من فضلك ادخل سعر تكلفة الشراء للاب');
            $('#cost_price_in_master').focus();
            return false;
        }

        if ($('#has_fixced_price').val() == "") {
            alert('من فضلك ادخل هل ل الصنف سعر ثابت في الفواتير');
            $('#has_fixced_price').focus();
            return false;
        }

        if ($('#active').val() == "") {
            alert('من فضلك ادخل حالة التفعيل');
            $('#active').focus();
            return false;
        }


    });
});
