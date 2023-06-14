<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            <th>كود العملية</th>
            <th>كود الشفت</th>
            <th>نوع الحركة</th>
            <th>اسم المستخدم</th>
            <th>اسم الخزنة</th>
            <th>اسم صاحب الحساب</th>
            <th>رقم آخر تحصيل من الخزنة</th>
            <th>المبلغ المحصل</th>
            <th>تم الاعتماد</th>
        </tr>

        @foreach ($data as $datum)
            <tr>
                <td>{{ $datum->transaction_code }}</td>
                <td>{{ $datum->shift_code }}</td>
                <td>{{ $datum->move_type_name }}</td>
                <td>{{ $datum->admin_name }}</td>
                <td>{{ $datum->treasuries_name }}</td>
                <td>{{ $datum->account_name }} <span class="my-col-main">({{ $datum->account_type }})</span></td>
                <td>{{ $datum->last_arrive }}</td>
                <td>{{ $datum->money }}</td>
                @if ($datum->is_approved == 1)
                    <td style="background-color: #5ab6a0a1">نعم</td>
                @else
                    <td style="background-color: #c15670a1">لا</td>
                @endif
            </tr>
        @endforeach

    @else
        <div class="alert alert-danger">
            لا يوجد بيانات لعرضها
        </div>
    @endif

</table>
