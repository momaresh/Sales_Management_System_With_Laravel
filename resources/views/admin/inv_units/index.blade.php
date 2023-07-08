@extends('layout.admin')

@section('title')
    الوحدات
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
    @if (check_control_menu_role('المخازن', 'الوحدات' , 'عرض') == true)
        <a href="{{ route('admin.inv_units.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-plus-circle"></i> اضافة جديد
        </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الوحدات</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <div class="mb-3 row">
                <div class="col-md-4 col-md-4">
                    <label class="control-label">اسم الوحدة</label>
                    <input class="form-control" type="search" placeholder="بحث بالاسم" id="ajax_search">
                </div>
                <div class="col-md-4">
                    <label class="control-label">نوع الوحدة</label>
                    <select class="form-control select2" id="master_search">
                        <option value="all">بحث بالكل</option>
                        <option value="0">وحدة جزئية</option>
                        <option value="1">وحدة اب</option>
                    </select>
                </div>
            </div>

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">

                            @if (check_control_menu_role('المخازن', 'الوحدات' , 'تعديل') == true)
                                <th>تعديل</th>
                            @endif
                            <th>كود الوحدة</th>
                            <th>اسم الوحدة</th>
                            <th>نوع الوحدة</th>
                            <th>حالة التفعيل</th>
                            <th>تاريخ الاضافة</th>
                            <th>تاريخ التحديث</th>
                            @if (check_control_menu_role('المخازن', 'الوحدات' , 'حذف') == true)
                                <th>حذف</th>
                            @endif
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                @if (check_control_menu_role('المخازن', 'الوحدات' , 'تعديل') == true)
                                    <td>
                                        <a href="{{ route('admin.inv_units.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                @endif
                                <td>{{ $datum->unit_code }}</td>
                                <td>{{ $datum->name }}</td>
                                <td>
                                    @if ($datum->master == 1)
                                        وحدة اب
                                    @else
                                        وحدة جزئية
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
                                @if (check_control_menu_role('المخازن', 'الوحدات' , 'حذف') == true)
                                    <td>
                                        <a href="{{ route('admin.inv_units.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
    المخازن
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.inv_units.index') }}">الوحدات</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')

    <script>
        $(function() {

            function make_search() {
                // get the value from the input to search by
                var search_by_name = $('#ajax_search').val();
                var search_by_type = $('#master_search').val();

                jQuery.ajax({
                    // first argument is the where the from route to
                    url:"{{ route('admin.inv_units.ajax_search') }}",
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
            }


            $(document).on('input', '#ajax_search', function() {
                make_search();
            });

            $(document).on('click', '#ajax_search_pagination a', function(e) {
                e.preventDefault();
                // get the value from the input to search by
                var search_by_name = $('#ajax_search').val();
                var search_by_type = $('#master_search').val();

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


            $(document).on('change', '#master_search', function() {
                make_search();
            });

        });
    </script>

@endsection
