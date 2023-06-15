@extends('layout.admin')

@section('title')
    حركات شفتات الخزن
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



@if (!@empty($check_has_shift))


<div class="card">
    <div class="card-header">
      <h3 class="card-title"> اضافة صرف جديد</h3>
    </div>
   <!-- /.card-header -->
    <div class="card-body">
        <form  action="{{ route('admin.exchange_transactions.store') }}" method="post" enctype="multipart/form-data" >
        @csrf

            <input value="{{ $check_has_shift['shift_code'] }}" type="hidden" name='shift_code'>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>تاريخ الحركة</label>
                        <input type="date" name="move_date" id="move_date" class="form-control" value="{{ old('move_date', date('Y-m-d')) }}" >

                        @error('move_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>بيانات الخزنة</label>
                        <select name="treasuries_id" id="treasuries_id" class="form-control select2">
                            <option value="{{ $check_has_shift['treasuries_id'] }}">{{ $check_has_shift['treasuries_name'] }}</option>
                        </select>

                        @error('treasuries_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>الرصيد المتاح بالخزنة</label>
                            <input type="text" name="" id="money" disabled value="{{ $check_has_shift['money_in_treasury'] }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>الحساب المالي</label>
                        <select name="account_number" id="account_number" class="form-control select2">
                            <option data-account_type="" value="">اختر صاحب الحساب</option>
                            @if (@isset($accounts) && !@empty($accounts))
                            @foreach ($accounts as $info )
                                <option data-account_type="{{ $info->account_type }}" @if(old('account_number') == $info->account_number) selected @endif value="{{ $info->account_number }}"> {{ $info->name }} ({{ $info->type }}) </option>
                            @endforeach
                            @endif
                        </select>
                        @error('account_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>نوع الحركة</label>
                        <select name="move_type" id="move_type" class="form-control">
                            <option value="">اختر النوع</option>
                            @if (@isset($moves_types) && !@empty($moves_types))
                            @foreach ($moves_types as $info )
                                <option @if(old('move_type') == $info->id) selected @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('move_type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4" id="get_status" style="display: none">

                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label>قيمة المبلغ المصرف</label>
                        <input oninput="this.value = this.value.replace(/[^0-9.]/g,'');" name="money" id="money" class="form-control"  value="{{ old('money') }}" placeholder="قيمة المبلغ المحصل"  >

                        @error('money')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>البيان</label>
                        <textarea name="byan" id="byan" class="form-control">{{ old('byan') }}</textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_add_item_card" type="submit" class="btn btn-primary btn-sm"> اضافة</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@else
    <div class="alert alert-warning">
        انت لست مستلما اي شفت حاليا
    </div>
@endif



<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات شفتات الخزن</h3>
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
                            <th>رقم آخر صرف من الخزنة</th>
                            <th>المبلغ المصرف</th>
                            <th>تم الاعتماد</th>
                            <th>المزيد</th>
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
                                <td>{{ $datum->money }}</td>
                                @if ($datum->is_approved == 1)
                                    <td style="background-color: #5ab6a0a1">نعم</td>
                                @else
                                    <td style="background-color: #c15670a1">لا</td>
                                @endif

                                <td>
                                    <a href="{{ route('admin.exchange_transactions.index') }}" style="color: rgb(55, 149, 88); font-size: 25px;">
                                        <i class="fa-solid fa-circle-info"></i>
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
    <a href="{{ route('admin.exchange_transactions.index') }}">شاشة الصرف</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')

    <script>
        $(function() {
            $(document).on('change', '#account_number', function() {
                var account_number = $('#account_number').val();
                if (account_number != '') {
                    jQuery.ajax({
                        url: "{{ route('admin.exchange_transactions.get_status') }}",
                        type: 'post',
                        dataType: 'html',
                        cache: false,
                        data: {
                            account_number: account_number,
                            '_token':"{{ csrf_token() }}"
                        },
                        success: function(data) {
                            $("#get_status").html(data);
                            $('#get_status').show();
                        },
                        error: function() {}
                    });
                }
                else {
                    $('#get_status').hide();
                }

                var account_type = $('#account_number option:selected').data('account_type');
                if (account_type == "") {
                    $('#move_type').val('');
                }
                else if (account_type == 2) {
                    $('#move_type').val(9);
                }
                else if (account_type == 3) {
                    $('#move_type').val(6);
                }
                else if (account_type == 6) {
                    $('#move_type').val(18);
                }
                else {
                    $('#move_type').val(3);
                }


            });

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
                    url:"{{ route('admin.exchange_transactions.ajax_search') }}",
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
