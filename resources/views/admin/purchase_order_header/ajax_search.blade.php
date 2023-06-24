<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'تعديل') == true)
                <th>تعديل</th>
            @endif
            <th>كود الفاتورة</th>
            <th>اسم المورد</th>
            <th>اسم المخزن</th>
            <th>نوع الفاتورة</th>
            <th>تاريخ الفاتورة</th>
            <th>حالة الفاتورة</th>
            <th>اجمالي الفاتورة</th>
            @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'حذف') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'طباعة') == true)
                <th>التحكم</th>
            @endif
        </tr>

        @foreach ($data as $datum)
            <tr>
                @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'تعديل') == true)
                    <td>
                        <a href="{{ route('admin.purchase_header.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                @endif

                <td>{{ $datum->pill_code }}</td>
                <td>{{ $datum['supplier_name'] }}</td>
                <td>{{ $datum['store_name'] }}</td>
                <td>
                    @if ($datum->pill_type == 1)
                        نقداً
                    @elseif ($datum->pill_type == 2)
                        آجل
                    @else
                        غير محدد
                    @endif
                </td>
                <td>{{ $datum->order_date }}</td>
                @if ($datum->is_approved == 0)
                <td style="background-color: #5ab6a0a1;">
                    مفتوحة
                </td>
                @elseif ($datum->is_approved == 1)
                <td style="background-color: #c15670a1;;">
                    معتمدة
                </td>
                @endif

                <td>{{ $datum->total_cost }}</td>
                @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'حذف') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'طباعة') == true)
                    <td>
                        @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'طباعة') == true)
                            <a href="{{ route('admin.purchase_header.printA4', [$datum->id, 'A4']) }}" class="btn btn-success">
                                A4 <i class="fa-solid fa-print"></i>
                            </a>
                            <a href="{{ route('admin.purchase_header.printA4', [$datum->id, 'A6']) }}" class="btn btn-success">
                                A6 <i class="fa-solid fa-print"></i>
                            </a>
                        @endif

                        @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'التفاصيل') == true)
                            <a href="{{ route('admin.purchase_header.details', $datum->id) }}" class="btn btn-info mt-1">
                                <i class="fa-solid fa-circle-info"></i>
                            </a>
                        @endif

                        @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'حذف') == true)
                            <a href="{{ route('admin.purchase_header.delete', $datum->id) }}" class="are_you_sure btn btn-danger mt-1">
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
