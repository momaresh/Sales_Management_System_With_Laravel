@if (!@empty($pill))
    @if ($pill->is_original_return == 0)
        <form action="{{ route('admin.purchase_order_header_original_return.approve_pill', $pill['id']) }}" method="POST" class="col-md-12">
            @csrf
            <table class="table table-striped table-borderless" dir="rtl">
                <thead style="background-color:#84B0CA ;" class="text-white">
                    <tr>
                    <th scope="col">رقم الفاتورة</th>
                    <th scope="col">المورد</th>
                    <th scope="col">تاريخ الفاتورة</th>
                    <th scope="col">نوع الفاتورة</th>
                    <th scope="col">الاجمالي</th>
                    <th scope="col">الضريبة</th>
                    <th scope="col">الخصم</th>
                    <th scope="col">الصافي</th>
                    <th scope="col">المدفوع</th>
                    <th scope="col">المتبقي</th>
                    <th scope="col">الحالة</th>
                    <th scope="col">المخزن</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $pill['pill_code'] }}</td>
                        <td>{{ $pill['supplier_name'] }}</td>
                        <td>{{ $pill['order_date'] }}</td>
                        <td>
                            @if ($pill['pill_type'] == 1)
                                كاش
                            @else
                                آجل
                            @endif
                        </td>
                        <td>{{ $pill['total_before_discount'] * 1}}</td>
                        <td>{{ $pill['tax_value'] * 1}}</td>
                        <td>{{ $pill['discount_value'] * 1 }}</td>
                        <td>{{ $pill['total_cost'] * 1}}</td>
                        <td>{{ $pill['what_paid'] }}</td>
                        <td>{{ $pill['what_remain'] }}</td>
                        <td>
                            @if ($pill['is_approved'] == 1)
                                معتمدة
                            @else
                                غير معتمدة
                            @endif
                        </td>
                        <td>{{ $pill['store_name'] }}</td>
                        <input type="hidden" id="tax_percent" value="{{ $pill['tax_percent'] }}">
                        <input type="hidden" id="discount_percent" value="{{ $pill['discount_percent'] }}">
                    </tr>
                </tbody>
            </table>


            <table id="example2" class="table table-bordered table-hover">
                <tr style="background-color: #535f6c; color:white;">
                    <th>#</th>
                    <th>اسم الصنف</th>
                    <th>الوحدة</th>
                    <th>الكمية</th>
                    <th>الكمية المرتجعة</th>
                    <th>الكمية في الباتش</th>
                    <th>سعر الوحدة</th>
                    <th>الاجمالي</th>
                </tr>
                @php
                    $i = 1;
                @endphp
                @foreach ($pill_details as $detail)
                    <tr>
                        <td>
                            @php
                                echo $i;
                            @endphp
                        </td>
                        <td>{{ $detail['item_name'] }}</td>
                        <td>{{ $detail['unit_name'] }}</td>
                        <td>{{ $detail['quantity'] *1 }}</td>
                        <td><input data-unit_price="{{ $detail['unit_price'] }}" data-quantity="{{ $detail['quantity'] }}" data-batch_quantity="{{ $detail['batch_quantity'] }}" type="number" name="rejected_quantity[]" class="form-control rejected_quantity" value="0"></td>
                        <td>{{ $detail['batch_quantity'] * 1}}</td>
                        <td>{{ $detail['unit_price'] * 1}}</td>
                        <td><input type="text" readonly name="total_price[]" class="form-control total_price" value="0"></td>
                        <input type="hidden" name="id[]" value="{{ $detail['id'] }}">
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
                <tr>
                    <td for="">اجمالي الفاتورة</td>
                    <td colspan="7"><input readonly type="text" name="total_pill" id="total_pill" value="0" class="form-control"></td>
                </tr>
            </table>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>نوع الفاتورة</label>
                        <select id="pill-type" name="pill_type" class="form-control select2">
                            <option value="">نوع الفاتورة</option>
                            <option @if($pill->pill_type == 1) selected @endif value="1">كاش</option>
                            <option @if($pill->pill_type == 2) selected @endif value="2">آجل</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4" id="what-paid-div">
                    <div class="form-group">
                        <label>المبلغ المدفوع</label>
                        <input type="text" readonly id="what-paid" class="form-control" name="what_paid" value="0">
                    </div>
                </div>

                <div class="col-md-4" id="what-remain-div">
                    <div class="form-group">
                        <label>المبلغ المتبقي</label>
                        <input type="text" readonly id="what-remain" class="form-control" name="what_remain" value="0">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>اسم الخزينة</label>
                        <input type="text" readonly class="form-control" id="treasuries-id" name="treasuries_name" value="{{ $check_shift['treasuries_name'] }}">
                    </div>

                </div>

                <input type="hidden" class="form-control" id="shift-code" name="shift_code" value="{{ $check_shift['shift_code'] }}">
                <input type="hidden" class="form-control" name="treasuries_id" value="{{ $check_shift['treasuries_id'] }}">

                <div class="col-md-4">
                    <div class="form-group">
                        <label>اجمالي الخزينة</label>
                        <input type="text" readonly class="form-control" id="treasury-money" name="treasuries_money" value="{{ $check_shift['treasuries_money'] }}">
                    </div>
                </div>
            </div>

            <button  class="btn btn-primary" id="approve_pill" type="submit">اعتماد</button>
        </form>
    @else
        <div class="alert alert-info col-md-12 text-center">
            لقد تم ارجاع الفاتورة مسبقا
        </div>
    @endif
@else
    <div class="alert alert-danger col-md-12 text-center">
        لا يوجد بيانات كهذه
    </div>
@endif

<script>
    if ($('#pill-type').val() == 1) {
        $('#what-paid').prop('readonly', true);;
        $('#what-remain').prop('readonly', true);;
        $('#what-paid').val(0);
        $('#what-remain').val(0);
    }
    else if ($('#pill-type').val() == 2) {
        $('#what-paid').prop('readonly', false);;
        $('#what-remain').prop('readonly', true);;
        $('#what-paid').val(0);
        $('#what-remain').val(0);
    }
    else {
        $('#what-remain-div').hide();
        $('#what-paid-div').hide();
    }
</script>
