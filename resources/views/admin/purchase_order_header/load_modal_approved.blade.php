@if (@isset($data) && !@empty($data))

    @if ($data['is_approved'] == 0)
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>عدد الاصناف بالفاتورة</label>
                    <input type="text" class="form-control" disabled value="{{ $data['all_items'] }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>اجمالي سعر الاصناف بالفاتورة قبل الخصم والضريبة</label>
                    <input type="text" id='total-before-discount' class="form-control" name="total_before_discount" disabled value="{{ $data['total_before_discount'] }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>نسبة الضريبة</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="tax-percent" class="form-control" name="tax_percent" value="0">
                </div>
            </div>


            <div class="col-md-4">
                <div class="form-group">
                    <label>قيمة الضريبة</label>
                    <input type="text" id="tax-value" class="form-control" name="tax_value" disabled value="">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>الاجمالي بعد الضريبة</label>
                    <input type="text" id="total-after-tax" class="form-control" disabled value="">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>نوع الخصم</label>
                    <select id="discount-type" name="discount_type" class="form-control select2">
                        <option value="">نوع الخصم</option>
                        <option value="1">خصم بنسبة</option>
                        <option value="2">خصم بقيمة</option>
                    </select>

                    @error('discount_type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4 discount_percent" style="display: none">
                <div class="form-group">
                    <label>نسبة الخصم</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="discount-percent" class="form-control" name="discount_percent" value="">
                    <span style="color: rgb(199, 8, 8)" id="discount-value-span"></span>
                    <input type="hidden" name="discount_val" id="discount-value-input">
                    @error('discount_percent')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4 discount_value" style="display: none">
                <div class="form-group">
                    <label>قيمة الخصم</label>
                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="discount-value" class="form-control" name="discount_value" value="">
                    @error('discount_value')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>اجمالي سعر الاصناف بالفاتورة بعد الخصم والضريبة</label>
                    <input type="text" readonly class="form-control" name="total_cost" id="total-cost" value="{{ $data['total_before_discount'] }}">
                    @error('total_cost')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div  class="col-md-8" id="check_shift_and_reload_money_result" style="display: flex; padding: 0">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>اسم الخزينة</label>
                        <input type="text" readonly class="form-control" id="treasuries-id" name="treasuries_name" value="{{ $check_shift['treasuries_name'] }}">
                        @error('treasuries_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <input type="hidden" class="form-control" id="shift-code" name="shift_code" value="{{ $check_shift['shift_code'] }}">
                <input type="hidden" class="form-control" name="treasuries_id" value="{{ $check_shift['treasuries_id'] }}">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>اجمالي الخزينة</label>
                        <input type="text" readonly class="form-control" id="treasury-money" name="treasuries_money" value="{{ $check_shift['treasuries_money'] }}">
                        @error('treasuries_money')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>نوع الفاتورة</label>
                    <select id="pill-type" name="pill_type" class="form-control select2">
                        <option value="">نوع الفاتورة</option>
                        <option @if ($data->pill_type == 1) selected @endif value="1">كاش</option>
                        <option @if ($data->pill_type == 2) selected @endif value="2">آجل</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4" id="what-paid-div">
                <div class="form-group">
                    <label>المبلغ المدفوع</label>
                    <input type="text" id="what-paid" class="form-control" name="what_paid" value="">
                </div>
            </div>

            <div class="col-md-4" id="what-remain-div">
                <div class="form-group">
                    <label>المبلغ المتبقي</label>
                    <input type="text" readonly id="what-remain" class="form-control" name="what_remain" value="">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" id="do_approve">اعتماد وترحيل</button>

    @else

    @endif
@else

@endif


<script>
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
</script>
