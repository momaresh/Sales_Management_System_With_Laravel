@extends('layout.admin')

@section('title')
    المبيعات
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

<div class="mb-3">
    <button id="create_pill_button"  style="background-color: #007bff; width: fit-content; color: white" class="btn">
         اضافة فاتورة
    </button>

    <button id="pill_mirror_button"  style="background-color: #007bff; width: fit-content; color: white" class="btn">
       عرض مرآة الاسعار
    </button>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات فواتير المبيعات</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="mb-3 row">
                <input type="hidden" id='ajax_search_route' value="{{ route('admin.sales_header.ajax_search') }}">
                <input type="hidden" id='ajax_token' value="{{ csrf_token() }}">

                <div class="col-md-4">
                    <label class="control-label" for="auto_serial">بحث بكود الفاتورة</label>
                    <input type="search" class="form-control" name="search_by_code" id="sales_code" placeholder="بحث بكود الفاتورة">
                </div>
                <div class="col-md-4">
                    <label class="control-label">بحث بالعملاء</label>
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
                            <th>تعديل</th>
                            <th>كود الفاتورة</th>
                            <th>اسم المورد</th>
                            <th>اسم المندوب</th>
                            <th>نوع الفاتورة</th>
                            <th>تاريخ الفاتورة</th>
                            <th>حالة الفاتورة</th>
                            <th>اجمالي الفاتورة</th>
                            <th>التفاصيل</th>
                            <th>حذف</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>
                                    <button data-id={{ $datum->id }} id="update_pill" class="btn" style="color: rgb(149, 35, 35); font-size: 25px;">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
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
                                    <td style="background-color: #c15670a1;;">
                                        معتمدة
                                    </td>
                                @endif
                                <td>{{ $datum->total_cost }}</td>
                                <td>
                                    <button data-id={{ $datum->id }} id="update_pill" class="btn" style="color: rgb(38, 123, 29); font-size: 25px;">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('admin.sales_header.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
  <input type="hidden" id="ajax_get_item_unit_route" value="{{ route('admin.sales_header.get_item_unit') }}">
  <input type="hidden" id="ajax_create_pill_route" value="{{ route('admin.sales_header.create_pill') }}">
  <input type="hidden" id="ajax_pill_mirror_route" value="{{ route('admin.sales_header.pill_mirror') }}">
  <input type="hidden" id="ajax_get_item_batch_route" value="{{ route('admin.sales_header.get_item_batch') }}">
  <input type="hidden" id="ajax_get_item_price_route" value="{{ route('admin.sales_header.get_item_price') }}">
  <input type="hidden" id="ajax_add_new_item_row_route" value="{{ route('admin.sales_header.add_new_item_row') }}">
  <input type="hidden" id="ajax_check_shift_and_reload_money_route" value="{{ route('admin.sales_header.check_shift_and_reload_money') }}">
  <input type="hidden" id="ajax_store_route" value="{{ route('admin.sales_header.store') }}">
  <input type="hidden" id="ajax_load_pill_adding_items_modal_route" value="{{ route('admin.sales_header.load_pill_adding_items_modal') }}">
  <input type="hidden" id="ajax_store_item_route" value="{{ route('admin.sales_header.store_item') }}">
  <input type="hidden" id="ajax_remove_item_route" value="{{ route('admin.sales_header.remove_item') }}">
  <input type="hidden" id="ajax_add_to_customer_route" value="{{ route('admin.sales_header.add_to_customer') }}">
  <input type="hidden" id="ajax_get_added_customer_route" value="{{ route('admin.sales_header.get_added_customer') }}">


<div class="modal fade" id="create_pill" style="overflow: scroll">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #0d7383; color: white">
            <h4 class="modal-title">اضافة اصناف للفاتورة</h4>
            <button style="color: rgb(223, 0, 0);" type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="create_pill_result">

        </div>
        <div class="modal-footer justify-content-between " >
            <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pill_mirror">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content" >
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

<div class="modal fade" id="add_new_customer_modal">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #0793a9; color: white">
            <h4 class="modal-title">اضافة عميل</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="add_new_customer_result">
            <div class="row">
                <div class="col-md-4">
                    <label for="inputEmail3">اسم العميل الاول</label>
                    <div class="form-group">
                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="اسم العميل الاول">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="inputEmail3">اسم العميل الاخير</label>
                    <div class="form-group">
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="اسم العميل الاخير">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="inputEmail3">العنوان</label>
                    <div class="form-group">
                        <input type="text" name="address" id="address" class="form-control" placeholder="العنوان">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="inputEmail3">الهاتف</label>
                    <div class="form-group">
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="الهاتف">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>حالة رصيد اول المدة</label>
                        <select name="start_balance_status" id="start_balance_status" class="form-control">
                            <option value="">اختر الحالة</option>
                            <option value="1">دائن</option>
                            <option value="2">مدين</option>
                            <option value="3">متزن</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>رصيد أول المدة للحساب</label>
                        <input name="start_balance" id="start_balance" class="form-control"  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" >
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>ملاحظات</label>
                        <input name="notes" id="notes" class="form-control" value="">
                    </div>
                </div>

                <div class="col-md-4">
                    <label>مفعلة</label>
                    <div class="form-group">
                        <select name="active" id="active" class="form-control">
                            <option value="">اختر الحالة</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-primary" id="add_to_customer">اضافة</button>
            <button type="button" class="btn btn-default " data-dismiss="modal">الغاء</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('contentheader')
    المبيعات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.sales_header.index') }}">المبيعات</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')
    <script  src="{{ asset('assets/admin/js/sales_header.js') }}"> </script>
@endsection
