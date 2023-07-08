<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))
        <tr style="background-color: #007bff; color:white;">
            @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'تعديل') == true)
                <th>تعديل</th>
            @endif
            <th>كود الجرد</th>
            <th>تاريخ الجرد</th>
            <th>نوع الجرد</th>
            <th>مخزن الجرد</th>
            <th>الحالة</th>
            @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'طباعة') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'حذف') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اغلاق') == true)
                <th>التحكم</th>
            @endif
        </tr>

        @foreach ($data as $datum)
            <tr>
                @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'تعديل') == true)
                    <td>
                        <a href="{{ route('admin.inv_stores_inventory.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                @endif
                <td>{{ $datum->inventory_code }}</td>
                <td>{{ $datum->inventory_date }}</td>
                <td>
                    @if ($datum->inventory_type == 1)
                        يومي
                    @elseif ($datum->inventory_type == 2)
                        اسبوعي
                    @elseif ($datum->inventory_type == 3)
                        شهري
                    @elseif ($datum->inventory_type == 4)
                        سنوي
                    @endif
                </td>

                <td>{{ $datum['store_name'] }}</td>
                @if ($datum->is_closed == 0)
                    <td style="background-color: #5ab6a0a1;">
                        مفتوح
                    </td>
                @elseif ($datum->is_closed == 1)
                    <td style="background-color: #c15670a1;;">
                        مغلق
                    </td>
                @endif

                @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'طباعة') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'حذف') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اغلاق') == true)
                    <td>
                        @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'طباعة') == true)
                            <a href="{{ route('admin.inv_stores_inventory.printA4', $datum->id) }}" class="btn btn-success">
                                A4 <i class="fa-solid fa-print"></i>
                            </a>
                            <a href="{{ route('admin.inv_stores_inventory.details', $datum->id) }}" class="btn btn-info">
                                <i class="fa-solid fa-circle-info"></i>
                            </a>
                        @endif

                        @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اغلاق') == true)
                            <a href="{{ route('admin.inv_stores_inventory.close_header', $datum->id) }}" @if ($datum->is_closed == 1) @endif class="btn btn-warning">
                                ترحيل
                            </a>
                        @endif

                        @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'حذف') == true)
                            <a href="{{ route('admin.inv_stores_inventory.delete', $datum->id) }}" class="are_you_sure btn btn-danger">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        @endif
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
