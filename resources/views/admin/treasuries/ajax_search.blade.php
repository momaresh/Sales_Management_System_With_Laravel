<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            @if (check_control_menu_role('الحسابات', 'الخزن' , 'تعديل') == true)
                <th>تعديل</th>
            @endif
            <th>كود الخرينة</th>
            <th>اسم الخرينة</th>
            <th>رقم الحساب</th>
            <th>الرصيد الحالي</th>
            <th>خزنة رئيسية</th>
            <th>اخر ايصال صرف</th>
            <th>اخر ايصال تحصيل</th>
            <th>اخر ايصال آجل</th>
            <th>حالة التفعيل</th>
            @if (check_control_menu_role('الحسابات', 'الخزن' , 'التفاصيل') == true)
                <th>الخزن التي يتم الاستلام منها</th>
            @endif
        </tr>

        @foreach ($data as $datum)
            <tr>
                @if (check_control_menu_role('الحسابات', 'الخزن' , 'تعديل') == true)
                    <td>
                        <a href="{{ route('admin.treasuries.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                @endif
                <td>{{ $datum->treasury_code }}</td>
                <td>{{ $datum->name }}</td>
                <td>{{ $datum->account_number }}</td>
                <td>
                    @if($datum->current_balance == 0)
                    متزن
                    @elseif ($datum->current_balance > 0)
                        مدين ({{ $datum->current_balance }})
                    @else
                        دائن ({{ $datum->current_balance * (-1) }})
                    @endif
                </td>
                <td>
                    @if ($datum->master == 1)
                        رئيسية
                    @else
                        غير رئيسية
                    @endif
                </td>
                <td>{{ $datum->last_exchange_arrive }}</td>
                <td>{{ $datum->last_collection_arrive }}</td>
                <td>{{ $datum->last_unpaid_arrive }}</td>
                @if ($datum->active == 1)
                <td style="background-color: #5ab6a0a1;">
                    مفعل
                </td>
                @elseif ($datum->active == 0)
                <td style="background-color: #c15670a1;;">
                    غير مفعل
                </td>
                @endif
                @if (check_control_menu_role('الحسابات', 'الخزن' , 'التفاصيل') == true)
                    <td>
                        <a href="{{ route('admin.treasuries.details', $datum->id) }}" class="btn btn-info">
                            عرض
                        </a>
                    </td>
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
