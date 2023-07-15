<html lang="en">
    <head>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/css.mycustomstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </head>
    <body>
        <hr style="border: 1px solid" class="my-col-main">

        <h5 class="my-col-main" style="width:fit-content; margin: 10px auto">الفاتورة</h5>
        <div class="row">

            <input type="hidden" name="id" id="invoice_order_id" value="{{ $sales_data->id }}">

            <div class="col-md-3">
                <div class="form-group">
                    <label>اسم المورد</label>
                    <input type="text" readonly value="{{ $sales_data->supplier_name }}" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>اسم المخزن</label>
                    <input type="text" readonly value="{{ $sales_data->store_name }}" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>نوع الفاتورة</label>
                    <input type="text" readonly value="@if($sales_data->pill_type == 1) نقدا @else آجل @endif" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <label for="inputEmail3">تاريخ الفاتورة</label>
                <div class="form-group">
                    <input type="date" readonly name="order_date" id="pill_date" class="form-control" value="{{ $sales_data->order_date }}">
                </div>
            </div>
        </div>

        <hr style="border: 1px solid" class="my-col-main">

        @if ($sales_data->is_approved == 0)
            <h5 class="my-col-main" style="width:fit-content; margin: 10px auto">اضافة صنف</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>اسم المخزن</label>
                        <select name="store_id" id="store_id_add" class="form-control">
                            <option value="{{ $sales_data->store_id }}">{{ $sales_data->store_name }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
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

                <div class="col-md-4 relatied_item_card" style="display: none" id="unit_add">

                </div>

                <div class="col-md-4 relatied_item_card" style="display: none" id="batch_add">

                </div>

                <div class="col-md-2 relatied_item_card" style="display: none">
                    <div class="form-group">
                        <label>الكمية المرتجعة</label>
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
                    <button type="button" class="btn btn-primary" id="add_to_detail_active">اضافة الصنف</button>
                </div>
            </div>
            <hr style="border: 1px solid #007bff">
        @endif

        <table id="example2" class="table table-bordered table-hover">
            <h5 class="my-col-main" style="width:fit-content; margin: 10px auto">الاصناف المضافة</h5>
            <thead>
                <tr style="background-color: #007bff; color:white;">
                    <th>اسم المخزن</th>
                    <th>اسم الصنف</th>
                    <th>وحدة البيع</th>
                    <th>الكمية</th>
                    <th>سعر الوحدة</th>
                    <th>الاجمالي</th>
                    @if ($sales_data->is_approved == 0)
                        <th>حذف</th>
                    @endif
                </tr>
            </thead>

            <tbody id="add_new_item_row_result">
                @foreach ($items as $data)
                    <tr>
                        <input type="hidden" name="total_price_array[]" class="total_price_array" value="{{ $data['total_price'] }}">
                        <td>{{ $data['store_name'] }}</td>
                        <td>{{ $data['item_name'] }}</td>
                        <td>{{ $data['unit_name'] }}</td>
                        <td>{{ $data['quantity'] }}</td>
                        <td>{{ $data['unit_price'] }}</td>
                        <td>{{ $data['total_price'] }}</td>
                        @if ($sales_data->is_approved == 0)
                            <td><button data-id="{{ $data['id'] }}" class="btn btn-danger remove_item_active"><i class="fa-solid fa-trash-can"></i></button></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr style="border: 1px solid #007bff">


        <form action="{{ route('admin.purchase_order_header_general_return.approve_pill', $sales_data->id) }}" method="post">
            @csrf
            <h5 class="my-col-main" style="width:fit-content; margin: 10px auto">اضافة الخصومات والضرائب</h5>
            @if ($sales_data->is_approved == 0)
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>عدد الاصناف بالفاتورة</label>
                            <input type="text" class="form-control" readonly id="all-items" value="{{ $sales_data->all_items }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>اجمالي سعر الاصناف بالفاتورة قبل الخصم والضريبة</label>
                            <input type="text" id='total-before-discount' class="form-control" name="total_before_discount" readonly value="{{ $sales_data->total_before_discount }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نسبة الضريبة</label>
                            <input readonly type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="tax-percent" class="form-control" name="tax_percent" value="{{ $sales_data->tax_percent }}">
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label>قيمة الضريبة</label>
                            <input type="text" id="tax-value" class="form-control" name="tax_value" disabled value="{{ $sales_data->tax_value }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الاجمالي بعد الضريبة</label>
                            <input type="text" id="total-after-tax" class="form-control" disabled value="{{ $sales_data->total_after_tax }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نوع الخصم</label>
                            <select id="discount-type" name="discount_type" class="form-control select2">
                                <option value="">نوع الخصم</option>
                                <option @if($sales_data->discount_type == 1) selected @endif value="1">خصم بنسبة</option>
                                <option @if($sales_data->discount_type == 2) selected @endif value="2">خصم بقيمة</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 discount_percent" style="display: none">
                        <div class="form-group">
                            <label>نسبة الخصم</label>
                            <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="discount-percent" class="form-control" name="discount_percent" value="{{ $sales_data->discount_percent }}">
                            <span style="color: rgb(199, 8, 8)" class="discount-value-span"></span>
                            <input type="hidden" name="discount_val" class="discount-value-span">
                        </div>
                    </div>

                    <div class="col-md-3 discount_value" style="display: none">
                        <div class="form-group">
                            <label>قيمة الخصم</label>
                            <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="discount-value" class="form-control" name="discount_value" value="{{ $sales_data->disount_value }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>اجمالي سعر الاصناف بالفاتورة بعد الخصم والضريبة</label>
                            <input type="text" readonly class="form-control" name="total_cost" id="total-cost" value="{{ $sales_data->total_cost }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نوع الفاتورة</label>
                            <select id="pill-type" name="pill_type" class="form-control select2">
                                <option value="">نوع الفاتورة</option>
                                <option @if($sales_data->pill_type == 1) selected @endif value="1">كاش</option>
                                <option @if($sales_data->pill_type == 2) selected @endif value="2">آجل</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="what-paid-div">
                        <div class="form-group">
                            <label>المبلغ المدفوع</label>
                            <input type="text" readonly id="what-paid" class="form-control" name="what_paid" value="{{ $sales_data->what_paid }}">
                        </div>
                    </div>

                    <div class="col-md-3" id="what-remain-div">
                        <div class="form-group">
                            <label>المبلغ المتبقي</label>
                            <input type="text" readonly id="what-remain" class="form-control" name="what_remain" value="{{ $sales_data->what_remain }}">
                        </div>
                    </div>

                    <div  class="col-md-6" id="check_shift_and_reload_money_result" style="display: flex; padding: 0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>اسم الخزينة</label>
                                <input type="text" readonly class="form-control" id="treasuries-id" name="treasuries_name" value="{{ $check_shift['treasuries_name'] }}">
                            </div>

                        </div>

                        <input type="hidden" class="form-control" id="shift-code" name="shift_code" value="{{ $check_shift['id'] }}">
                        <input type="hidden" class="form-control" name="treasuries_id" value="{{ $check_shift['treasuries_id'] }}">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>اجمالي الخزينة</label>
                                <input type="text" readonly class="form-control" id="treasury-money" name="treasuries_money" value="{{ $check_shift['treasuries_money'] }}">
                            </div>
                        </div>
                    </div>
                </div>
                @if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات العام' , 'اعتماد'))
                    <button type="submit" class="btn btn-primary m-3" id="approve_pill">اعتماد وترحيل</button>
                @endif

            @else
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>عدد الاصناف بالفاتورة</label>
                            <input type="text" class="form-control" readonly value="{{ $sales_data->all_items }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>اجمالي سعر الاصناف بالفاتورة قبل الخصم والضريبة</label>
                            <input type="text" class="form-control" name="total_before_discount" readonly value="{{ $sales_data->total_before_discount }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نسبة الضريبة</label>
                            <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" readonly class="form-control" name="tax_percent" value="{{ $sales_data->tax_percent }}">
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label>قيمة الضريبة</label>
                            <input type="text" class="form-control" name="tax_value" readonly value="{{ $sales_data->tax_value }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الاجمالي بعد الضريبة</label>
                            <input type="text" class="form-control" readonly value="{{ $sales_data->total_after_tax }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نوع الخصم</label>
                            <select name="discount_type" disabled class="form-control select2">
                                <option @if($sales_data->discount_type == 1) selected @endif value="1">خصم بنسبة</option>
                                <option @if($sales_data->discount_type == 2) selected @endif value="2">خصم بقيمة</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نسبة الخصم</label>
                            <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" class="form-control" readonly name="discount_percent" value="{{ $sales_data->discount_percent }}">
                            <span style="color: rgb(199, 8, 8)" id="discount-value-span"></span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>قيمة الخصم</label>
                            <input type="text" readonly oninput="this.value=this.value.replace(/[^0-9]/g,'')" class="form-control" name="discount_value" value="{{ $sales_data->discount_value }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>اجمالي سعر الاصناف بالفاتورة بعد الخصم والضريبة</label>
                            <input type="text" readonly class="form-control" name="total_cost" value="{{ $sales_data->total_cost }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نوع الفاتورة</label>
                            <select name="pill_type" disabled class="form-control select2">
                                <option @if($sales_data->pill_type == 1) selected @endif value="1">كاش</option>
                                <option @if($sales_data->pill_type == 2) selected @endif value="2">آجل</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>المبلغ المدفوع</label>
                            <input type="text" readonly class="form-control" name="what_paid" value="{{ $sales_data->what_paid }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>المبلغ المتبقي</label>
                            <input type="text" readonly class="form-control" name="what_remain" value="{{ $sales_data->what_remain }}">
                        </div>
                    </div>
                </div>
            @endif
        </form>

        <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
        <script>
            $(function () {

                if ($(window).width() < 1100) {
                    $('table').addClass('table-responsive');
                }
                else {
                    $('table').removeClass('table-responsive');
                }

                //Initialize Select2 Elements
                $('.select2').select2({
                theme: 'bootstrap4'
                })

                if ($('#pill-type').val() == 1) {
                    var total_cost = $("#total-cost").val();
                    $('#what-paid').val(total_cost);
                    $('#what-paid').prop('readonly', true);;
                    $('#what-remain').val(0);
                }

                else if ($('#pill-type').val() == 2) {
                    var total_cost = $("#total-cost").val();
                    $('#what-remain').val(total_cost);
                    $('#what-paid').prop('readonly', false);
                    $('#what-paid').val(0);
                }
            });
        </script>
    </body>
</html>
