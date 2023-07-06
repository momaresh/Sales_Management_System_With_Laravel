<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            @if (check_control_menu_role('الحسابات', 'الحسابات' , 'تعديل') == true)
                <th>تعديل</th>
            @endif
            <th>كود الحساب</th>
            <th>رقم الحساب</th>
            <th>اسم صاحب الحساب</th>
            <th>نوع الحساب</th>
            <th>حساب الأب</th>
            <th>الرصيد</th>
            <th>رصيد اول المدة</th>
            <th>حالة التفعيل</th>
            @if (check_control_menu_role('الحسابات', 'الحسابات' , 'حذف') == true)
                <th>حذف</th>
            @endif
        </tr>

        @foreach ($data as $datum)
            <tr>
                @if (check_control_menu_role('الحسابات', 'الحسابات' , 'تعديل') == true)
                    <td>
                        <a href="{{ route('admin.accounts.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                @endif
                <td>{{ $datum->id }}</td>
                <td>{{ $datum->account_number }}</td>
                <td>
                @php
                if (in_array($datum->account_type, [2, 3, 4, 5])):
                    echo "$datum->account_person_name";
                else:
                    echo "$datum->notes";
                endif;
                @endphp
                </td>
                <td>{{ $datum->account_type_name }}</td>
                <td>{{ $datum->parent_account_name }}</td>
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
                    @if($datum->start_balance == 0)
                        متزن
                    @elseif ($datum->start_balance > 0)
                        مدين ({{ $datum->start_balance }})
                    @else
                        دائن ({{ $datum->start_balance * (-1) }})
                    @endif
                </td>
                @if ($datum->active == 1)
                <td style="background-color: #5ab6a0a1;">
                    مفعل
                </td>
                @elseif ($datum->active == 0)
                <td style="background-color: #c15670a1;;">
                    غير مفعل
                </td>
                @endif

                @if (check_control_menu_role('الحسابات', 'الحسابات' , 'حذف') == true)
                    <td>
                        <a href="{{ route('admin.accounts.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
                            <i class="fa-solid fa-trash-can"></i>
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
