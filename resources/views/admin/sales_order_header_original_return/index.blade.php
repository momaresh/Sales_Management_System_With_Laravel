@extends('layout.admin')

@section('title')
مرتجع المبيعات بالاصل
@endsection

@section('contentheader')
    المبيعات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.sales_order_header_original_return.index') }}">المرتجعات بالفاتورة الاصل</a>
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
    @if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'اضافة') == true)
        <a href="{{ route('admin.sales_order_header_original_return.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
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
                <input type="hidden" id='ajax_search_route' value="{{ route('admin.sales_order_header_original_return.ajax_search') }}">
                <input type="hidden" id='ajax_token' value="{{ csrf_token() }}">

                <div class="col-md-4">
                    <label class="control-label" for="pill_code">بحث بكود الفاتورة</label>
                    <input class="form-control" type="search" placeholder="بحث بكود الفاتورة" name="pill_code" id="pill_code">
                </div>
                <div class="col-md-4">
                    <label class="control-label">بحث بالعميل</label>
                    <select class="form-control select2" name="customer_code_search" id="customer_code_search">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($customers) && !@empty($customers))
                            @foreach ($customers as $info )
                                <option value="{{ $info->customer_code }}">{{ $info->customer_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="control-label">بحث بالمناديب</label>
                    <select class="form-control select2" name="delegate_code_search" id="delegate_code_search">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($delegates) && !@empty($delegates))
                            @foreach ($delegates as $info )
                                <option value="{{ $info->delegate_code }}">{{ $info->delegate_name }}</option>
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
                            <th>اسم العميل</th>
                            <th>اسم المندوب</th>
                            <th>نوع الفاتورة</th>
                            <th>تاريخ الفاتورة</th>
                            <th>حالة الفاتورة</th>
                            <th>اجمالي الفاتورة</th>
                            @if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'التفاصيل') == true || check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'طباعة') == true)
                                <th>التحكم</th>
                            @endif
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>{{ $datum->pill_code }}</td>
                                <td>{{ $datum['customer_name'] }}</td>
                                <td>{{ $datum['delegate_name'] }}</td>
                                <td>
                                    @if ($datum->pill_type == 1)
                                        نقداً
                                    @elseif ($datum->pill_type == 2)
                                        آجل
                                    @else
                                        غير محدد
                                    @endif
                                </td>
                                <td>{{ $datum->order_date }}</td>
                                @if ($datum->is_approved == 0)
                                <td style="background-color: #5ab6a0a1;">
                                    مفتوحة
                                </td>
                                @elseif ($datum->is_approved == 1)
                                <td style="background-color: #c15670a1;">
                                    معتمدة
                                </td>
                                @endif

                                <td>{{ $datum->total_cost }}</td>
                                @if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'التفاصيل') == true || check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'طباعة') == true)
                                    <td>
                                        @if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'طباعة') == true)
                                            <a href="{{ route('admin.sales_order_header_original_return.printA4', [$datum->id, 'A4']) }}" class="btn btn-success">
                                                A4 <i class="fa-solid fa-print"></i>
                                            </a>
                                            <a href="{{ route('admin.sales_order_header_original_return.printA4', [$datum->id, 'A6']) }}" class="btn btn-success">
                                                A6 <i class="fa-solid fa-print"></i>
                                            </a>
                                        @endif

                                        @if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'التفاصيل') == true)
                                            <a href="{{ route('admin.sales_order_header_original_return.details', $datum->id) }}" class="btn btn-info">
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
@endsection

@section('script')
    <script  src="{{ asset('assets/admin/js/sales_original_return.js') }}"> </script>
@endsection
