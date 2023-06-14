@extends('layout.admin')

@section('title')
    الاصناف
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
    <a href="{{ route('admin.inv_item_card.create') }}" style="background-color: #007bff; font-size: 20px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-save"></i> اضافة جديد
    </a>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الاصناف</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="mb-3" style="display: flex">
                <div class="col-4">
                    <label class="control-label" for="barcode">بحث بالباركود</label>
                    <input type="radio" checked name="search_by_radio" id="barcode" value="barcode">
                    <label class="control-label" for="item_code">بحث item_code</label>
                    <input type="radio" name="search_by_radio" id="item_code" value="item_code">
                    <label class="control-label" for="name">بحث بالاسم</label>
                    <input type="radio" name="search_by_radio" id="name" value="name">

                    <input class="form-control" type="search" placeholder="الباركود" id="ajax_search">
                </div>
                <div class="col-4">
                    <label class="control-label">بحث بنوع الصنف</label>
                    <select class="form-control" id="type_search">
                        <option value="all">بحث بالكل</option>
                        <option value="1">مخزني</option>
                        <option value="2">استهلاكي بصلاحية انتهاء</option>
                        <option value="3">عهدة</option>
                    </select>
                </div>
                <div class="col-4">
                    <label class="control-label">بحث بفئة الصنف</label>
                    <select class="form-control" id="category_search">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($inv_itemCard_categories) && !@empty($inv_itemCard_categories))
                            @foreach ($inv_itemCard_categories as $info )
                                <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>تعديل</th>
                            <th>الكود</th>
                            <th>الاسم</th>
                            <th>النوع</th>
                            <th>الفئة</th>
                            <th>الصنف الاب</th>
                            <th>الوحدة الاب</th>
                            <th>الوحدة التجزئة</th>
                            <th>حالة التفعيل</th>
                            <th>التفاصيل</th>
                            <th>حذف</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.inv_item_card.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td>{{ $datum->item_code }}</td>
                                <td>{{ $datum->name }}</td>
                                <td>
                                    @if ($datum->item_type == 1)
                                        مخزني
                                    @elseif ($datum->item_type == 2)
                                        استهلاكي بتاريخ صلاحية
                                    @elseif ($datum->item_type == 3)
                                        عهدة
                                    @else
                                        غير محدد
                                    @endif
                                </td>
                                <td>{{ $datum->inv_itemcard_categories_name }}</td>
                                <td>{{ $datum->parent_inv_itemcard_name }}</td>
                                <td>{{ $datum->unit_name }}</td>
                                <td>{{ $datum->retail_unit_name }}</td>


                                @if ($datum->active == 1)
                                <td style="background-color: #5ab6a0a1;">
                                    مفعل
                                </td>
                                @elseif ($datum->active == 0)
                                <td style="background-color: #c15670a1;;">
                                    غير مفعل
                                </td>
                                @endif

                                <td>
                                    <a href="{{ route('admin.inv_item_card.details', $datum->id) }}"  style="color:#007bff; font-size: 25px;">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.inv_item_card.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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

@endsection

@section('contentheader')
    المخازن
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.inv_item_card.index') }}">فئات المنتجات</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


@section('script')

    <script>
        $(function() {

            function make_search() {
                // get the value from the input to search by
                var search_by_name = $('#ajax_search').val();
                var search_by_type = $('#type_search').val();
                var search_by_category = $('#category_search').val();
                var search_by_radio = $('input[type=radio][name=search_by_radio]:checked').val();

                jQuery.ajax({
                    // first argument is the where the from route to
                    url:"{{ route('admin.inv_item_card.ajax_search') }}",
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{search_by_name:search_by_name,search_by_type:search_by_type,search_by_category:search_by_category,search_by_radio:search_by_radio, '_token':"{{ csrf_token() }}"},
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {

                    }
                });
            }


            $(document).on('input', '#ajax_search', function() {
                make_search();
            });

            $(document).on('click', '#ajax_search_pagination a', function(e) {
                e.preventDefault();
                // get the value from the input to search by
                var search_by_name = $('#ajax_search').val();
                var search_by_type = $('#type_search').val();
                var search_by_category = $('#category_search').val();
                var search_by_radio = $('input[type=radio][name=search_by_radio]:checked').val();

                jQuery.ajax({
                    // first argument is the where the from route to
                    url:$(this).attr("href"),
                    // second argument is sending type of the form
                    type:'post',
                    // third argument is the type of the returned data from the model
                    datatype:'html',
                    // first argument is
                    cache:false,
                    // forth we send the search data and the token
                    data:{search_by_name:search_by_name,search_by_type:search_by_type,search_by_category:search_by_category,search_by_radio,search_by_radio, '_token':"{{ csrf_token() }}"},
                    // If the form and everything okay
                    success:function(data){
                        $('#ajax_search_result').html(data);
                    },
                    // If the there is an error
                    error:function() {

                    }
                });
            });


            $(document).on('change', '#type_search', function() {
                make_search();
            });
            $(document).on('change', '#category_search', function() {
                make_search();
            });

        });
    </script>

@endsection
