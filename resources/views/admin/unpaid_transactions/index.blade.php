@extends('layout.admin')

@section('title')
شاشة الدفع الآجل
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


@if (check_control_menu_role('الحسابات', 'شاشة الدفع الآجل' , 'عرض') == true)
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الدفع الآجل</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <div class="mb-3" style="display: flex; flex-wrap: wrap">
                    <div class="col-md-4">
                        <input type="radio" checked name="search_by_radio" id="code" value="code">
                        <label class="control-label" for="code">بالكود</label>
                        <input type="radio" name="search_by_radio" id="arrive" value="arrive">
                        <label class="control-label" for="arrive">بالايصال</label>
                        <input type="radio" name="search_by_radio" id="shift" value="shift">
                        <label class="control-label" for="shift">بالشفت</label>

                        <input class="form-control" type="search" placeholder="بالكود - بالايصال - بالشفت" id="text_search">
                    </div>
                    <div class="col-md-4">
                        <label class="control-label">بحث بالحسابات المالية</label>
                        <select class="form-control select2" name="account_number_search" id="account_number_search">
                            <option value="all">بحث بالكل</option>
                            @if (@isset($accounts) && !@empty($accounts))
                                @foreach ($accounts as $info )
                                    <option value="{{ $info->account_number }}">{{ $info->name }} ({{ $info->type }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بحث بالحركات المالية</label>
                            <select name="move_type_search" id="move_type_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                @if (@isset($moves_types) && !@empty($moves_types))
                                @foreach ($moves_types as $info )
                                    <option value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بحث بالخزن</label>
                            <select name="treasuries_search" id="treasuries_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                @if (@isset($treasuries) && !@empty($treasuries))
                                @foreach ($treasuries as $info )
                                    <option value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بحث بالمستخدمين</label>
                            <select name="admin_search" id="admin_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                @if (@isset($admins) && !@empty($admins))
                                @foreach ($admins as $info )
                                    <option value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>


                </div>

                <div id="ajax_search_result">
                    <table id="example2" class="table table-bordered table-hover">

                        @if (!@empty($data[0]))

                            <tr style="background-color: #007bff; color:white;">
                                <th>كود العملية</th>
                                <th>كود الشفت</th>
                                <th>نوع الحركة</th>
                                <th>اسم المستخدم</th>
                                <th>اسم الخزنة</th>
                                <th>اسم صاحب الحساب</th>
                                <th>رقم آخر ايصال آجل من الخزنة</th>
                                <th>المبلغ المستحق للحساب او عليه</th>
                                <th>تم الاعتماد</th>
                            </tr>

                            @foreach ($data as $datum)
                                <tr>
                                    <td>{{ $datum->transaction_code }}</td>
                                    <td>{{ $datum->shift_code }}</td>
                                    <td>{{ $datum->move_type_name }}</td>
                                    <td>{{ $datum->admin_name }}</td>
                                    <td>{{ $datum->treasuries_name }}</td>
                                    <td>{{ $datum->account_name }} <span class="my-col-main">({{ $datum->account_type }})</span></td>
                                    <td>{{ $datum->last_arrive }}</td>
                                    <td>{{ $datum->money_for_account }}</td>
                                    @if ($datum->is_approved == 1)
                                        <td style="background-color: #5ab6a0a1">نعم</td>
                                    @else
                                        <td style="background-color: #c15670a1">لا</td>
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
@endif

@endsection

@section('contentheader')
    الحسابات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.unpaid_transactions.index') }}">شاشة الدفع الآجل</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')

    <script>
        $(function() {
            // ajax search
            function make_search() {
                // get the value from the input to search by
                var radio_search = $('input[type=radio][name=search_by_radio]:checked').val();
                var account_number_search = $('#account_number_search').val();
                var move_type_search = $('#move_type_search').val();
                var treasuries_search = $('#treasuries_search').val();
                var text_search = $('#text_search').val();
                var admin_search = $('#admin_search').val();
                jQuery.ajax({
                    // first argument is the where the from route to
                    url:"{{ route('admin.unpaid_transactions.ajax_search') }}",
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{
                        text_search:text_search,
                        radio_search:radio_search,
                        account_number_search:account_number_search,
                        move_type_search:move_type_search,
                        treasuries_search:treasuries_search,
                        admin_search:admin_search,
                        '_token':"{{ csrf_token() }}"
                        },
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {
                        alert('حدث خطأ');
                    }
                });
            }

            $(document).on('click', '#ajax_search_pagination a', function(e) {
                e.preventDefault();
                var radio_search = $('input[type=radio][name=search_by_radio]:checked').val();
                var account_number_search = $('#account_number_search').val();
                var move_type_search = $('#move_type_search').val();
                var treasuries_search = $('#treasuries_search').val();
                var text_search = $('#text_search').val();
                var admin_search = $('#admin_search').val();
                jQuery.ajax({
                    url:$(this).attr('href'),
                    type:'post',
                    datatype:'html',
                    cache:false,
                    data:{
                        text_search:text_search,
                        radio_search:radio_search,
                        account_number_search:account_number_search,
                        move_type_search:move_type_search,
                        treasuries_search:treasuries_search,
                        admin_search:admin_search,
                        '_token':"{{ csrf_token() }}"
                        },
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    error:function() {
                        alert('حدث خطأ');
                    }
                });
            });

            $(document).on('change', '#account_number_search', function() {
                make_search();
            });

            $(document).on('change', '#move_type_search', function() {
                make_search();
            });
            $(document).on('change', '#treasuries_search', function() {
                make_search();
            });
            $(document).on('change', '#admin_search', function() {
                make_search();
            });
            $(document).on('change', 'input[type=radio][name=search_by_radio]', function() {
                make_search();
            });
            $(document).on('input', '#text_search', function() {
                make_search();
            });

        });
    </script>

@endsection
