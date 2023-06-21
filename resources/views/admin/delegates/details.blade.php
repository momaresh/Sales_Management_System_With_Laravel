<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-responsive">

                @if (!@empty($data))
                    <tr>
                        <th>كود المندوب</th>
                        <td>{{ $data->delegate_code }}</td>
                    </tr>

                    <tr>
                        <th>اسم المندوب</th>
                        <td>{{ $data->first_name }}  {{ $data->last_name }}</td>
                    </tr>

                    <tr>
                        <th>رصيد اول المدة</th>
                        <td>
                            @if($data->start_balance == 0)
                                متزن
                            @elseif ($data->start_balance > 0)
                                مدين ({{ $data->start_balance }})
                            @else
                                دائن ({{ $data->start_balance * (-1) }})
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>الرصيد الحالي</th>
                        <td>
                            @if($data->current_balance == 0)
                                متزن
                            @elseif ($data->current_balance > 0)
                                مدين ({{ $data->current_balance }})
                            @else
                                دائن ({{ $data->current_balance * (-1) }})
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>الهاتف</th>
                        <td>{{ $data->phone }}</td>
                    </tr>

                    <tr>
                        <th>العنوان</th>
                        <td>{{ $data->address }}</td>
                    </tr>

                    <tr>
                        <th>الحساب الاب</th>
                        <td>{{ $data->parent_account_name }} ({{ $data->parent_account }})</td>
                    </tr>

                    <tr>
                        <th>نوع التحصيل</th>
                        <td>{{ $data->percent_type }}</td>
                    </tr>

                    <tr>
                        <th>التحصيل في حالة البيع بالجملة</th>
                        <td>{{ $data->group }}</td>
                    </tr>

                    <tr>
                        <th>التحصيل في حالة البيع بالنص جملة</th>
                        <td>{{ $data->half_group }}</td>
                    </tr>

                    <tr>
                        <th>التحصيل في حالة البيع بالقطاعي(بالحبة)</th>
                        <td>{{ $data->one }}</td>
                    </tr>

                    <tr>
                        <th>الحالة</th>
                        @if ($data->active == 1)
                        <td style="background-color: #5ab6a0a1;">
                            مفعل
                        </td>
                        @elseif ($data->active == 0)
                        <td style="background-color: #c15670a1;;">
                            غير مفعل
                        </td>
                        @endif
                    </tr>

                    <tr>
                        <th>تم الاضافة</th>

                        <td>
                            @if ($data['added_by'] != null)
                                @php
                                    $d = new DateTime($data['created_at']);
                                    $date = $d->format('d/m/Y الساعة h:i:sA');
                                @endphp

                                {{ $date }}
                                بواسطة
                                {{ $data['added_by_name'] }}
                            @else
                                لا يوجد اي بيانات
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>اخر تحديث</th>

                        <td>
                            @if ($data['updated_by'] != null)
                                @php
                                    $d = new DateTime($data['updated_at']);
                                    $date = $d->format('d/m/Y الساعة h:i:sA');
                                @endphp

                                {{ $date }}
                                بواسطة
                                {{ $data['updated_by_name'] }}
                            @else
                                لا يوجد اي تحديث
                            @endif
                        </td>
                    </tr>
                @else
                    <div class="alert alert-danger">
                        لا يوجد بيانات لعرضها
                    </div>
                @endif

            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
