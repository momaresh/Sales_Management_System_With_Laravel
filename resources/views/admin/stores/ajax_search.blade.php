<table id="example2" class="table table-bordered table-hover table-responsive">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            @if (check_control_menu_role('المخازن', 'المخازن' , 'تعديل') == true)
                <th>تعديل</th>
            @endif
            <th>كود المخزن</th>
            <th>اسم المخزن</th>
            <th>الهاتف</th>
            <th>العنوان</th>
            <th>حالة التفعيل</th>
            <th>تاريخ الاضافة</th>
            <th>تاريخ التحديث</th>
            @if (check_control_menu_role('المخازن', 'المخازن' , 'حذف') == true)
                <th>حذف</th>
            @endif
        </tr>

        @foreach ($data as $datum)
            <tr>

                @if (check_control_menu_role('المخازن', 'المخازن' , 'تعديل') == true)
                    <td>
                        <a href="{{ route('admin.stores.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                @endif

                <td>{{ $datum->id }}</td>
                <td>{{ $datum->name }}</td>
                <td>{{ $datum->phone }}</td>
                <td>{{ $datum->address }}</td>
                @if ($datum->active == 1)
                <td style="background-color: #5ab6a0a1;">
                    مفعل
                </td>
                @elseif ($datum->active == 0)
                <td style="background-color: #c15670a1;;">
                    غير مفعل
                </td>
                @endif
                <td>
                    @if ($datum['added_by'] != null)
                        @php
                            $d = new DateTime($datum['created_at']);
                            $date = $d->format('d/m/Y الساعة h:i:sA');
                        @endphp

                        {{ $date }}
                        بواسطة
                        {{ $datum['added_by_name'] }}
                    @else
                        لم يتم تسجيل بيانات المضاف
                    @endif
                </td>
                <td>
                    @if ($datum['updated_by'] != null)
                        @php
                            $d = new DateTime($datum['updated_at']);
                            $date = $d->format('d/m/Y الساعة h:i:sA');
                        @endphp

                        {{ $date }}
                        بواسطة
                        {{ $datum['updated_by_name'] }}
                    @else
                        لا يوجد اي تحديث
                    @endif
                </td>
                @if (check_control_menu_role('المخازن', 'المخازن' , 'حذف') == true)
                    <td>
                        <a href="{{ route('admin.stores.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
