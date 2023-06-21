@extends('layout.admin')

@section('title')
    المناديب
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
    <a href="{{ route('admin.delegates.create') }}" style="background-color: #007bff; font-size: 20px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-save"></i> اضافة جديد
    </a>
</div>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات المناديب</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="mb-3" style="display: flex">
                <div class="col-md-6">
                    <label class="control-label" for="cus_code">بحث برقم المندوب</label>
                    <input type="radio" checked name="search_by_radio" id="cus_code" value="cus_code">
                    <label class="control-label" for="acc_number">بحث برقم الحساب</label>
                    <input type="radio" name="search_by_radio" id="acc_number" value="acc_number">
                    <label class="control-label" for="name">بحث بالاسم الاخير</label>
                    <input type="radio" name="search_by_radio" id="name" value="name">

                    <input class="form-control" type="search" placeholder="رقم المندوب - رقم الحساب - الاسم الاخير" id="ajax_search">
                </div>

                <div class="col-md-3">
                    <label class="control-label">حالة الحساب</label>
                    <select class="form-control select2" id="current_status_search">
                        <option value="all">بحث بالكل</option>
                        <option value="0">دائن</option>
                        <option value="1">متزن</option>
                        <option value="2">مدين</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="control-label">حالة الحساب اول المدة</label>
                    <select class="form-control select2" id="start_status_search">
                        <option value="all">بحث بالكل</option>
                        <option value="0">دائن</option>
                        <option value="1">متزن</option>
                        <option value="2">مدين</option>
                    </select>
                </div>
            </div>

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>تعديل</th>
                            <th>كود المندوب</th>
                            <th>اسم المندوب</th>
                            <th>رقم حساب المندوب</th>
                            <th>الرصيد</th>
                            <th>رصيد اول المدة</th>
                            <th>التفاصيل</th>
                            <th>حذف</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.delegates.edit', $datum->id) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td>{{ $datum['delegate_code'] }}</td>
                                <td>{{ $datum->first_name }}  {{ $datum->last_name }}</td>
                                <td>{{ $datum['account_number'] }}</td>
                                <td>
                                    @if($datum->current_balance == 0)
                                    متزن
                                    @elseif ($datum->current_balance > 0)
                                        مدين ({{ $datum->current_balance }})
                                    @else
                                        دائن ({{ $datum->current_balance * (-1) }})
                                    @endif
                                </td>
                                <td>
                                    @if($datum->start_balance == 0)
                                        متزن
                                    @elseif ($datum->start_balance > 0)
                                        مدين ({{ $datum->start_balance }})
                                    @else
                                        دائن ({{ $datum->start_balance * (-1) }})
                                    @endif
                                </td>
                                <td>
                                    <button data-id="{{ $datum->id }}" class="details_button btn" style="color: rgb(38, 123, 29); font-size: 25px;">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('admin.delegates.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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

<div class="modal fade" id="details_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">باقي تفاصيل العميل</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="details_result">

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
        </div>
        </div>
    </div>
</div>

@endsection

@section('contentheader')
    المناديب
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.delegates.index') }}">المناديب</a>
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
            var search_by_radio = $('input[type=radio][name=search_by_radio]:checked').val();
            var current_status_search = $('#current_status_search').val();
            var start_status_search = $('#start_status_search').val();

            jQuery.ajax({
                // first argument is the where the from route to
                url:"{{ route('admin.delegates.ajax_search') }}",
                // second argument is sending type of the form
                type:'post',
                // third argument is the type of the returned data from the model
                datatype:'html',
                // first argument is
                cache:false,
                // forth we send the search data and the token
                data:{
                    search_by_name:search_by_name,
                    search_by_radio:search_by_radio,
                    current_status_search:current_status_search,
                    start_status_search:start_status_search,
                    '_token':"{{ csrf_token() }}"},
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

        $(document).on('change', '#current_status_search', function() {
            make_search();
        });

        $(document).on('change', '#start_status_search', function() {
            make_search();
        });

        $(document).on('click', '#ajax_search_pagination a', function(e) {
            e.preventDefault();
            // get the value from the input to search by
            var search_by_name = $('#ajax_search').val();
            var current_status_search = $('#current_status_search').val();
            var start_status_search = $('#start_status_search').val();
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
                data:{
                    search_by_name:search_by_name,
                    search_by_radio:search_by_radio,
                    current_status_search:current_status_search,
                    start_status_search:start_status_search,
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

        $(document).on('change', 'input[type=radio][name=search_by_radio]', function() {
            make_search();
        });

        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            // get the value from the input to search by
            var id = $(this).data('id');

            jQuery.ajax({
                // first argument is the where the from route to
                url:"{{ route('admin.delegates.details') }}",
                // second argument is sending type of the form
                type:'post',
                // third argument is the type of the returned data from the model
                datatype:'html',
                // first argument is
                cache:false,
                // forth we send the search data and the token
                data:{id:id, '_token':"{{ csrf_token() }}"},
                // If the form and everything okay
                success:function(data){
                    $('#details_result').html(data);
                    $('#details_modal').modal('show');
                },
                // If the there is an error
                error:function() {

                }
            });
        });

    });
</script>

@endsection
