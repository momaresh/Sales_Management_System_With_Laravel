@extends('layout.admin')

@section('title')
    الصلاحيات
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
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الصلاحية</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">

            @if (!@empty($data))

                <tr>
                    <th>كود الصلاحية</th>
                    <td>{{ $data->id }}</td>
                </tr>

                <tr>
                    <th>اسم الصلاحية</th>
                    <td>{{ $data->name }}</td>
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
    <button data-target="#create_permission_main_menu_modal" data-toggle="modal" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-plus-circle"></i> اضافة جديد
    </button>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات القوائم الرئيسية في الصلاخية</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div>
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($permission_main_menus[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>كود القائمة الرئيسية</th>
                            <th>اسم القائمة الرئيسية</th>
                            <th>تاريخ الاضافة</th>
                            <th>الصلاحيات</th>
                            <th>حذف</th>
                        </tr>

                        @foreach ($permission_main_menus as $permission_main_menu)
                            <tr>
                                <td>{{ $permission_main_menu->roles_main_menu_id }}</td>
                                <td>{{ $permission_main_menu->main_menu_name }}</td>
                                <td>
                                    @if ($permission_main_menu['added_by'] != null)
                                        @php
                                            $d = new DateTime($permission_main_menu['created_at']);
                                            $date = $d->format('d/m/Y الساعة h:i:sA');
                                        @endphp

                                        {{ $date }}
                                        بواسطة
                                        {{ $permission_main_menu['added_by_name'] }}
                                    @else
                                        لم يتم تسجيل بيانات المضاف
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.roles.main_menu_details', [$data->id, $permission_main_menu->roles_main_menu_id]) }}" class="btn btn-info">
                                        الصلاحيات
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.roles.delete_permission_main_menu', [$data->id, $permission_main_menu->roles_main_menu_id]) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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


<div class="modal fade" id="create_permission_main_menu_modal">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #0793a9; color: white">
            <h4 class="modal-title">اضافة قائمة رئيسية</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="add_new_customer_result">
            <form action="{{ route('admin.roles.store_permission_main_menu') }}", method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>اسم القائمة الرئيسية</label>
                        <select name="main_menu_id[]" multiple class="form-control select2">
                            @if (@isset($main_menus) && !@empty($main_menus))
                                @foreach ($main_menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <input type="hidden" name="roles_id" value="{{ $data['id'] }}">

                <div class="col-md-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary">اضافة</button>
                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default " data-dismiss="modal">الغاء</button>
        </div>
        </div>
    </div>
</div>

@endsection

@section('contentheader')
    الصلاحيات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.roles.index') }}">الصلاحيات</a>
@endsection

@section('contentheaderactive')
    عرض التفاصيل
@endsection
