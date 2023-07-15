@extends('layout.admin')

@section('title')
    المشتريات
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
    @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'اضافة') == true)
        <a href="{{ route('admin.purchase_header.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-plus-circle"></i> اضافة جديد
        </a>
    @endif
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات فواتير المشتريات</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="mb-3 row">
                <input type="hidden" id='ajax_search_route' value="{{ route('admin.purchase_header.ajax_search') }}">
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
                            @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'تعديل') == true)
                                <th>تعديل</th>
                            @endif
                            <th>كود الفاتورة</th>
                            <th>اسم المورد</th>
                            <th>اسم المخزن</th>
                            <th>نوع الفاتورة</th>
                            <th>تاريخ الفاتورة</th>
                            <th>حالة الفاتورة</th>
                            <th>اجمالي الفاتورة</th>
                            @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'حذف') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'طباعة') == true)
                                <th>التحكم</th>
                                <th>طباعة بعد الارجاع</th>
                            @endif
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'تعديل') == true)
                                    <td>
                                        <a href="{{ route('admin.purchase_header.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                @endif

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
                                <td>{{ $datum->order_date }}</td>
                                @if ($datum->is_approved == 0)
                                <td style="background-color: #5ab6a0a1;">
                                    مفتوحة
                                </td>
                                @elseif ($datum->is_approved == 1)
                                <td style="background-color: #c15670a1;;">
                                    معتمدة
                                </td>
                                @endif

                                <td>{{ $datum->total_cost }}</td>
                                @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'حذف') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'التفاصيل') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'طباعة') == true)
                                    <td>
                                        @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'طباعة') == true)
                                            <a href="{{ route('admin.purchase_header.printA4', [$datum->id, 'A4']) }}" class="btn btn-success">
                                                A4 <i class="fa-solid fa-print"></i>
                                            </a>
                                            <a href="{{ route('admin.purchase_header.printA4', [$datum->id, 'A6']) }}" class="btn btn-success">
                                                A6 <i class="fa-solid fa-print"></i>
                                            </a>
                                        @endif

                                        @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'التفاصيل') == true)
                                            <a href="{{ route('admin.purchase_header.details', $datum->id) }}" class="btn btn-info mt-1">
                                                <i class="fa-solid fa-circle-info"></i>
                                            </a>
                                        @endif

                                        @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'حذف') == true)
                                            <a href="{{ route('admin.purchase_header.delete', $datum->id) }}" class="are_you_sure btn btn-danger mt-1">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        @endif
                                    </td>

                                    @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'طباعة') == true && $datum->is_original_return == 1)
                                        <td>
                                            <a href="{{ route('admin.purchase_header.printA4', [$datum->id, 'currentA6']) }}" class="btn btn-success">
                                                A6 <i class="fa-solid fa-print"></i>
                                            </a>
                                        </td>
                                    @endif
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
    <a href="{{ route('admin.purchase_header.index') }}">المشتريات</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')
    <script  src="{{ asset('assets/admin/js/purchase_header.js') }}"> </script>
@endsection
