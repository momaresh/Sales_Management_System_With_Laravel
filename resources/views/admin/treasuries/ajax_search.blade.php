<table id="example2" class="table table-bordered table-hover">

    @if (!@empty($data[0]))

        <tr style="background-color: #007bff; color:white;">
            <th>تعديل</th>
            <th>كود الخرينة</th>
            <th>اسم الخرينة</th>
            <th>خزنة رئيسية</th>
            <th>اخر ايصال صرف</th>
            <th>اخر ايصال تحصيل</th>
            <th>حالة التفعيل</th>
            <th>تاريخ الاضافة</th>
            <th>تاريخ التحديث</th>
            <th>الخزن التي يتم الاستلام منها</th>
        </tr>

        @foreach ($data as $datum)
            <tr>
                <td>
                    <a href="{{ route('admin.treasuries.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
                <td>{{ $datum->id }}</td>
                <td>{{ $datum->name }}</td>
                <td>
                    @if ($datum->master == 1)
                        رئيسية
                    @else
                        غير رئيسية
                    @endif
                </td>
                <td>{{ $datum->last_exchange_arrive }}</td>
                <td>{{ $datum->last_collection_arrive }}</td>
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
                <td>
                    <a href="{{ route('admin.treasuries.details', $datum->id) }}" class="btn btn-primary">
                        عرض
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
