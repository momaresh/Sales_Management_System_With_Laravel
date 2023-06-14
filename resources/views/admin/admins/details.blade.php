@extends('layout.admin')

@section('title')
    المستخدمين
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
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات المستخدم</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">

            @if (!@empty($data))

                <tr>
                    <th>كود المستخدم</th>
                    <td>{{ $data->id }}</td>
                </tr>

                <tr>
                    <th>الاسم</th>
                    <td>{{ $data->name }}</td>
                </tr>

                <tr>
                    <th>اسم المستخدم</th>
                    <td>{{ $data->user_name }}</td>
                </tr>

                <tr>
                    <th>الايميل</th>
                    <td>{{ $data->email }}</td>
                </tr>

                <tr>
                    <th>كلمة السر</th>
                    <td>{{ $data->password }}</td>
                </tr>

                <tr>
                    <th>حالة التفعيل</th>
                    <td>
                        @if ($data->active == 1)
                            مفعل
                        @else
                            غير مفعل
                        @endif
                    </td>
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
    <a href="{{ route('admin.admins.create_treasuries', $data->id) }}" style="background-color: #007bff; font-size: 20px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-save"></i> اضافة جديد
    </a>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">الخزن التي يمتلك فيها الصلاحيات</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div>
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($treasuries[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>كود الخزينة</th>
                            <th>اسم الخزينة</th>
                            <th>حالة التفعيل</th>
                            <th>تاريخ الاضافة</th>
                            <th>حذف</th>
                        </tr>

                        @foreach ($treasuries as $treasury)
                            <tr>
                                <td>{{ $treasury->treasuries_id }}</td>
                                <td>{{ $treasury->treasury_name }}</td>
                                <td>
                                    @if ($data->active == 1)
                                        مفعل
                                    @else
                                        غير مفعل
                                    @endif
                                </td>

                                <td>
                                    @if ($treasury['added_by'] != null)
                                        @php
                                            $d = new DateTime($treasury['created_at']);
                                            $date = $d->format('d/m/Y الساعة h:i:sA');
                                        @endphp

                                        {{ $date }}
                                        بواسطة
                                        {{ $treasury['added_by_name'] }}
                                    @else
                                        لم يتم تسجيل بيانات المضاف
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.admins.delete_treasuries', [$data->id, $treasury->treasuries_id]) }}" class="btn btn-danger are_you_sure">
                                        حذف
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
    <a href="{{ route('admin.admins.index') }}">المستخدمين</a>
@endsection

@section('contentheaderactive')
    عرض الصلاحيات
@endsection
