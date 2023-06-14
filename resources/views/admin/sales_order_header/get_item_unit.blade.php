<div class="form-group">
    <label>بيانات وحدات الصنف</label>
    <select  id="unit_id_add" class="form-control select2" style="width: 100%;">
        <option value="">اختر الوحده</option>
        @if (@isset($item_card_data) && !@empty($item_card_data))
            @if($item_card_data['does_has_retailunit']==1)
                <option data-isparentunit="1" selected   value="{{ $item_card_data['unit_id'] }}"> {{ $item_card_data['parent_unit_name']  }} (وحده اب) </option>
                <option  data-isparentunit="0"   value="{{ $item_card_data['retail_unit_id'] }}"> {{ $item_card_data['retail_unit_name']  }} (وحدة تجزئة) </option>
            @else
                <option   data-isparentunit="1" selected   value="{{ $item_card_data['unit_id'] }}"> {{ $item_card_data['parent_unit_name']  }} (وحده اب) </option>
            @endif
        @endif
    </select>
</div>

