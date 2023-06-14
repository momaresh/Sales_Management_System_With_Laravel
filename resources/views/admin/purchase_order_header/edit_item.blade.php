<html lang="en">
    <head>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </head>
    <body>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>اسم الصنف</label>
                    <select name="item_code" id="item_code_add" class="form-control select2">
                        <option value="">اختر الصنف</option>
                        @if (@isset($items_card) && !@empty($items_card))
                        @foreach ($items_card as $info )
                            <option data-type="{{ $info->item_type }}"  @if(old('item_code', $detail->item_code) == $info->item_code) selected @endif value="{{ $info->item_code }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>

                    @error('item_code')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4 relatied_item_card" style="display: none" id="unit_add">

            </div>

            <div class="col-md-4 relatied_item_card">
                <div class="form-group">
                    <label>الكمية المستلمة</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="quantity_add" class="form-control" value="{{ $detail->quantity }}">
                </div>
            </div>

            <div class="col-md-4 relatied_date">
                <div class="form-group">
                    <label>تاريخ الانتاج</label>
                    <input type="date" id="production_date_add" class="form-control" value="{{ $detail->production_date }}">
                </div>
            </div>

            <div class="col-md-4 relatied_date">
                <div class="form-group">
                    <label>تاريخ الانتهاء</label>
                    <input type="date" id="expire_date_add" class="form-control" value="{{ $detail->expire_date }}">
                </div>
            </div>

            <div class="col-md-4 relatied_item_card">
                <div class="form-group">
                    <label>سعر الوحدة</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="unit_price_add" class="form-control" value="{{ $detail->unit_price }}">
                </div>
            </div>

            <div class="col-md-4 relatied_item_card">
                <div class="form-group">
                    <label>السعر الاجمالي</label>
                    <input type="text" readonly id="total_price_add" class="form-control" value="{{ $detail->total_price }}">
                </div>
            </div>

            <input type="hidden" id="ajax_purchase_order_detail_id" value="{{ $detail->id }}">

        </div>

        <script>
            var type = $('#item_code_add').children('option:selected').data('type');
            if (type == 2) {
                $('.relatied_date').show();
            }
            else {
                $('.relatied_date').hide();
            }


            var item_code = $('#item_code_add').val();
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
        </script>
        <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
        <script>
            $(function () {
                //Initialize Select2 Elements
                $('.select2').select2({
                theme: 'bootstrap4'
                })
            });
        </script>
    </body>
</html>
