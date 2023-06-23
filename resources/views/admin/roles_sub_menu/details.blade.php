@extends('layout.admin')

@section('title')
    التحكم للقوائم الفرعية
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
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات القائمة الفرعية</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">

            @if (!@empty($data))

                <tr>
                    <th>كود القائمة الفرعية</th>
                    <td>{{ $data->id }}</td>
                </tr>

                <tr>
                    <th>اسم القائمة الفرعية</th>
                    <td>{{ $data->name }}</td>
                </tr>

                <tr>
                    <th>اسم القائمة الرئيسية</th>
                    <td>{{ $data->main_menu_name }}</td>
                </tr>

                <tr>
                    <th>حالة التفعيل</th>
                    @if ($data->active == 1)
                    <td style="background-color: #5ab6a0a1;">
                        مفعلة
                    </td>
                    @elseif ($data->active == 0)
                    <td style="background-color: #c15670a1;;">
                        غير مفعلة
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
                            {{ $data['added_by_admin'] }}
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
                            {{ $data['updated_by_admin'] }}
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
    <a href="{{ route('admin.roles_sub_menu.create_control', $data->id) }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-plus-circle"></i> اضافة جديد
    </a>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات التحكم في القائمة</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div>
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($controls[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>تعديل</th>
                            <th>كود التحكم</th>
                            <th>اسم التحكم</th>
                            <th>اسم القائمة الفرعية</th>
                            <th>حالة التفعيل</th>
                            <th>تاريخ الاضافة</th>
                            <th>تاريخ التحديث</th>
                            <th>حذف</th>
                        </tr>

                        @foreach ($controls as $control)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.roles_sub_menu.edit_control', $control->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td>{{ $control->id }}</td>
                                <td>{{ $control->name }}</td>
                                <td>{{ $control->sub_menu_name }}</td>
                                @if ($control->active == 1)
                                <td style="background-color: #5ab6a0a1;">
                                    مفعلة
                                </td>
                                @elseif ($control->active == 0)
                                <td style="background-color: #c15670a1;;">
                                    غير مفعلة
                                </td>
                                @endif
                                <td>
                                    @if ($control['added_by'] != null)
                                        @php
                                            $d = new DateTime($control['created_at']);
                                            $date = $d->format('d/m/Y الساعة h:i:sA');
                                        @endphp

                                        {{ $date }}
                                        بواسطة
                                        {{ $control['added_by_name'] }}
                                    @else
                                        لم يتم تسجيل بيانات المضاف
                                    @endif
                                </td>
                                <td>
                                    @if ($control['updated_by'] != null)
                                        @php
                                            $d = new DateTime($control['updated_at']);
                                            $date = $d->format('d/m/Y الساعة h:i:sA');
                                        @endphp

                                        {{ $date }}
                                        بواسطة
                                        {{ $control['updated_by_name'] }}
                                    @else
                                        لا يوجد اي تحديث
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.roles_sub_menu.delete_control', $control->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
    الصلاحيات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.roles_sub_menu.index') }}">القوائم الفرعية</a>
@endsection

@section('contentheaderactive')
    عرض التفاصيل
@endsection
