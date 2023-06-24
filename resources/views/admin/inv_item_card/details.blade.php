@extends('layout.admin')

@section('title')
    تفاصيل الاصناف
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

<style>
    th, td {
        text-align: center;
    }
    #example2 th {
        color: #007bff;
    }
</style>

<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الصنف</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">

            @if (!@empty($data))

                <tr>
                    <th>باركود الصنف</th>
                    <th>اسم الصنف</th>
                    <th>نوع الصنف</th>
                </tr>

                <tr>
                    <td>{{ $data->barcode }}</td>
                    <td>{{ $data->name }}</td>
                    <td>
                        @if ($data->item_type == 1)
                            مخزني
                        @elseif ($data->item_type == 2)
                            استهلاكي بتاريخ صلاحية
                        @elseif ($data->item_type == 3)
                            عهدة
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>فئة الصنف</th>
                    <th> الصنف الاب له</th>
                    <th> وحدة القياس الرئيسية</th>
                </tr>

                <tr>
                    <td>{{ $data->inv_itemcard_categories_name }}</td>
                    <td> @if (@empty($data->parent_inv_itemcard_name)) لا يوجد @else {{ $data->parent_inv_itemcard_name }} @endif</td>
                    <td>{{ $data->unit_name }}</td>
                </tr>

                <tr>
                    <th> هل للصنف وحدة قياس فرعية</th>
                    @if ($data->does_has_retailunit == 1)
                        <th> وحدة القياس التجزئة</th>
                        <th> عدد وحدات التجزئة ({{ $data->retail_unit_name }}) بالنسبة للاب ({{ $data->unit_name }})</th>
                    @endif
                </tr>

                <tr>
                    <td>
                        @if ($data->does_has_retailunit == 1)
                            نعم
                        @else
                            لا
                        @endif
                    </td>
                    @if ($data->does_has_retailunit == 1)
                        <td>{{ $data->retail_unit_name }}</td>
                        <td>{{ $data->retail_uom_quntToParent }}</td>
                    @endif
                </tr>

                <tr>
                    <th> سعر القطاعي بوحدة ({{ $data->unit_name }}) </th>
                    <th> سعر النص جملة بوحدة ({{ $data->unit_name }}) </th>
                    <th> سعر الجملة بوحدة ({{ $data->unit_name }}) </th>
                </tr>

                <tr>
                    <td>{{ $data->price_per_one_in_master_unit }}</td>
                    <td>{{ $data->price_per_half_group_in_master_unit }}</td>
                    <td>{{ $data->price_per_group_in_master_unit }}</td>
                </tr>

                @if ($data->does_has_retailunit == 1)
                <tr>
                    <th> سعر القطاعي بوحدة ({{ $data->retail_unit_name }}) </th>
                    <th> سعر النص جملة بوحدة ({{ $data->retail_unit_name }}) </th>
                    <th> سعر الجملة بوحدة ({{ $data->retail_unit_name }}) </th>
                </tr>

                <tr>
                    <td>{{ $data->price_per_one_in_retail_unit }}</td>
                    <td>{{ $data->price_per_half_group_in_retail_unit }}</td>
                    <td>{{ $data->price_per_group_in_retail_unit }}</td>
                </tr>
                @endif


                <tr>
                    <th> سعر تكلفة الشراء لوحدة ({{ $data->unit_name }}) </th>
                    @if ($data->does_has_retailunit == 1)
                        <th> سعر تكلفة الشراء لوحدة ({{ $data->retail_unit_name }}) </th>
                    @endif
                    <th>هل للصنف سعر ثابت</th>
                </tr>

                <tr>
                    <td>{{ $data->cost_price_in_master }}</td>
                    @if ($data->does_has_retailunit == 1)
                        <td>{{ $data->cost_price_in_retail }}</td>
                    @endif
                    <td>
                        @if ($data->has_fixed_price == 1)
                            نعم
                        @else
                            لا
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>كل الكمية بوحدة ({{ $data->unit_name }})</th>
                    @if ($data->does_has_retailunit == 1)
                        <th>المتبقي من ({{ $data->unit_name }})</th>
                        <th>كل الكمية بوحدة ({{ $data->retail_unit_name }})</th>
                    @endif
                </tr>

                <tr>
                    <td>{{ $data->all_quantity_with_master_unit }}</td>
                    @if ($data->does_has_retailunit == 1)
                        <td>{{ $data->remain_quantity_in_retail }}</td>
                        <td>{{ $data->all_quantity_with_retail_unit }}</td>
                    @endif
                </tr>


                <tr>
                    <th>حالة الصنف</th>
                    <th>تم الاضافة</th>
                    <th>اخر تحديث</th>
                </tr>

                <tr>
                    @if ($data->active == 1)
                    <td style="background-color: #5ab6a0a1;">
                        مفعل
                    </td>
                    @elseif ($data->active == 0)
                    <td style="background-color: #c15670a1;;">
                        غير مفعل
                    </td>
                    @endif
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

                    <tr>
                        <th>الصورة</th>
                    </tr>
                    <tr>
                        <td><img id="uploadedimg" src="{{ asset('assets/admin/uploads/item_card_images/'.$data->item_img) }}" alt="uploaded img" style="width: 300px; width: 300px;" ></td>
                    </tr>
                </tr>

            @else
                <div class="text-danger">
                    لا يوجد بيانات لعرضها
                </div>
            @endif

        </table>
        @if (check_control_menu_role('المخازن', 'الاصناف' , 'تعديل') == true)
            <div>
                <a href="{{ route('admin.inv_item_card.edit', $data->id) }}" class="btn btn-primary mt-2">
                    تعديل
                </a>
            </div>
        @endif
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

