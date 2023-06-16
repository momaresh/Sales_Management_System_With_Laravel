<tr>
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
