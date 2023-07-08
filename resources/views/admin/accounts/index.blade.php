@extends('layout.admin')

@section('title')
    الحسابات
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

<div>
    @if (check_control_menu_role('الحسابات', 'الحسابات' , 'اضافة') == true)
        <a href="{{ route('admin.accounts.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-plus-circle"></i> اضافة جديد
        </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الحسابات</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label>بحث برقم الحساب</label>
                    <input style="margin-top: 6px !important;" type="number" id="search_by_text" placeholder=" رقم الحساب" class="form-control"> <br>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                    <label>بحث بنوع الحساب</label>
                    <select name="account_type_search" id="account_type_search" class="form-control select2">
                        <option value="all"> بحث بالكل</option>

                        @if (@isset($account_types) && !@empty($account_types))

                        @foreach ($account_types as $info )
                            <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach

                        @endif

                    </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                    <label>هل الحساب أب</label>
                    <select name="is_parent_search" id="is_parent_search" class="form-control select2">
                        <option value="all"> بحث بالكل</option>
                        <option value="1"> نعم</option>
                        <option value="0"> لا</option>
                    </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                    <label>   حالة التفعيل  </label>
                        <select name="active_search" id="active_search" class="form-control select2">
                            <option value="all"> بحث بالكل</option>
                            <option     value="1"> مفعل  </option>
                            <option  value="0"> معطل</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            @if (check_control_menu_role('الحسابات', 'الحسابات' , 'تعديل') == true)
                                <th>تعديل</th>
                            @endif
                            <th>رقم الحساب</th>
                            <th>اسم صاحب الحساب</th>
                            <th>نوع الحساب</th>
                            <th>الحساب الأب</th>
                            <th>الرصيد</th>
                            <th>رصيد اول المدة</th>
                            <th>حالة التفعيل</th>
                            @if (check_control_menu_role('الحسابات', 'الحسابات' , 'حذف') == true)
                                <th>حذف</th>
                            @endif
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                @if (check_control_menu_role('الحسابات', 'الحسابات' , 'تعديل') == true)
                                    <td>
                                        <a href="{{ route('admin.accounts.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                @endif
                                <td>{{ $datum->account_number }}</td>
                                <td>{{ $datum->account_name }}</td>
                                <td>{{ $datum->account_type_name }}</td>
                                <td>{{ $datum->parent_account_name }}</td>
                                <td>
                                    @if($datum->current_balance == 0)
                                    متزن
                                    @elseif ($datum->current_balance > 0)
                                        مدين ({{ $datum->current_balance }})
                                    @else
                                        دائن ({{ $datum->current_balance * (-1) }})
                                    @endif
                                </td>
                                <td>
                                    @if($datum->start_balance == 0)
                                        متزن
                                    @elseif ($datum->start_balance > 0)
                                        مدين ({{ $datum->start_balance }})
                                    @else
                                        دائن ({{ $datum->start_balance * (-1) }})
                                    @endif
                                </td>
                                @if ($datum->active == 1)
                                <td style="background-color: #5ab6a0a1;">
                                    مفعل
                                </td>
                                @elseif ($datum->active == 0)
                                <td style="background-color: #c15670a1;;">
                                    غير مفعل
                                </td>
                                @endif

                                @if (check_control_menu_role('الحسابات', 'الحسابات' , 'حذف') == true)
                                    <td>
                                        <a href="{{ route('admin.accounts.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
                                            <i class="fa-solid fa-trash-can"></i>
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
                <div style="width: fit-content; margin:auto;">
                    {{ $data->links() }}
                </div>
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
    <a href="{{ route('admin.account_types.index') }}">انواع الحسابات</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')

    <script>
        $(function() {
            function make_search() {
                var search_by_text = $("#search_by_text").val();
                var account_type = $("#account_type_search").val();
                var is_parent = $("#is_parent_search").val();
                var active_search = $("#active_search").val();

                jQuery.ajax({
                    url: "{{ route('admin.accounts.ajax_search') }}",
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        search_by_text: search_by_text,
                        account_type: account_type,
                        is_parent: is_parent,
                        active_search: active_search,
                        '_token':"{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $("#ajax_search_result").html(data);
                    },
                    error: function() {}
                });
            }

            $(document).on('input', '#search_by_text', function(e) {
                make_search();
            });
            $(document).on('change', '#account_type_search', function(e) {
                make_search();
            });
            $(document).on('change', '#is_parent_search', function(e) {
                make_search();
            });
            $(document).on('change', '#active_search', function(e) {
                make_search();
            });

            $(document).on('click', '#ajax_pagination_search a', function(e) {
                e.preventDefault();
                var search_by_text = $("#search_by_text").val();
                var account_type = $("#account_type_search").val();
                var is_parent = $("#is_parent_search").val();
                var active_search = $("#active_search").val();
                var url = $(this).attr("href");
                jQuery.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        search_by_text: search_by_text,
                        account_type: account_type,
                        is_parent: is_parent,
                        active_search: active_search,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $("#ajax_search_result").html(data);
                    },
                    error: function() {}
                });
            });

        });
    </script>

@endsection
