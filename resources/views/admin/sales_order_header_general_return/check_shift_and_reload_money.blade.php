<div class="col-md-6">
    <div class="form-group">
        <label>اسم الخزينة</label>
        <input type="text" readonly class="form-control" name="treasuries_id" id="treasuries-id" value="{{ $check_shift['treasuries_name'] }}">
    </div>
</div>

<input type="hidden" class="form-control" id="shift-code" name="shift_code" value="{{ $check_shift['id'] }}">
<input type="hidden" class="form-control" name="treasuries_id" value="{{ $check_shift['treasuries_id'] }}">

<div class="col-md-6">
    <div class="form-group">
        <label>اجمالي الخزينة</label>
        <input type="text" readonly class="form-control treasury_money_return" id="treasury-money" name="treasury_money" value="{{ $check_shift['treasuries_money'] }}">
    </div>
</div>
