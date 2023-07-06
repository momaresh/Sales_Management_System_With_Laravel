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
            @if ($data->is_approved == 0)
                <th>حذف</th>
            @endif
        </tr>
    </thead>

    <tbody id="add_new_item_row_result">
        @foreach ($items as $d)
            <tr>
                <input type="hidden" name="total_price_array[]" class="total_price_array" value="{{ $d['total_price'] }}">


                <td>{{ $d['store_name'] }}</td>
                <td>{{ $d['item_name'] }}</td>
                <td>{{ $d['sales_type_name'] }}</td>
                <td>{{ $d['unit_name'] }}</td>
                <td>{{ $d['quantity'] }}</td>
                <td>{{ $d['unit_price'] }}</td>
                <td>{{ $d['total_price'] }}</td>
                @if ($data->is_approved == 0)
                    <td><button data-id="{{ $d['id'] }}" class="btn btn-danger remove_item_active"><i class="fa-solid fa-trash-can"></i></button></td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
