@extends('layout.admin')

@section('title')
المرتجعات بالفاتورة الاصل
@endsection

@section('contentheader')
    الحركات المخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.purchase_order_header_original_return.index') }}">المرتجعات بالفاتورة الاصل</a>
@endsection

@section('contentheaderactive')
    عرض
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
    @if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'اضافة') == true)
        <a href="{{ route('admin.purchase_order_header_original_return.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-plus-circle"></i> اضافة جديد
        </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات فواتير المرتجعات بالاصل</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="mb-3 row">
                <input type="hidden" id='ajax_search_route' value="{{ route('admin.purchase_order_header_original_return.ajax_search') }}">
                <input type="hidden" id='ajax_token' value="{{ csrf_token() }}">

                <div class="col-md-4">
                    <label class="control-label" for="purchase_code">بحث بكود الفاتورة</label>
                    <input class="form-control" type="search" placeholder="بحث بكود الفاتورة" id="purchase_code">
                </div>
                <div class="col-md-4">
                    <label class="control-label">بحث بالمورد</label>
                    <select class="form-control select2" name="supplier_code_search" id="supplier_code_search">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($suppliers) && !@empty($suppliers))
                            @foreach ($suppliers as $info )
                                <option value="{{ $info->supplier_code }}">{{ $info->supplier_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="control-label">بحث بالمخزن</label>
                    <select class="form-control select2" name="store_id_search" id="store_id_search">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($stores) && !@empty($stores))
                            @foreach ($stores as $info )
                                <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="control-label" for="from_date_search">من تاريخ</label>
                    <input class="form-control" type="date" id="from_date_search" name="from_date_search" >
                </div>

                <div class="col-md-4">
                    <label class="control-label" for="to_date_search">الى تاريخ</label>
                    <input class="form-control" type="date" id="to_date_search" name="to_date_search" >
                </div>
            </div>

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>كود الفاتورة الاصل</th>
                            <th>كود الفاتورة</th>
                            <th>اسم المورد</th>
                            <th>اسم المخزن</th>
                            <th>نوع الفاتورة</th>
                            <th>تاريخ الفاتورة</th>
                            <th>حالة الفاتورة</th>
                            <th>اجمالي الفاتورة</th>
                            @if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'طباعة') == true)
                                <th>التحكم</th>
                            @endif
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>{{ $datum->parent_pill_code }}</td>
                                <td>{{ $datum->pill_code }}</td>
                                <td>{{ $datum['supplier_name'] }}</td>
                                <td>{{ $datum['store_name'] }}</td>
                                <td>
                                    @if ($datum->pill_type == 1)
                                        نقداً
                                    @elseif ($datum->pill_type == 2)
                                        آجل
                                    @else
                                        غير محدد
                                    @endif
                                </td>
                                <td>{{ $datum->return_date }}</td>

                                <td style="background-color: #c15670a1;">
                                    معتمدة
                                </td>

                                <td>{{ $datum->total_cost }}</td>
                                @if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'طباعة') == true)
                                    <td>
                                        @if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'طباعة') == true)
                                            <a href="{{ route('admin.purchase_order_header_original_return.printA4', [$datum->invoice_order_id, $datum->pill_code, 'A4']) }}" class="btn btn-success">
                                                A4 <i class="fa-solid fa-print"></i>
                                            </a>
                                            <a href="{{ route('admin.purchase_order_header_original_return.printA4', [$datum->invoice_order_id, $datum->pill_code, 'A6']) }}" class="btn btn-success">
                                                A6 <i class="fa-solid fa-print"></i>
                                            </a>
                                        @endif

                                        @if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'التفاصيل') == true)
                                            <a href="{{ route('admin.purchase_order_header_original_return.details', [$datum->invoice_order_id, $datum->pill_code]) }}" class="btn btn-info mt-1">
                                                <i class="fa-solid fa-circle-info"></i>
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

<input type="hidden" id="token_search" value="{{ csrf_token() }}">
@endsection

@section('script')
    <script  src="{{ asset('assets/admin/js/original_return.js') }}"> </script>
@endsection
