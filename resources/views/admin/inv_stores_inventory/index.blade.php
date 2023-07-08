@extends('layout.admin')

@section('title')
جرد المخازن
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
    @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اضافة') == true)
        <a href="{{ route('admin.inv_stores_inventory.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-plus-circle"></i> اضافة جديد
        </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الجرد</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="mb-3 row">
                <div class="col-md-4">
                    <label class="control-label">بحث بالمخزن</label>
                    <select class="form-control select2" name="store_id" id="store_id">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($stores) && !@empty($stores))
                            @foreach ($stores as $info )
                                <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="control-label">بحث بالنوع</label>
                    <select name="inventory_type" id="inventory_type" class="form-control select2">
                        <option value="all">بحث بالكل</option>
                        <option value="1">يومي</option>
                        <option value="2">اسبوعي</option>
                        <option value="3">شهري</option>
                        <option value="4">سنوي</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="control-label">بحث بالحالة</label>
                    <select name="is_closed" id="is_closed" class="form-control select2">
                        <option value="all">بحث بالكل</option>
                        <option value="1">مغلق</option>
                        <option value="0">مفتوح</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="control-label" for="from_date">من تاريخ</label>
                    <input class="form-control" type="date" id="from_date" name="from_date" >
                </div>

                <div class="col-md-4">
                    <label class="control-label" for="to_date">الى تاريخ</label>
                    <input class="form-control" type="date" id="to_date" name="to_date" >
                </div>
            </div>

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))
                        <tr style="background-color: #007bff; color:white;">
                            @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'تعديل') == true)
                                <th>تعديل</th>
                            @endif
                            <th>كود الجرد</th>
                            <th>تاريخ الجرد</th>
                            <th>نوع الجرد</th>
                            <th>مخزن الجرد</th>
                            <th>الحالة</th>
                            @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'طباعة') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'حذف') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اغلاق') == true)
                                <th>التحكم</th>
                            @endif
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'تعديل') == true)
                                    <td>
                                        <a href="{{ route('admin.inv_stores_inventory.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                @endif
                                <td>{{ $datum->inventory_code }}</td>
                                <td>{{ $datum->inventory_date }}</td>
                                <td>
                                    @if ($datum->inventory_type == 1)
                                        يومي
                                    @elseif ($datum->inventory_type == 2)
                                        اسبوعي
                                    @elseif ($datum->inventory_type == 3)
                                        شهري
                                    @elseif ($datum->inventory_type == 4)
                                        سنوي
                                    @endif
                                </td>

                                <td>{{ $datum['store_name'] }}</td>
                                @if ($datum->is_closed == 0)
                                    <td style="background-color: #5ab6a0a1;">
                                        مفتوح
                                    </td>
                                @elseif ($datum->is_closed == 1)
                                    <td style="background-color: #c15670a1;;">
                                        مغلق
                                    </td>
                                @endif

                                @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'طباعة') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'حذف') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اغلاق') == true)
                                    <td>
                                        @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'طباعة') == true)
                                            <a href="{{ route('admin.inv_stores_inventory.printA4', $datum->id) }}" class="btn btn-success">
                                                A4 <i class="fa-solid fa-print"></i>
                                            </a>
                                        @endif

                                        @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'التفاصيل') == true)
                                            <a href="{{ route('admin.inv_stores_inventory.details', $datum->id) }}" class="btn btn-info">
                                                <i class="fa-solid fa-circle-info"></i>
                                            </a>
                                        @endif


                                        @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اغلاق') == true)
                                            <a href="{{ route('admin.inv_stores_inventory.close_header', $datum->id) }}" @if ($datum->is_closed == 1) @endif class="btn btn-warning">
                                                ترحيل
                                            </a>
                                        @endif

                                        @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'حذف') == true)
                                            <a href="{{ route('admin.inv_stores_inventory.delete', $datum->id) }}" class="are_you_sure btn btn-danger">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        @endif
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
    الحركات المخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.inv_stores_inventory.index') }}">جرد المخازن</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')

    <script>
        $(function() {
            function make_search() {
                var store_id = $("#store_id").val();
                var inventory_type = $("#inventory_type").val();
                var is_closed = $("#is_closed").val();
                var from_date = $("#from_date").val();
                var to_date = $("#to_date").val();
                jQuery.ajax({
                    // first argument is the where the from route to
                    url:"{{ route('admin.inv_stores_inventory.ajax_search') }}",
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{
                        store_id:store_id,
                        inventory_type:inventory_type,
                        is_closed:is_closed,
                        from_date:from_date,
                        to_date:to_date,
                        '_token':"{{ csrf_token() }}"},
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {
                        alert('حدث خطأ ما');
                    }
                });
            }

            $(document).on('click', '#ajax_search_pagination a', function(e) {
                e.preventDefault();
                var store_id = $("#store_id").val();
                var inventory_type = $("#inventory_type").val();
                var is_closed = $("#is_closed").val();
                var from_date = $("#from_date").val();
                var to_date = $("#to_date").val();
                jQuery.ajax({
                    // first argument is the where the from route to
                    url:$(this).attr('href'),
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{
                        store_id:store_id,
                        inventory_type:inventory_type,
                        is_closed:is_closed,
                        from_date:from_date,
                        to_date:to_date,
                        '_token':"{{ csrf_token() }}"},
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {
                        alert('حدث خطأ ما');
                    }
                });
            });

            $(document).on('change', '#store_id', function(e) {
                make_search();
            });

            $(document).on('change', '#inventory_type', function(e) {
                make_search();
            });

            $(document).on('change', '#is_closed', function(e) {
                make_search();
            });

            $(document).on('change', '#from_date', function(e) {
                make_search();
            });

            $(document).on('change', '#to_date', function(e) {
                make_search();
            });
        });
    </script>

@endsection
