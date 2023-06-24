@extends('layout.admin')

@section('title')
    تفاصيل المشتريات
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

<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الفاتورة</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">

            @if (!@empty($data))

                <tr>
                    <th>كود الفاتورة</th>
                    <td>{{ $data->purchase_code }}</td>
                </tr>

                <tr>
                    <th>اسم المورد</th>
                    <td>{{ $data->supplier_name }}</td>
                </tr>

                <tr>
                    <th>تاريخ الفاتورة</th>
                    <td>{{ $data->order_date }}</td>
                </tr>


                <tr>
                    <th>نوع الفاتورة</th>
                    <td>
                        @if ($data->pill_type == 1)
                            نقداً
                        @elseif ($data->pill_type == 2)
                            آجل
                        @else
                            غير محدد
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>كود الفاتورة بأصل فاتورة الاصل لدى المورد</th>
                    <td>{{ $data->pill_number }}</td>
                </tr>

                <tr>
                    <th>حالة الفاتورة</th>
                    @if ($data->is_approved == 0)
                    <td style="background-color: #5ab6a0a1;">
                        مفتوحة
                    </td>
                    @elseif ($data->is_approved == 1)
                    <td style="background-color: #c15670a1;;">
                        معتمدة
                    </td>
                    @endif
                </tr>

                <tr>
                    <th>اجمالي الاصناف على الفاتورة</th>
                    <td id="reload_total_price_result">{{ $data['total_before_discount'] }}</td>
                </tr>

                <tr>
                    <th>الخصم على الفاتورة</th>
                    <td>
                        @if ($data['discount_type'] != null)

                            @if ($data['discount_type'] == 1)
                                خصم بنسبة {{ $data['discount_percent'] }} وتعادل قيمته {{ $data['discount_value'] }}
                            @else
                                قيمة الحصم {{ $data['discount_value'] }}
                            @endif

                        @else
                            لا يوجد خصم على الفاتورة
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>نسبة الضريبة</th>
                    <td>{{ $data['tax_percent'] }}%</td>
                </tr>

                <tr>
                    <th>اجمالي الفاتورة</th>
                    <td id="reload_total_price_result">{{ $data['total_cost'] }}</td>
                </tr>

                <tr>
                    <th>اسم المخزن المستلم</th>
                    <td>{{ $data['store_name'] }}</td>
                </tr>

                <tr>
                    <th>تم الاضافة</th>

                    <td>
                        @if ($data['added_by'] != null)
                            @php
                                $d = new DateTime($data['created_at']);
                                $date = $d->format('d/m/Y الساعة h:i:sA');
                            @endphp

                            {{ $date }}
                            بواسطة
                            {{ $data['added_by_name'] }}
                        @else
                            لا يوجد اي بيانات
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>اخر تحديث</th>

                    <td>
                        @if ($data['updated_by'] != null)
                            @php
                                $d = new DateTime($data['updated_at']);
                                $date = $d->format('d/m/Y الساعة h:i:sA');
                            @endphp

                            {{ $date }}
                            بواسطة
                            {{ $data['updated_by_name'] }}
                        @else
                            لا يوجد اي تحديث
                        @endif
                    </td>
                </tr>
            @else
                <div class="text-danger">
                    لا يوجد بيانات لعرضها
                </div>
            @endif

        </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    </div>
    <!-- /.col -->
</div>


