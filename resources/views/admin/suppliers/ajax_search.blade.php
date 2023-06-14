<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            <th>تعديل</th>
            <th>كود المورد</th>
            <th>اسم المورد</th>
            <th>رقم حساب المورد</th>
            <th>الرصيد</th>
            <th>رصيد اول المدة</th>
            <th>حذف</th>
        </tr>

        @foreach ($data as $datum)
            <tr>
                <td>
                    <a href="{{ route('admin.suppliers.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
                <td>{{ $datum['supplier_code'] }}</td>
                <td>{{ $datum->first_name }}  {{ $datum->last_name }}</td>
                <td>{{ $datum['account_number'] }}</td>
                <td>{{ $datum['supplier_balance'] }}</td>
                <td>{{ $datum['start_balance'] }}</td>
                <td>
                    <a href="{{ route('admin.suppliers.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
