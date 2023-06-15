<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            <th>تعديل</th>
            <th>كود الحساب</th>
            <th>رقم الحساب</th>
            <th>اسم صاحب الحساب</th>
            <th>نوع الحساب</th>
            <th>حساب الأب</th>
            <th>الرصيد</th>
            <th>حالة التفعيل</th>
            <th>حذف</th>
        </tr>

        @foreach ($data as $datum)
            <tr>
                <td>
                    <a href="{{ route('admin.accounts.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
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
                <td>{{ $datum->parent_account_number }}</td>
                <td>{{ $datum->current_balance }}</td>
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
                    <a href="{{ route('admin.accounts.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
