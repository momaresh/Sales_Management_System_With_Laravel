$(document).ready(function() {

    $(document).on('change', '#supplier_code_add', function() {
        var supplier_pills_route = $('#ajax_get_supplier_pills_route').val();
        var supplier_code = $('#supplier_code_add').val();
        var ajax_token = $('#ajax_token').val();

        if (supplier_code != '') {
            jQuery.ajax({
                url:supplier_pills_route,
                type:'post',
                datatype:'html',
                cache:false,
                data:{
                    supplier_code:supplier_code,
                    '_token':ajax_token
                    },
                // If the form and everything okay
                success:function(data){
                    $('#supplier_pills_div').html(data);
                },
                // If the there is an error
                error:function() {
                    alert('error');
                }
            });
        }
        else {
            $('#supplier_pills_div').hide();
        }
    });

    $(document).on('change', '#supplier_pills_add', function() {
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

    $(document).on('input', '#pill_code', function() {
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
                    alert('error');
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

        var batch_quantity = $(this).data("batch_quantity");
        batch_quantity = parseFloat(batch_quantity);
        if (batch_quantity == '') batch_quantity = 0;

        var quantity = $(this).data('quantity');
        quantity = parseFloat(quantity);
        if (quantity == '') quantity = 0;

        if (rejected > quantity) {
            alert('لا تكون الكمية المرتجعة اكبر من الكمية نفسها');
            $(this).val('0');
        }
        else if (rejected > batch_quantity) {
            alert('الكمية المرتجعة ليست كافية في الباتش');
            $(this).val('0');
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
    });


    $(document).on('click', '#approve_pill', function(e) {
        if ($('.rejected_quantity').val() == '' || $('.rejected_quantity').val() == 0) {
            alert('من فضلك ادخل الكمية المرتجعه');
            return false;
        }

        if ($('.total_price').val() == '' || $('.total_price').val() == 0) {
            alert('من فضلك ادخل الاجمالي ');
            return false;
        }

        if ($('.total_pill').val() == '' || $('.total_pill').val() == 0) {
            alert('من فضلك ادخل اجمالي الكمية المرتجعه ');
            return false;
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