<div>
    @if ($data->is_approved == 0)
        <div style="display: flex; justify-content:space-around;" class="mb-3">
            @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'اضافة صنف') == true)
                <button type="button" class="btn btn-info" style="background-color:#007bff" id="create_item_button">
                    اضافة صنف
                </button>
            @endif

            @if (!@empty($details[0]) && check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'اعتماد') == true)
                <button type="button" class="btn btn-info" style="background-color:#4a9b88" id="approve_pill_button">
                    اعتماد
                </button>
            @endif

            @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'تعديل') == true)
                <a href="{{ route('admin.purchase_header.edit', $data->id) }}" class="btn btn-info">
                    تعديل
                </a>
            @endif
        </div>
    @endif

    <input type="hidden" id="token_search" value="{{ csrf_token() }}">
    <input type="hidden" id="ajax_get_item_unit_route" value="{{ route('admin.purchase_header.get_item_unit') }}">
    <input type="hidden" id="ajax_add_new_item_route" value="{{ route('admin.purchase_header.add_new_item') }}">
    <input type="hidden" id="ajax_edit_item_route" value="{{ route('admin.purchase_header.edit_item') }}">
    <input type="hidden" id="ajax_update_item_route" value="{{ route('admin.purchase_header.update_item') }}">
    <input type="hidden" id="ajax_create_item_route" value="{{ route('admin.purchase_header.create_item') }}">
    <input type="hidden" id="ajax_check_shift_and_reload_money_route" value="{{ route('admin.purchase_header.check_shift_and_reload_money') }}">
    <input type="hidden" id="ajax_load_modal_approved_route" value="{{ route('admin.purchase_header.load_modal_approved') }}">
    <input type="hidden" id="ajax_purchase_auto_serial" value="{{ $data->id }}">
    <input type="hidden" id="ajax_reload_items_route" value="{{ route('admin.purchase_header.reload_items') }}">
    <input type="hidden" id="ajax_reload_total_price_route" value="{{ route('admin.purchase_header.reload_total_price') }}">
</div>

<div class="row" id="reload_items_result">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الاصناف</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body ">
                <div>
                    <table id="example2" class="table table-bordered table-hover">

                        @if (!@empty($details[0]))

                            <tr style="background-color: #007bff; color:white;">
                                <th>اسم الصنف</th>
                                <th>الكمية</th>
                                <th>الوحدة</th>
                                <th>سعر الوحدة</th>
                                <th>الاجمالي</th>
                                <th>تاريخ الاضافة</th>
                                @if ($data->is_approved == 0 && (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'تعديل صنف') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'حذف صنف') == true))
                                    <th>التحكم</th>
                                @endif
                            </tr>

                            @foreach ($details as $detail)
                                <tr>
                                    <td>{{ $detail['item_card_name'] }} <br/>
                                        <span style="color: green">{{ $detail['production_date'] }}</span> <br/>
                                        <span style="color: red">{{ $detail['expire_date'] }}</span>
                                    </td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ $detail->unit_name }}</td>
                                    <td>{{ $detail->unit_price }}</td>
                                    <td>{{ $detail->total_price }}</td>

                                    <td>
                                        @if ($detail['added_by'] != null)
                                            @php
                                                $d = new DateTime($detail['created_at']);
                                                $date = $d->format('d/m/Y الساعة h:i:sA');
                                            @endphp

                                            {{ $date }}
                                            بواسطة
                                            {{ $detail['added_by_name'] }}
                                        @else
                                            لم يتم تسجيل بيانات المضاف
                                        @endif
                                    </td>
                                    @if ($data->is_approved == 0 && (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'تعديل صنف') == true || check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'حذف صنف') == true))
                                        <td>
                                            @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'تعديل صنف') == true)
                                                <button data-purchase_order_detail_id="{{ $detail->id }}" class="btn btn-info edit_item_button">
                                                    تعديل
                                                </button>
                                            @endif
                                            @if (check_control_menu_role('الحركات المخزنية', 'فواتير المشتريات' , 'حذف صنف') == true)
                                                <a href="{{ route('admin.purchase_header.delete_item', [$detail->id, $data->id]) }}" class="btn btn-danger are_you_sure">
                                                    حذف
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
                </div>

            </div>
            <!-- /.card-body -->
        </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

<div class="modal fade" id="edit-item">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">تعديل صنف فاتورة</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="edit-item-result">

        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
          <button type="button" class="btn btn-primary" id="update_item">تعديل</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

{{-- --------------------------------------------------------------------------------- --}}

<div class="modal fade" id="create_item">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">اضافة اصناف للفاتورة</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="create_item_result">

        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
          <button type="button" class="btn btn-primary" id="add_to_pill">اضافة</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="approve_pill">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">اعتمادالفاتورة</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('admin.purchase_header.do_approve', $data->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" id="approve_pill_result">

            </div>
        </form>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('contentheader')
    الحركات المخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.purchase_header.index') }}">المشتريات</a>
@endsection

@section('contentheaderactive')
    عرض الاصناف
@endsection

@section('script')
    <script  src="{{ asset('assets/admin/js/purchase_header.js') }}"> </script>
@endsection
