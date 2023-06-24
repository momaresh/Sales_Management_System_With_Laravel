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

<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الجرد</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">

            @if (!@empty($data))

                <tr>
                    <th>تاريخ الجرد</th>
                    <td>{{ $data->inventory_date }}</td>
                </tr>

                <tr>
                    <th>نوع الجرد</th>
                    <td>
                        @if ($data->inventory_type == 1)
                            يومي
                        @elseif ($data->inventory_type == 2)
                            اسبوعي
                        @elseif ($data->inventory_type == 3)
                            شهري
                        @elseif ($data->inventory_type == 4)
                            سنوي
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>حالة الجرد</th>
                    @if ($data->is_closed == 0)
                        <td style="background-color: #5ab6a0a1;">
                            مفتوح
                        </td>
                    @elseif ($data->is_closed == 1)
                        <td style="background-color: #c15670a1;;">
                            مغلق
                        </td>
                    @endif
                </tr>

                <tr>
                    <th>اجمالي باتشات الجرد</th>
                    <td></td>
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
        <div class="mt-2 text-center">
            <a href="{{ route('admin.inv_stores_inventory.printA4', $data->id) }}" class="btn btn-success">
                A4 <i class="fa-solid fa-print"></i>
            </a>
            <a href="{{ route('admin.inv_stores_inventory.close_header', $data->id) }}" @if ($data->is_closed == 1) @endif class="btn btn-warning">
                ترحيل
            </a>
            <a href="{{ route('admin.inv_stores_inventory.delete', $data->id) }}" class="are_you_sure btn btn-danger">
                <i class="fa-solid fa-trash-can"></i>
            </a>
        </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    </div>
    <!-- /.col -->
</div>


<div>
    @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اضافة') == true)
        <button data-toggle="modal" data-target="#adding_item_inventory" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-plus-circle"></i> اضافة جديد
        </button>
    @endif
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الباتشات في مخزن الجرد</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div>
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($details[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>كود الباتش</th>
                            <th>اسم الصنف</th>
                            <th>الكمية بالباتش</th>
                            <th>الكمية الدفترية</th>
                            <th>الفرق</th>
                            <th>سعر الوحدة</th>
                            <th>سبب الزيادة/النقص</th>
                            @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'تعديل باتش') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'حذف باتش') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اغلاق باتش') == true)
                                <th>التحكم</th>
                            @endif
                        </tr>

                        @foreach ($details as $detail)
                            <tr>
                                <td>{{ $detail->batch_id }}</td>
                                <td>
                                    {{ $detail->item_name }} <br>
                                    <span style="color: green">{{ $detail['production_date'] }}</span> <br/>
                                    <span style="color: red">{{ $detail['expire_date'] }}</span>
                                </td>
                                <td>{{ $detail->old_quantity }}</td>
                                <td>{{ $detail->new_quantity }}</td>
                                <td>{{ $detail->different_quantity }}</td>
                                <td>{{ $detail->unit_cost * 1 }}</td>
                                <td>{{ $detail->notes }}</td>
                                @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'تعديل باتش') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'حذف باتش') == true || check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اغلاق باتش') == true)
                                    @if ($detail->is_closed == 0)
                                        <td>
                                            @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'تعديل باتش') == true)
                                                <button data-id={{ $detail->id }} id="update_batch_btn" class="btn btn-info">
                                                    <i class="fa-solid fa-edit"></i>
                                                </button>
                                            @endif

                                            @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'اغلاق باتش') == true)
                                                <a href="{{ route('admin.inv_stores_inventory.close_detail', [$detail->id, $data->id]) }}" class="btn btn-warning are_you_sure">
                                                    ترحيل
                                                </a>
                                            @endif

                                            @if (check_control_menu_role('الحركات المخزنية', 'جرد المخازن' , 'حذف باتش') == true)
                                                <a href="{{ route('admin.inv_stores_inventory.delete_detail', [$detail->id, $data->id]) }}" class="btn btn-danger are_you_sure">
                                                    حذف
                                                </a>
                                            @endif
                                        </td>
                                    @elseif ($detail->is_closed == 1)
                                        <td style="background-color: #c15670a1;;">
                                            مغلق ومرحل
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
            </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

<div class="modal fade" id="adding_item_inventory">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #0793a9; color: white">
            <h4 class="modal-title">اضافة باتش للجرد</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.inv_stores_inventory.create_detail', $data['id']) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>هل تريد اضافة الباتشات الفاضية</label>
                            <select name="empty_batches" id="empty_batches" class="form-control select2">
                                <option value="1">نعم</option>
                                <option value="0">لا</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>هل تريد اضافة كل الاصناف</label>
                            <select name="all_items" id="all_items" class="form-control select2">
                                <option value="1">نعم</option>
                                <option value="0">لا</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4" id="item_code_div" style="display: none">
                        <div class="form-group">
                            <label>الصنف في الباتش</label>
                            <select name="item_code" id="item_code" class="form-control select2">
                                <option value="">اختر الصنف</option>
                                @if (@isset($items_card) && !@empty($items_card))
                                    @foreach ($items_card as $info )
                                        <option value="{{ $info->item_code }}"> {{ $info->name }} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="submit" id="add_detail" class="btn btn-primary ">اضافة</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default " data-dismiss="modal">الغاء</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_batch_modal">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #0793a9; color: white">
            <h4 class="modal-title">اضافة الكمية الدفترية</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="update_batch_result">

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default " data-dismiss="modal">الغاء</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('contentheader')
    الحركات المخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.inv_stores_inventory.index') }}">جرد المخازن</a>
@endsection

@section('contentheaderactive')
    عرض الباتشات
@endsection

@section('script')
    <script>
        $(function() {
            $(document).on('change', '#all_items', function() {
                if ($(this).val() == 0) {
                    $("#item_code_div").show();
                }
                else {
                    $("#item_code_div").hide();
                }
            });

            $(document).on('click', '#add_detail', function(e) {
                if ($("#all_items").val() == 0) {
                    if ($("#item_code").val() == '') {
                        alert('من فضلك اختر الصنف');
                        $("#item_code").focus();
                        return false;
                    }
                }
            });

            $(document).on('click', '#update_batch_btn', function() {
                var id = $(this).data('id');
                jQuery.ajax({
                    url:"{{ route('admin.inv_stores_inventory.load_modal_update_batch') }}",
                    type:'post',
                    datatype:'html',
                    cache:false,
                    data:{
                        id:id,
                        '_token':"{{ csrf_token() }}"},
                    success:function(data){
                        $('#update_batch_result').html(data);
                        $('#update_batch_modal').modal('show');
                    },
                    error:function() {

                    }
                });
            })

            $(document).on('click', '#update_batch_detail', function(e) {
                if ($("#new_quantity").val() == '') {
                    alert('من فضلك ادخل الكمية في الجرد');
                    $("#new_quantity").focus();
                    return false;
                }
            });


        })
    </script>
@endsection
