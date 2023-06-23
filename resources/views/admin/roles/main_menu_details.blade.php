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
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات القائمة الرئيسية</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">

            @if (!@empty($data))
                <tr>
                    <th>اسم الصلاحية</th>
                    <td>{{ $data->roles_name }}</td>
                </tr>

                <tr>
                    <th>اسم القائمة الرئيسية</th>
                    <td>{{ $data->roles_main_menu_name }}</td>
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
    <button data-target="#create_permission_sub_menu_modal" data-toggle="modal" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-plus-circle"></i> اضافة جديد
    </button>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات القوائم الفرعية في القائمة {{ $data->roles_name }} للصلاحية {{ $data->roles_main_menu_name }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div>
                @if (!@empty($permission_sub_menus[0]))
                    @foreach ($permission_sub_menus as $permission_sub_menu)

                        <table id="example2" class="table table-bordered table-hover">
                            <tr style="background-color: #007bff; color:white;">
                                <th>كود القائمة الفرعية</th>
                                <th>اسم القائمة الفرعية</th>
                                <th>تاريخ الاضافة</th>
                                <th>الصلاحيات</th>
                                <th>حذف</th>
                            </tr>

                                <tr>
                                    <td>{{ $permission_sub_menu->roles_sub_menu_id }}</td>
                                    <td>{{ $permission_sub_menu->sub_menu_name }}</td>
                                    <td>
                                        @if ($permission_sub_menu['added_by'] != null)
                                            @php
                                                $d = new DateTime($permission_sub_menu['created_at']);
                                                $date = $d->format('d/m/Y الساعة h:i:sA');
                                            @endphp

                                            {{ $date }}
                                            بواسطة
                                            {{ $permission_sub_menu['added_by_name'] }}
                                        @else
                                            لم يتم تسجيل بيانات المضاف
                                        @endif
                                    </td>
                                    <td>
                                        <button data-roles_id="{{ $data->roles_id }}" data-main_id="{{ $data->roles_main_menu_id }}" data-sub_id="{{ $permission_sub_menu->roles_sub_menu_id }}" id="load_control_modal_btn" class="btn btn-info">
                                            الصلاحيات
                                        </button>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.roles.delete_permission_sub_menu', [$data->roles_id, $data->roles_main_menu_id, $permission_sub_menu->roles_sub_menu_id]) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <table id="example2" class="table table-bordered table-hover">
                                <tr>
                                    <td colspan="5">
                                        <div class="row">
                                            @foreach ($permission_sub_menu['controls'] as $control)
                                                <div class="col-md-2 p-2 m-1 text-center" style="background-color: #ffc107; border-radius: 10%">
                                                    <a href="{{ route('admin.roles.delete_permission_sub_menu_control', [$data->roles_id, $data->roles_main_menu_id, $permission_sub_menu->roles_sub_menu_id, $control->roles_sub_menu_control_id]) }}" class="are_you_sure" style="color: rgb(149, 35, 35)">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                    {{ $control->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    @else
                        <div class="alert alert-danger">
                            لا يوجد بيانات لعرضها
                        </div>
                    @endif

                <br>
            </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
</div>


<div class="modal fade" id="create_permission_sub_menu_modal">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #0793a9; color: white">
            <h4 class="modal-title">اضافة قائمة فرعية</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="add_new_customer_result">
            <form action="{{ route('admin.roles.store_permission_sub_menu') }}", method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label>اسم القائمة الفرعية</label>
                        <select name="sub_menu_id[]" multiple class="form-control select2">
                            @if (@isset($sub_menus) && !@empty($sub_menus))
                                @foreach ($sub_menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <input type="hidden" name="roles_id" value="{{ $data['roles_id'] }}">
                <input type="hidden" name="roles_main_menu_id" value="{{ $data['roles_main_menu_id'] }}">

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

<div class="modal fade" id="create_permission_sub_menu_control_modal">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #0793a9; color: white">
            <h4 class="modal-title">اضافة تحكم</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="create_permission_sub_menu_control_result">

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

@section('script')
    <script>
        $(function() {
            $(document).on('click', '#load_control_modal_btn', function() {
                var roles_id = $(this).data('roles_id');
                var main_id = $(this).data('main_id');
                var sub_id = $(this).data('sub_id');

                jQuery.ajax({
                    // first argument is the where the from route to
                    url:"{{ route('admin.roles.load_control_modal') }}",
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{
                        roles_id:roles_id,
                        main_id:main_id,
                        sub_id:sub_id,
                        '_token':"{{ csrf_token() }}"
                    },
                    // If the form and everything okay
                    success:function(data){
                        $('#create_permission_sub_menu_control_result').html(data);
                        $('#create_permission_sub_menu_control_modal').modal('show');
                    },
                    // If the there is an error
                    error:function() {
                        alert('حدث خطأ ما');
                    }
                });
            })
        })
    </script>
@endsection

