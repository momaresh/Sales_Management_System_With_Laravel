<form action="{{ route('admin.inv_stores_inventory.edit_detail') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>الكمية في الباتش</label>
                <input type="text" name="old_quantity" readonly id="old_quantity" value="{{ $detail->old_quantity }}" class="form-control">
            </div>
        </div>

        <input type="hidden" name="inventory_id" value="{{ $detail->inv_stores_inventory_header_id }}">
        <input type="hidden" name="detail_id" value="{{ $detail->id }}">

        <div class="col-md-4">
            <div class="form-group">
                <label>الكمية في الجرد</label>
                <input type="text" name="new_quantity" value="{{ $detail->new_quantity }}" id="new_quantity" class="form-control">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>سبب النقص او الزيادة</label>
                <textarea name="notes" id="" class="form-control" >{{ $detail->notes }}</textarea>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" id="update_batch_detail" class="btn btn-primary ">تعديل</button>
        </div>
    </div>
</form>
