@extends('layout.admin')

@section('title')
    المرتجعات
@endsection

@section('contentheader')
    الحركات المخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.purchase_order_header_general_return.index') }}">المرتجعات</a>
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
    <button id="create_pill_button" style="background-color: #007bff; font-size: 20px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-save"></i> اضافة جديد
    </button>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات فواتير المرتجعات</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="mb-3 row">
                <input type="hidden" id='ajax_search_route' value="{{ route('admin.purchase_order_header_general_return.ajax_search') }}">
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
                            <th>تعديل</th>
                            <th>كود الفاتورة</th>
                            <th>اسم المورد</th>
                            <th>اسم المخزن</th>
                            <th>نوع الفاتورة</th>
                            <th>تاريخ الفاتورة</th>
                            <th>حالة الفاتورة</th>
                            <th>اجمالي الفاتورة</th>
                            <th>التحكم</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>
                                    <button data-id={{ $datum->id }} id="update_pill" class="btn" style="color: rgb(149, 35, 35);; font-size: 27px;">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
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
                                <td style="background-color: #c15670a1;">
                                    معتمدة
                                </td>
                                @endif

                                <td>{{ $datum->total_cost }}</td>
                                <td>
                                    <a href="{{ route('admin.purchase_order_header_general_return.printA4', [$datum->id, 'A4']) }}" class="btn btn-success">
                                        A4 <i class="fa-solid fa-print"></i>
                                    </a>
                                    <a href="{{ route('admin.purchase_order_header_general_return.printA4', [$datum->id, 'A6']) }}" class="btn btn-success">
                                        A6 <i class="fa-solid fa-print"></i>
                                    </a>
                                    <button data-id={{ $datum->id }} id="update_pill" class="btn" style="color: rgb(38, 123, 29); font-size: 25px;">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                    <a href="{{ route('admin.purchase_order_header_general_return.delete', $datum->id) }}" class="are_you_sure btn btn-danger mt-1">
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

<input type="hidden" id="token_search" value="{{ csrf_token() }}">
<input type="hidden" id="ajax_get_item_unit_route" value="{{ route('admin.purchase_order_header_general_return.get_item_unit') }}">
<input type="hidden" id="ajax_create_pill_route" value="{{ route('admin.purchase_order_header_general_return.create_pill') }}">
<input type="hidden" id="ajax_get_item_batch_route" value="{{ route('admin.purchase_order_header_general_return.get_item_batch') }}">
<input type="hidden" id="ajax_get_item_price_route" value="{{ route('admin.purchase_order_header_general_return.get_item_price') }}">
<input type="hidden" id="ajax_add_new_item_row_route" value="{{ route('admin.purchase_order_header_general_return.add_new_item_row') }}">
<input type="hidden" id="ajax_check_shift_and_reload_money_route" value="{{ route('admin.purchase_order_header_general_return.check_shift_and_reload_money') }}">
<input type="hidden" id="ajax_store_route" value="{{ route('admin.purchase_order_header_general_return.store') }}">
<input type="hidden" id="ajax_load_pill_adding_items_modal_route" value="{{ route('admin.purchase_order_header_general_return.load_pill_adding_items_modal') }}">
<input type="hidden" id="ajax_store_item_route" value="{{ route('admin.purchase_order_header_general_return.store_item') }}">
<input type="hidden" id="ajax_remove_item_route" value="{{ route('admin.purchase_order_header_general_return.remove_item') }}">

<div class="modal fade" id="create_pill">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content" >
        <div class="modal-header">
            <h4 class="modal-title">اضافة فاتورة</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="create_pill_result">

        </div>


        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
            <button type="button" class="btn btn-primary" id="add_to_pill">اضافة</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pill_mirror">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">مرآة فاتورة الاسعار</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="pill_mirror_result">

        </div>


        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
            <button type="button" class="btn btn-primary" id="add_to_pill">اضافة</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pill_adding_items_modal" style="overflow: scroll !important;">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">اضافة اصناف للفاتورة</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="pill_adding_items_result">

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default " data-dismiss="modal">الغاء</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script  src="{{ asset('assets/admin/js/purchase_order_header_general_return.js') }}"> </script>
@endsection
