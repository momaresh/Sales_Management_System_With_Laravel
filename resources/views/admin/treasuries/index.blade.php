@extends('layout.admin')

@section('title')
    الخزنات
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
    @if (check_control_menu_role('الحسابات', 'الخزن' , 'اضافة') == true)
        <a href="{{ route('admin.treasuries.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-plus-circle"></i> اضافة جديد
        </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الخزنات</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="mb-3 row">
                <div class="col-md-6">
                    <input type="radio" checked name="search_by_radio" id="acc_number" value="acc_number">
                    <label class="control-label" for="acc_number">بحث برقم الحساب</label>
                    <input type="radio" name="search_by_radio" id="name" value="name">
                    <label class="control-label" for="name">بحث بالاسم</label>

                    <input class="form-control" type="search" placeholder="-رقم الحساب - الاسم" id="ajax_search">
                </div>

                <div class="col-md-3">
                    <label class="control-label">حالة الحساب</label>
                    <select class="form-control select2" id="current_status_search">
                        <option value="all">بحث بالكل</option>
                        <option value="0">دائن</option>
                        <option value="1">متزن</option>
                        <option value="2">مدين</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="control-label">حالة الحساب اول المدة</label>
                    <select class="form-control select2" id="start_status_search">
                        <option value="all">بحث بالكل</option>
                        <option value="0">دائن</option>
                        <option value="1">متزن</option>
                        <option value="2">مدين</option>
                    </select>
                </div>
            </div>

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            @if (check_control_menu_role('الحسابات', 'الخزن' , 'تعديل') == true)
                                <th>تعديل</th>
                            @endif
                            <th>كود الخرينة</th>
                            <th>اسم الخرينة</th>
                            <th>رقم الحساب</th>
                            <th>الرصيد الحالي</th>
                            <th>خزنة رئيسية</th>
                            <th>اخر ايصال صرف</th>
                            <th>اخر ايصال تحصيل</th>
                            <th>اخر ايصال آجل</th>
                            <th>حالة التفعيل</th>
                            @if (check_control_menu_role('الحسابات', 'الخزن' , 'التفاصيل') == true)
                                <th>الخزن التي يتم الاستلام منها</th>
                            @endif
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                @if (check_control_menu_role('الحسابات', 'الخزن' , 'تعديل') == true)
                                    <td>
                                        <a href="{{ route('admin.treasuries.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                @endif
                                <td>{{ $datum->treasury_code }}</td>
                                <td>{{ $datum->name }}</td>
                                <td>{{ $datum->account_number }}</td>
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
                                    @if ($datum->master == 1)
                                        رئيسية
                                    @else
                                        غير رئيسية
                                    @endif
                                </td>
                                <td>{{ $datum->last_exchange_arrive }}</td>
                                <td>{{ $datum->last_collection_arrive }}</td>
                                <td>{{ $datum->last_unpaid_arrive }}</td>
                                @if ($datum->active == 1)
                                <td style="background-color: #5ab6a0a1;">
                                    مفعل
                                </td>
                                @elseif ($datum->active == 0)
                                <td style="background-color: #c15670a1;;">
                                    غير مفعل
                                </td>
                                @endif
                                @if (check_control_menu_role('الحسابات', 'الخزن' , 'التفاصيل') == true)
                                    <td>
                                        <a href="{{ route('admin.treasuries.details', $datum->id) }}" class="btn btn-info">
                                            عرض
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
    <a href="{{ route('admin.treasuries.index') }}">الخزينة</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')

    <script>
        function make_search() {
            var search_by_name = $('#ajax_search').val();
            var search_by_radio = $('input[type=radio][name=search_by_radio]:checked').val();
            var current_status_search = $('#current_status_search').val();
            var start_status_search = $('#start_status_search').val();

            jQuery.ajax({
                url:"{{ route('admin.treasuries.ajax_search') }}",
                type:'post',
                datatype:'html',
                cache:false,
                data:{
                    search_by_name:search_by_name,
                    search_by_radio:search_by_radio,
                    current_status_search:current_status_search,
                    start_status_search:start_status_search,
                    '_token':"{{ csrf_token() }}"},
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

        $(document).on('change', '#current_status_search', function() {
            make_search();
        });

        $(document).on('change', '#start_status_search', function() {
            make_search();
        });

        $(document).on('click', '#ajax_search_pagination a', function(e) {
            e.preventDefault();
            var search_by_name = $('#ajax_search').val();
            var search_by_radio = $('input[type=radio][name=search_by_radio]:checked').val();
            var current_status_search = $('#current_status_search').val();
            var start_status_search = $('#start_status_search').val();

            jQuery.ajax({
                // first argument is the where the from route to
                url: $(this).attr("href"),
                // second argument is sending type of the form
                type:'post',
                // third argument is the type of the returned data from the model
                datatype:'html',
                // first argument is
                cache:false,
                // forth we send the search data and the token
                data:{
                    search_by_name:search_by_name,
                    search_by_radio:search_by_radio,
                    current_status_search:current_status_search,
                    start_status_search:start_status_search,
                    '_token':"{{ csrf_token() }}"},
                // If the form and everything okay
                success:function(data){
                    $('#ajax_search_result').html(data);
                },
                // If the there is an error
                error:function() {

                }
            });
        });

        $(document).on('change', 'input[type=radio][name=search_by_radio]', function() {
            make_search();
        });
    </script>

@endsection
