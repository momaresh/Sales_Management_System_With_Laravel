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

<div>
    <a href="{{ route('admin.admins.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-plus-circle"></i> اضافة جديد
    </a>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات المستخدمين</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <input class="form-control col-md-4 mb-3" type="search" placeholder="بحث بالاسم" id="ajax_search">

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>تعديل</th>
                            <th>كود المستخدم</th>
                            <th>الاسم</th>
                            <th>اسم المستخدم</th>
                            <th>الايميل</th>
                            <th>حالة التفعيل</th>
                            <th>نوع المستخدم</th>
                            <th>صلاحيات خاصة</th>
                            <th>حذف</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.admins.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td>{{ $datum->id }}</td>
                                <td>{{ $datum->name }}</td>
                                <td>{{ $datum->user_name }}</td>
                                <td>{{ $datum->email }}</td>
                                @if ($datum->active == 1)
                                <td style="background-color: #5ab6a0a1;">
                                    مفعل
                                </td>
                                @elseif ($datum->active == 0)
                                <td style="background-color: #c15670a1;;">
                                    غير مفعل
                                </td>
                                @endif

                                <td>{{ $datum->roles_name }}</td>
                                <td>
                                    <a href="{{ route('admin.admins.details', $datum->id) }}" style="color: rgb(37, 149, 82); font-size: 25px;">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                </td>

                                <td>
                                    <a href="{{ route('admin.admins.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    @else
                        <div class="text-danger">
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
    <a href="{{ route('admin.admins.index') }}">المستخدمين</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')

    <script>
        $(function() {
            $(document).on('input', '#ajax_search', function() {
                // get the value from the input to search by
                var search_value = $(this).val();

                jQuery.ajax({
                    // first argument is the where the from route to
                    url:"{{ route('admin.admins.ajax_search') }}",
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{search_value:search_value, '_token':"{{ csrf_token() }}"},
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {

                    }
                });

                $(document).on('click', '#ajax_search_pagination a', function(e) {
                    e.preventDefault();
                    // get the value from the input to search by
                    //var search_value = $(this).val();

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
                        data:{search_value:search_value, '_token':"{{ csrf_token() }}"},
                        // If the form and everything okay
                        success:function(data){
                            $('#ajax_search_result').html(data);
                        },
                        // If the there is an error
                        error:function() {

                        }
                    });
                });

            });

        });
    </script>

@endsection