<input type="hidden" id="item_code_search" value="{{ $data['item_code'] }}">

@if (check_control_menu_role('المخازن', 'الاصناف' , 'عرض الحركات') == true)
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الحركات على الصنف</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <div class="mb-3 row">
                    <div class="col-md-4">
                        <label class="control-label">بحث بالمخازن</label>
                        <select class="form-control select2" name="store_search" id="store_search">
                            <option value="all">بحث بالكل</option>
                            @if (@isset($stores) && !@empty($stores))
                                @foreach ($stores as $info )
                                    <option value="{{ $info->id }}">{{ $info->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بحث بقسم الحركة</label>
                            <select name="category_search" id="category_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                @if (@isset($categories) && !@empty($categories))
                                @foreach ($categories as $info )
                                    <option value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بحث بنوع الحركة</label>
                            <select name="type_search" id="type_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                @if (@isset($types) && !@empty($types))
                                @foreach ($types as $info )
                                    <option value="{{ $info->id }}"> {{ $info->type }} </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="control-label" for="from_date_search">من تاريخ</label>
                        <input class="form-control" type="date" id="from_date_search" name="from_date_search" >
                    </div>

                    <div class="col-md-4">
                        <label class="control-label" for="to_date_search">الى تاريخ</label>
                        <input class="form-control" type="date" id="to_date_search" name="to_date_search" >
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بحث بالترتيب</label>
                            <select name="order_search" id="order_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                <option value="asc">ترتيب تصاعدي</option>
                                <option value="desc">ترتيب تنازلي</option>
                            </select>
                        </div>
                    </div>


                </div>

                <div id="ajax_search_result">
                    <table id="example1" class="table table-bordered table-hover">

                        @if (!@empty($moves[0]))

                            <tr style="background-color: #007bff; color:white;">
                                <th>المخزن</th>
                                <th>القسم</th>
                                <th>الحركة</th>
                                <th>البيان</th>
                                <th>الكمية قبل الحركة</th>
                                <th>الكمية بعد الحركة</th>
                                <th>تم الاضافة</th>
                            </tr>

                            @foreach ($moves as $move)
                                <tr>
                                    <td>{{ $move->store_name }}</td>
                                    <td>{{ $move->category_name }}</td>
                                    <td>{{ $move->type_name }}</td>
                                    <td>{{ $move->byan }}</td>
                                    <td><span style="color: #06a782">الكمية في المخزن الحالي {{ $move->quantity_before_movement_in_current_store }}</span> <span style="color: #ad002ba1">الكمية في كل المخارن {{ $move->quantity_before_movement }}</span></td>
                                    <td><span style="color: #06a782">الكمية في المخزن الحالي {{ $move->quantity_after_movement_in_current_store }}</span> <span style="color: #ad002ba1">الكمية في كل المخارن {{ $move->quantity_after_movement }}</span></td>
                                    <td>
                                        @if ($move['added_by'] != null)
                                            @php
                                                $d = new DateTime($move['created_at']);
                                                $date = $d->format('d/m/Y الساعة h:i:sA');
                                            @endphp

                                            {{ $date }}
                                            بواسطة
                                            {{ $move['added_by_name'] }}
                                        @else
                                            لا يوجد اي بيانات
                                        @endif
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
                        {{ $moves->links() }}
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
    المخازن
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.inv_item_card.index') }}">الاصناف</a>
@endsection

@section('contentheaderactive')
    عرض التفاصيل
@endsection


@section('script')

    <script>
        $(function() {

            function make_search() {
                // get the value from the input to search by
                var store_search = $('#store_search').val();
                var category_search = $('#category_search').val();
                var type_search = $('#type_search').val();
                var from_date_search = $('#from_date_search').val();
                var to_date_search = $('#to_date_search').val();
                var order_search = $('#order_search').val();
                var item_code_search = $('#item_code_search').val();

                jQuery.ajax({
                    // first argument is the where the from route to
                    url:"{{ route('admin.inv_item_card.moves_ajax_search') }}",
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{
                        store_search:store_search,
                        category_search:category_search,
                        type_search:type_search,
                        from_date_search:from_date_search,
                        to_date_search:to_date_search,
                        order_search:order_search,
                        item_code_search:item_code_search,
                        '_token':"{{ csrf_token() }}"},
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {

                    }
                });
            }

            $(document).on('click', '#ajax_search_pagination a', function(e) {
                e.preventDefault();
                // get the value from the input to search by
                var store_search = $('#store_search').val();
                var category_search = $('#category_search').val();
                var type_search = $('#type_search').val();
                var from_date_search = $('#from_date_search').val();
                var to_date_search = $('#to_date_search').val();
                var order_search = $('#order_search').val();
                var item_code_search = $('#item_code_search').val();

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
                        store_search:store_search,
                        category_search:category_search,
                        type_search:type_search,
                        from_date_search:from_date_search,
                        to_date_search:to_date_search,
                        order_search:order_search,
                        item_code_search:item_code_search,
                        '_token':"{{ csrf_token() }}"},
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {

                    }
                });
            });


            $(document).on('change', '#store_search', function() {
                make_search();
            });
            $(document).on('change', '#category_search', function() {
                make_search();
            });

            $(document).on('change', '#type_search', function() {
                make_search();
            });
            $(document).on('change', '#from_date_search', function() {
                make_search();
            });

            $(document).on('change', '#to_date_search', function() {
                make_search();
            });

            $(document).on('change', '#order_search', function() {
                make_search();
            });
        });
    </script>

@endsection
