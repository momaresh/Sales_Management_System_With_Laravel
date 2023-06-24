<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))
        <tr style="background-color: #007bff; color:white;">
            @if (check_control_menu_role('المخازن', 'الاصناف' , 'تعديل') == true)
                <th>تعديل</th>
            @endif
            <th>الكود</th>
            <th>الاسم</th>
            <th>النوع</th>
            <th>الفئة</th>
            <th>الصنف الاب</th>
            <th>الوحدة الاب</th>
            <th>الوحدة التجزئة</th>
            <th>حالة التفعيل</th>
            @if (check_control_menu_role('المخازن', 'الاصناف' , 'التفاصيل') == true)
                <th>التفاصيل</th>
            @endif
            @if (check_control_menu_role('المخازن', 'الاصناف' , 'حذف') == true)
                <th>حذف</th>
            @endif
        </tr>

        @foreach ($data as $datum)
            <tr>
                @if (check_control_menu_role('المخازن', 'الاصناف' , 'تعديل') == true)
                    <td>
                        <a href="{{ route('admin.inv_item_card.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                @endif

                <td>{{ $datum->item_code }}</td>
                <td>{{ $datum->name }}</td>
                <td>
                    @if ($datum->item_type == 1)
                        مخزني
                    @elseif ($datum->item_type == 2)
                        استهلاكي بتاريخ صلاحية
                    @elseif ($datum->item_type == 3)
                        عهدة
                    @else
                        غير محدد
                    @endif
                </td>
                <td>{{ $datum->inv_itemcard_categories_name }}</td>
                <td>{{ $datum->parent_inv_itemcard_name }}</td>
                <td>{{ $datum->unit_name }}</td>
                <td>{{ $datum->retail_unit_name }}</td>


                @if ($datum->active == 1)
                <td style="background-color: #5ab6a0a1;">
                    مفعل
                </td>
                @elseif ($datum->active == 0)
                <td style="background-color: #c15670a1;;">
                    غير مفعل
                </td>
                @endif

                @if (check_control_menu_role('المخازن', 'الاصناف' , 'التفاصيل') == true)
                    <td>
                        <a href="{{ route('admin.inv_item_card.details', $datum->id) }}"  style="color:#007bff; font-size: 25px;">
                            <i class="fa-solid fa-circle-info"></i>
                        </a>
                    </td>
                @endif

                @if (check_control_menu_role('المخازن', 'الاصناف' , 'تعديل') == true)
                    <td>
                        <a href="{{ route('admin.inv_item_card.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
