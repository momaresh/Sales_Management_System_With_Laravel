<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            @if (check_control_menu_role('الحسابات', 'الموردين' , 'تعديل') == true)
                <th>تعديل</th>
            @endif
            <th>كود المورد</th>
            <th>اسم المورد</th>
            <th>رقم حساب المورد</th>
            <th>الرصيد</th>
            <th>رصيد اول المدة</th>
            @if (check_control_menu_role('الحسابات', 'الموردين' , 'التفاصيل') == true)
                <th>التفاصيل</th>
            @endif

            @if (check_control_menu_role('الحسابات', 'الموردين' , 'حذف') == true)
                <th>حذف</th>
            @endif
        </tr>

        @foreach ($data as $datum)
            <tr>
                @if (check_control_menu_role('الحسابات', 'الموردين' , 'تعديل') == true)
                    <td>
                        <a href="{{ route('admin.suppliers.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                @endif

                <td>{{ $datum['supplier_code'] }}</td>
                <td>{{ $datum->first_name }}  {{ $datum->last_name }}</td>
                <td>{{ $datum['account_number'] }}</td>
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
                @if (check_control_menu_role('الحسابات', 'الموردين' , 'التفاصيل') == true)
                    <td>
                        <button data-id="{{ $datum->id }}" class="details_button btn" style="color: rgb(38, 123, 29); font-size: 25px;">
                            <i class="fa-solid fa-circle-info"></i>
                        </button>
                    </td>
                @endif
                @if (check_control_menu_role('الحسابات', 'الموردين' , 'حذف') == true)
                    <td>
                        <a href="{{ route('admin.suppliers.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
