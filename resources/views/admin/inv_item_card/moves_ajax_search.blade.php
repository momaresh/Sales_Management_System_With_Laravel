<table id="example1" class="table table-bordered table-hover">

    @if (!@empty($moves[0]))

        <tr style="background-color: #007bff; color:white;">
            <th>المخزن</th>
            <th>القسم</th>
            <th>الحركة</th>
            <th>البيان</th>
            <th>الكمية قبل الحركة</th>
            <th>الكمية بعد الحركة</th>
            <th>تم الاضافة</th>
        </tr>

        @foreach ($moves as $move)
            <tr>
                <td>{{ $move->store_name }}</td>
                <td>{{ $move->category_name }}</td>
                <td>{{ $move->type_name }}</td>
                <td>{{ $move->byan }}</td>
                <td><span style="color: #06a782">الكمية في المخزن الحالي {{ $move->quantity_before_movement_in_current_store }}</span> <span style="color: #ad002ba1">الكمية في كل المخارن {{ $move->quantity_before_movement }}</span></td>
                <td><span style="color: #06a782">الكمية في المخزن الحالي {{ $move->quantity_after_movement_in_current_store }}</span> <span style="color: #ad002ba1">الكمية في كل المخارن {{ $move->quantity_after_movement }}</span></td>
                <td>
                    @if ($move['added_by'] != null)
                        @php
                            $d = new DateTime($move['created_at']);
                            $date = $d->format('d/m/Y الساعة h:i:sA');
                        @endphp

                        {{ $date }}
                        بواسطة
                        {{ $move['added_by_name'] }}
                    @else
                        لا يوجد اي بيانات
                    @endif
                </td>
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
    {{ $moves->links() }}
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
