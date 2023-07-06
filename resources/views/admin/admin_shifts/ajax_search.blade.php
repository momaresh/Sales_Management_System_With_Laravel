<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            <th>كود الشفت</th>
            <th>اسم المستخدم</th>
            <th>اسم الخزنة</th>
            <th>تاريخ البداية</th>
            <th>تاريخ النهاية</th>
            <th>تم الانتهاء</th>
            <th>التحكم</th>
        </tr>

        @foreach ($data as $datum)
            <tr>
                @if ($datum->is_finished == 0 && $datum->admin_id == auth()->user()->id)
                    <td style="background-color: #eee880a1">{{ $datum->shift_code }}</td>
                @else
                    <td>{{ $datum->shift_code }}</td>
                @endif
                <td>{{ $datum->admin_name }}</td>
                <td>{{ $datum->treasuries_name }}</td>
                <td>{{ $datum->start_date }}</td>
                <td>{{ $datum->end_date }}</td>
                @if ($datum->is_finished == 1)
                    <td style="background-color: #c15670a1">مغلق</td>
                @else
                    <td style="background-color: #5ab6a0a1">مفتوح</td>
                @endif

                <td>

                    @if ($datum->is_finished == 0 && check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'انهاء شفت') == true)
                        <a href="{{ route('admin.admin_shifts.end_shift', $datum->id) }}" class="btn btn-danger">
                            انهاء
                        </a>
                    @endif

                    @if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'مراجعة شفت') == true && $datum->is_finished == 1 && @empty($datum->delivered_to_shift_id) && !@empty($check_shift) && $datum->allowed_review == true)
                        <button data-id="{{ $datum->id }}"  data-money="{{ $datum->money_should_delivered }}" class="btn btn-info review_shift_btn">
                            مراجعة
                        </button>
                    @endif

                    @if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'طباعة') == true)
                        <a href="{{ route('admin.admin_shifts.printA4', [$datum->id]) }}" class="btn btn-success">
                            A4 <i class="fa-solid fa-print"></i>
                        </a>
                    @endif
            </tr>
        @endforeach

    @else
        <div class="alert alert-danger">
            لا يوجد بيانات لعرضها
        </div>
    @endif

</table>

<br>
<div style="width: fit-content; margin:auto;" id="ajax_pagination_search">
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
