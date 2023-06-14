<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            <th>تعديل</th>
            <th>كود الفاتورة</th>
            <th>اسم المورد</th>
            <th>اسم المندوب</th>
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
                    <a href="{{ route('admin.purchase_header.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
                <td>{{ $datum->pill_code }}</td>
                <td>{{ $datum['customer_name'] }}</td>
                <td>{{ $datum['delegate_name'] }}</td>
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
                <td>
                    @if ($datum->is_approved == 0)
                        مفتوحة
                    @elseif ($datum->is_approved == 1)
                        معتمدة
                    @endif
                </td>
                <td>{{ $datum->total_cost }}</td>
                <td>
                    <a href="{{ route('admin.purchase_header.details', $datum->id) }}" style="color: rgb(39, 149, 35); font-size: 25px;">
                        <i class="fa-solid fa-circle-info"></i>
                    </a>
                </td>
                <td>
                    <a href="{{ route('admin.purchase_header.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
