<div class="form-group">
    <label>بيانات الباتشات في المخزن</label>
    <select  id="batch_id_add" class="form-control select2 batch_id_add_return" style="width: 100%;">
        <option value="new">اضافة في باتش جديدة</option>
        @if (@isset($item_card_batches) && !@empty($item_card_batches))
            @foreach ($item_card_batches as $info)
                <option
                    @if ($batch_id == $info->id)
                        selected
                    @endif
                    data-batch_id="{{ $info->id }}"
                    data-quantity="{{ $info->quantity }}"
                    data-production_date="{{ $info->production_date }}"
                    data-expire_date="{{ $info->expire_date }}"
                    value="{{ $info->id }}"> {{ $info->all_data }}
                </option>
            @endforeach
        @endif
    </select>
</div>
