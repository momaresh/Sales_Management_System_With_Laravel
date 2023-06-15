@extends('layout.admin')

@section('title')
    الاصناف في المخازن
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
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الاصناف في المخازن</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="mb-3 row">
                <input type="hidden" id='ajax_search_route' value="{{ route('admin.purchase_header.ajax_search') }}">
                <input type="hidden" id='ajax_token' value="{{ csrf_token() }}">

                <div class="col-md-4">
                    <label class="control-label">بحث بالاصناف</label>
                    <select class="form-control select2" name="item_code_search" id="item_code_search">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($items) && !@empty($items))
                            @foreach ($items as $info )
                                <option value="{{ $info->item_code }}">{{ $info->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="control-label">بحث بالمخازن</label>
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
                    <label class="control-label">بحث بالمخازن</label>
                    <select class="form-control select2" name="unit_id_search" id="unit_id_search">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($units) && !@empty($units))
                            @foreach ($units as $info )
                                <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="control-label" for="production_date_search">تاريخ الانتاج</label>
                    <input class="form-control" type="date" id="production_date_search" name="production_date_search" >
                </div>

                <div class="col-md-4">
                    <label class="control-label" for="expire_date_search">تاريخ الانتهاء</label>
                    <input class="form-control" type="date" id="expire_date_search" name="expire_date_search" >
                </div>
            </div>

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>اسم المخزن</th>
                            <th>اسم الصنف</th>
                            <th>نوع الوحدة</th>
                            <th>الكمية</th>
                            <th>سعر الواحدة</th>
                            <th>سعر الكل</th>
                            <th>تاريخ الانتاج</th>
                            <th>تاريخ الانتهاء</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>{{ $datum->store_name }}</td>
                                <td>{{ $datum->item_name }}</td>
                                <td>{{ $datum->unit_name }}</td>
                                <td>{{ $datum->quantity }}</td>
                                <td>{{ $datum->unit_cost_price }}</td>
                                <td>{{ $datum->total_cost_price }}</td>
                                <td>{{ $datum->production_date }}</td>
                                <td>{{ $datum->expire_date }}</td>
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
    حركات مخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.account_types.index') }}">الاصناف في المخازن</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('script')
<script>
    $(function() {

        function make_search() {
            // get the value from the input to search by
            var item_code_search = $('#item_code_search').val();
            var store_id_search = $('#store_id_search').val();
            var unit_id_search = $('#unit_id_search').val();
            var production_date_search = $('#production_date_search').val();
            var expire_date_search = $('#expire_date_search').val();

            jQuery.ajax({
                // first argument is the where the from route to
                url:"{{ route('admin.items_in_stores.ajax_search') }}",
                // second argument is sending type of the form
                type:'post',
                // third argument is the type of the returned data from the model
                datatype:'html',
                // first argument is
                cache:false,
                // forth we send the search data and the token
                data:{
                    item_code_search:item_code_search,
                    store_id_search:store_id_search,
                    unit_id_search:unit_id_search,
                    production_date_search:production_date_search,
                    expire_date_search:expire_date_search,
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
            var item_code_search = $('#item_code_search').val();
            var store_id_search = $('#store_id_search').val();
            var unit_id_search = $('#unit_id_search').val();
            var production_date_search = $('#production_date_search').val();
            var expire_date_search = $('#expire_date_search').val();

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
                    item_code_search:item_code_search,
                    store_id_search:store_id_search,
                    unit_id_search:unit_id_search,
                    production_date_search:production_date_search,
                    expire_date_search:expire_date_search,
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

        $(document).on('change', '#item_code_search', function() {
            make_search();
        });

        $(document).on('change', '#store_id_search', function() {
            make_search();
        });

        $(document).on('change', '#unit_id_search', function() {
            make_search();
        });

        $(document).on('change', '#production_date_search', function() {
            make_search();
        });

        $(document).on('change', '#expire_date_search', function() {
            make_search();
        });
    });
</script>
@endsection
