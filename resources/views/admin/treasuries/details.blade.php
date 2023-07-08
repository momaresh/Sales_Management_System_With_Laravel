@extends('layout.admin')

@section('title')
    تفاصيل الخزن
@endsection

@section('content')

@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
@endif

<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الخزينة الرئيسية</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">

            @if (!@empty($data))
                <tr>
                    <th>كود الخزينة</th>
                    <td>{{ $data->treasury_code }}</td>
                </tr>

                <tr>
                    <th>اسم الخزينة</th>
                    <td>{{ $data->name }}</td>
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
                    <th>هل رئيسية</th>
                    <td>
                        @if ($data->master == 1)
                            نعم
                        @else
                            لا
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>حالة الخزينة</th>
                    <td>
                        @if ($data->active == 1)
                            مفعل
                        @else
                            غير مفعل
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>اخر ايصال صرف</th>
                    <td>{{ $data->last_exchange_arrive }}</td>
                </tr>

                <tr>
                    <th>اخر ايصال تحصيل</th>
                    <td>{{ $data->last_collection_arrive }}</td>
                </tr>

                <tr>
                    <th>اخر ايصال آجل</th>
                    <td>{{ $data->last_unpaid_arrive }}</td>
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
                <div class="text-danger">
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


<div>
    @if (check_control_menu_role('الحسابات', 'الخزن' , 'اضافة خزنة استلام') == true)
        <a href="{{ route('admin.treasuries_delivery.create', $data->id) }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-plus-circle"></i> اضافة جديد
        </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الخزنات التي تقوم بالتسليم</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div>
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($treasuries[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>اسم الخزينة</th>
                            <th>تاريخ الاضافة</th>
                            @if (check_control_menu_role('الحسابات', 'الخزن' , 'حذف خزنة استلام') == true)
                                <th>حذف</th>
                            @endif
                        </tr>

                        @foreach ($treasuries as $treasury)
                            <tr>
                                <td>{{ $treasury->treasury_name }}</td>

                                <td>
                                    @if ($treasury['added_by'] != null)
                                        @php
                                            $d = new DateTime($treasury['created_at']);
                                            $date = $d->format('d/m/Y الساعة h:i:sA');
                                        @endphp

                                        {{ $date }}
                                        بواسطة
                                        {{ $treasury['added_by_admin'] }}
                                    @else
                                        لم يتم تسجيل بيانات المضاف
                                    @endif
                                </td>

                                @if (check_control_menu_role('الحسابات', 'الخزن' , 'حذف خزنة استلام') == true)
                                    <td>
                                        <a href="{{ route('admin.treasuries_delivery.delete', [$treasury->treasury_id, $data->id]) }}" class="btn btn-danger are_you_sure">
                                            حذف
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
            </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>

@endsection

@section('contentheader')
    الحسابات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.treasuries.index') }}">الخزينة</a>
@endsection

@section('contentheaderactive')
    عرض التفاصيل
@endsection
