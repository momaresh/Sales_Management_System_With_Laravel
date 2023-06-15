<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            <th>تعديل</th>
            <th>كود الفاتورة</th>
            <th>اسم المورد</th>
            <th>اسم المخزن</th>
            <th>نوع الفاتورة</th>
            <th>تاريخ الفاتورة</th>
            <th>حالة الفاتورة</th>
            <th>اجمالي الفاتورة</th>
            <th>التفاصيل</th>
            <th>حذف</th>
        </tr>

        @foreach ($data as $datum)
            <tr>
                <td>
                    <button data-id={{ $datum->id }} id="update_pill" class="btn" style="color: rgb(149, 35, 35);; font-size: 27px;">
                        <i class="fa fa-edit"></i>
                    </button>
                </td>
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
                <td style="background-color: #c15670a1;">
                    معتمدة
                </td>
                @endif

                <td>{{ $datum->total_cost }}</td>
                <td>
                    <button data-id={{ $datum->id }} id="update_pill" class="btn" style="color: rgb(38, 123, 29); font-size: 25px;">
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                </td>
                <td>
                    <a href="{{ route('admin.purchase_order_header_general_return.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
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
