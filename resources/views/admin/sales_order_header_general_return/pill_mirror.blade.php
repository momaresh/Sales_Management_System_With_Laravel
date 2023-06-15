<html lang="en">
    <head>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/css.mycustomstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </head>
    <body>
        <hr style="border: 1px solid" class="my-col-main">

        <h5 class="my-col-main" style="width:fit-content; margin: 10px auto">اضافة صنف</h5>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>اسم المخزن</label>
                    <select name="store_id" id="store_id_add" class="form-control select2">
                        <option value="">اختر المخزن</option>
                        @if (@isset($stores) && !@empty($stores))
                        @foreach ($stores as $info )
                            <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>نوع البيع</label>
                    <select name="sales_type" id="sales_type" class="form-control select2">
                        <option value="">اختر النوع</option>
                        <option value="1">جملة</option>
                        <option value="2">نص جملة</option>
                        <option value="3">تجزئة</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>اسم الصنف</label>
                    <select name="item_code" id="item_code_add" class="form-control select2">
                        <option value="">اختر الصنف</option>
                        @if (@isset($items_card) && !@empty($items_card))
                        @foreach ($items_card as $info )
                            <option data-type="{{ $info->item_type }}" data-has_fixed_price="{{ $info->has_fixed_price }}"  value="{{ $info->item_code }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-3 relatied_item_card" style="display: none" id="unit_add">

            </div>

            <div class="col-md-3 relatied_item_card" style="display: none" id="batch_add">

            </div>

            <div class="col-md-3 relatied_item_card" style="display: none">
                <div class="form-group">
                    <label>الكمية المستلمة</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="quantity_add" class="form-control" value="">
                </div>
            </div>

            <div class="col-md-2 relatied_item_card" style="display: none">
                <div class="form-group">
                    <label>سعر الوحدة</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^\d.-]+/g,'')" id="unit_price_add" class="form-control" value="">
                </div>
            </div>

            <div class="col-md-2 relatied_item_card" style="display: none">
                <div class="form-group">
                    <label>السعر الاجمالي</label>
                    <input type="text" readonly id="total_price_add" class="form-control" value="">
                </div>
            </div>
            <div class="col-md-2 pt-3" style="display: flex; align-items: center;">
                <button type="button" class="btn btn-primary" id="add_to_detail">اضافة الصنف</button>
            </div>
        </div>

        <hr style="border: 1px solid #007bff">

        <table id="example2" class="table table-bordered table-hover">
            <h5 class="my-col-main" style="width:fit-content; margin: 10px auto">الاصناف المضافة</h5>
            <thead>
                <tr style="background-color: #007bff; color:white;">
                    <th>اسم المخزن</th>
                    <th>اسم الصنف</th>
                    <th>نوع البيع</th>
                    <th>وحدة البيع</th>
                    <th>الكمية</th>
                    <th>سعر الوحدة</th>
                    <th>الاجمالي</th>
                    <th>حذف</th>
                </tr>
            </thead>

            <tbody id="add_new_item_row_result">

            </tbody>
        </table>

        <hr style="border: 1px solid #007bff">
        <h5 class="my-col-main" style="width:fit-content; margin: 10px auto">اضافة الخصومات والضرائب</h5>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>عدد الاصناف بالفاتورة</label>
                    <input type="text" class="form-control" readonly id="all-items" value="0">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>اجمالي سعر الاصناف بالفاتورة قبل الخصم والضريبة</label>
                    <input type="text" id='total-before-discount' class="form-control" name="total_before_discount" readonly value="0">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>نسبة الضريبة</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="tax-percent" class="form-control" name="tax_percent" value="0">
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <label>قيمة الضريبة</label>
                    <input type="text" id="tax-value" class="form-control" name="tax_value" disabled value="0">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>الاجمالي بعد الضريبة</label>
                    <input type="text" id="total-after-tax" class="form-control" disabled value="0">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>نوع الخصم</label>
                    <select id="discount-type" name="discount_type" class="form-control select2">
                        <option value="">نوع الخصم</option>
                        <option value="1">خصم بنسبة</option>
                        <option value="2">خصم بقيمة</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 discount_percent" style="display: none">
                <div class="form-group">
                    <label>نسبة الخصم</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="discount-percent" class="form-control" name="discount_percent" value="0">
                    <span style="color: rgb(199, 8, 8)" id="discount-value-span"></span>
                </div>
            </div>

            <div class="col-md-3 discount_value" style="display: none">
                <div class="form-group">
                    <label>قيمة الخصم</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="discount-value" class="form-control" name="discount_value" value="0">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>اجمالي سعر الاصناف بالفاتورة بعد الخصم والضريبة</label>
                    <input type="text" readonly class="form-control" name="total_cost" id="total-cost" value="0">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>نوع الفاتورة</label>
                    <select id="pill-type" name="pill_type" class="form-control select2">
                        <option value="">نوع الفاتورة</option>
                        <option value="1" selected>كاش</option>
                        <option value="2">آجل</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3" id="what-paid-div">
                <div class="form-group">
                    <label>المبلغ المدفوع</label>
                    <input type="text" readonly id="what-paid" class="form-control" name="what_paid" value="0">
                </div>
            </div>

            <div class="col-md-3" id="what-remain-div">
                <div class="form-group">
                    <label>المبلغ المتبقي</label>
                    <input type="text" readonly id="what-remain" class="form-control" name="what_remain" value="0">
                </div>
            </div>

            <div  class="col-md-6" id="check_shift_and_reload_money_result" style="display: flex; padding: 0">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>اسم الخزينة</label>
                        <input type="text" readonly class="form-control" id="treasuries-id" name="treasuries_name" value="{{ $check_shift['treasuries_name'] }}">
                    </div>

                </div>

                <input type="hidden" class="form-control" id="shift-code" name="shift_code" value="{{ $check_shift['shift_code'] }}">
                <input type="hidden" class="form-control" name="treasuries_id" value="{{ $check_shift['treasuries_id'] }}">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>اجمالي الخزينة</label>
                        <input type="text" readonly class="form-control" id="treasury-money" name="treasuries_money" value="{{ $check_shift['treasuries_money'] }}">
                    </div>
                </div>
            </div>
        </div>

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


