<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            <th>اسم المخزن</th>
            <th>اسم الصنف</th>
            <th>نوع الوحدة</th>
            <th>الكمية</th>
            <th>سعر الواحدة</th>
            <th>سعر الكل</th>
            <th>تاريخ الانتاج</th>
            <th>تاريخ الانتهاء</th>
        </tr>

        @foreach ($data as $datum)
            <tr>
                <td>{{ $datum->store_name }}</td>
                <td>{{ $datum->item_name }}</td>
                <td>{{ $datum->unit_name }}</td>
                <td>{{ $datum->quantity }}</td>
                <td>{{ $datum->unit_cost_price }}</td>
                <td>{{ $datum->total_cost_price }}</td>
                <td>{{ $datum->production_date }}</td>
                <td>{{ $datum->expire_date }}</td>
            </tr>
        @endforeach

    @else
        <div class="alert alert-danger">
            لا يوجد بيانات لعرضها
        </div>
    @endif

</table>

<br>
<div style="width: fit-content; margin:auto;" id="ajax_search_pagination">
    {{ $data->links() }}
</div>

<script>
    $(function () {
        if ($(window).width() < 1100) {
            $('table').addClass('table-responsive');
        }
        else {
            $('table').removeClass('table-responsive');
        }
    });
</script>
