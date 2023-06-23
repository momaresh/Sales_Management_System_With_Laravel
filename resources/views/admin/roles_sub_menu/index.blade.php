@extends('layout.admin')

@section('title')
    القوائم الفرعية للصلاحيات
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
    <a href="{{ route('admin.roles_sub_menu.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-plus-circle"></i> اضافة جديد
    </a>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات القوائم الفرعية للصلاحيات</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <div class="mb-3 row">
                <div class="col-md-4 col-md-4">
                    <label class="control-label">اسم القائمة</label>
                    <input class="form-control" type="search" placeholder="بحث بالاسم" id="sub_menu_search">
                </div>
                <div class="col-md-4">
                    <label class="control-label">القائمة الرئيسية</label>
                    <select class="form-control select2" id="main_menu_search">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($main_menus) && !@empty($main_menus))
                            @foreach ($main_menus as $menu)
                            <option @if (old('main_menu_id') == $menu->id) selected @endif value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>تعديل</th>
                            <th>كود القائمة الفرعية</th>
                            <th>اسم القائمة الفرعية</th>
                            <th>اسم القائمة الرئيسية</th>
                            <th>حالة التفعيل</th>
                            <th>تاريخ الاضافة</th>
                            <th>تاريخ التحديث</th>
                            <th>التحكم</th>
                            <th>حذف</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.roles_sub_menu.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td>{{ $datum->id }}</td>
                                <td>{{ $datum->name }}</td>
                                <td>{{ $datum->main_menu_name }}</td>
                                @if ($datum->active == 1)
                                <td style="background-color: #5ab6a0a1;">
                                    مفعلة
                                </td>
                                @elseif ($datum->active == 0)
                                <td style="background-color: #c15670a1;;">
                                    غير مفعلة
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
                                    <a href="{{ route('admin.roles_sub_menu.details', $datum->id) }}" style="color: rgb(38, 123, 29); font-size: 25px;">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.roles_sub_menu.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
    الصلاحيات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.roles_sub_menu.index') }}">القوائم الفرعية للصلاحيات</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')

    <script>
        $(function() {

            function make_search() {
                // get the value from the input to search by
                var search_by_name = $('#sub_menu_search').val();
                var search_by_main_menu = $('#main_menu_search').val();

                jQuery.ajax({
                    // first argument is the where the from route to
                    url:"{{ route('admin.roles_sub_menu.ajax_search') }}",
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{search_by_name:search_by_name,search_by_main_menu:search_by_main_menu, '_token':"{{ csrf_token() }}"},
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {

                    }
                });
            }


            $(document).on('input', '#sub_menu_search', function() {
                make_search();
            });

            $(document).on('click', '#sub_menu_search_pagination a', function(e) {
                e.preventDefault();
                // get the value from the input to search by
                var search_by_name = $('#sub_menu_search').val();
                var search_by_type = $('#main_menu_search').val();

                jQuery.ajax({
                    // first argument is the where the from route to
                    url:$(this).attr("href"),
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{search_by_name:search_by_name,search_by_type:search_by_type, '_token':"{{ csrf_token() }}"},
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {

                    }
                });
            });


            $(document).on('change', '#main_menu_search', function() {
                make_search();
            });

        });
    </script>

@endsection
