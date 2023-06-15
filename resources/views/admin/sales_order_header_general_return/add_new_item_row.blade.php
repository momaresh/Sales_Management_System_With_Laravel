<tr>
    <input type="hidden" name="store_id_array[]" class="store_id_array" value="{{ $data['store_id'] }}">
    <input type="hidden" name="sales_type_array[]" class="sales_type_array" value="{{ $data['sales_type'] }}">
    <input type="hidden" name="item_code_array[]" class="item_code_array" value="{{ $data['item_code'] }}">
    <input type="hidden" name="unit_id_array[]" class="unit_id_array" value="{{ $data['unit_id'] }}">
    <input type="hidden" name="batch_id_array[]" class="batch_id_array" value="{{ $data['batch_id'] }}">
    <input type="hidden" name="quantity_array[]" class="quantity_array" value="{{ $data['quantity'] }}">
    <input type="hidden" name="unit_price_array[]" class="unit_price_array" value="{{ $data['unit_price'] }}">
    <input type="hidden" name="total_price_array[]" class="total_price_array" value="{{ $data['total_price'] }}">


    <td>{{ $data['store_name'] }}</td>
    <td>{{ $data['item_name'] }}</td>
    <td>{{ $data['sales_type_name'] }}</td>
    <td>{{ $data['unit_name'] }}</td>
    <td>{{ $data['quantity'] }}</td>
    <td>{{ $data['unit_price'] }}</td>
    <td>{{ $data['total_price'] }}</td>
    <td><button data-id="{{ $data['id'] }}" class="btn btn-danger remove_item_active"><i class="fa-solid fa-trash-can"></i></button></td>
</tr>
