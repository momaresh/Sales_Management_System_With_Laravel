@extends('layout.admin')

@section('title')
    حركات شفتات الخزن
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
    <a href="{{ route('admin.admin_shifts.create') }}" style="background-color: #007bff; font-size: 20px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
        <i class="fas fa-save"></i> استلام شفت
    </a>
</div>



<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات شفتات الخزن</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>تعديل</th>
                            <th>كود الشفت</th>
                            <th>اسم المستخدم</th>
                            <th>اسم الخزنة</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ النهاية</th>
                            <th>تم الانتهاء</th>
                            <th>حذف</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.accounts.edit', $datum->shift_code) }}" style="color: rgb(149, 35, 35); font-size: 25px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                @if ($datum->is_finished == 0)
                                    <td style="background-color: #eee880a1">{{ $datum->shift_code }}</td>
                                @else
                                    <td>{{ $datum->shift_code }}</td>
                                @endif
                                <td>{{ $datum->admin_name }}</td>
                                <td>{{ $datum->treasuries_name }}</td>
                                <td>{{ $datum->start_date }}</td>
                                <td>{{ $datum->end_date }}</td>
                                @if ($datum->is_finished == 1)
                                    <td style="background-color: #5ab6a0a1">نعم</td>
                                @else
                                    <td style="background-color: #c15670a1">لا</td>
                                @endif

                                <td>
                                    <a href="{{ route('admin.accounts.delete', $datum->id) }}" class="are_you_sure" style="color: rgb(149, 35, 35); font-size: 25px;">
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
    حركات شفتات الخزن
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.admin_shifts.index') }}">شفتات الخزن</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection


{{-- @section('script')

    <script>
        $(function() {
            function make_search() {
                var search_by_text = $("#search_by_text").val();
                var account_type = $("#account_type_search").val();
                var is_parent = $("#is_parent_search").val();
                var active_search = $("#active_search").val();

                jQuery.ajax({
                    url: "{{ route('admin.accounts.ajax_search') }}",
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        search_by_text: search_by_text,
                        account_type: account_type,
                        is_parent: is_parent,
                        active_search: active_search,
                        '_token':"{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $("#ajax_search_result").html(data);
                    },
                    error: function() {}
                });
            }

            $(document).on('input', '#search_by_text', function(e) {
                make_search();
            });
            $(document).on('change', '#account_type_search', function(e) {
                make_search();
            });
            $(document).on('change', '#is_parent_search', function(e) {
                make_search();
            });
            $(document).on('change', '#active_search', function(e) {
                make_search();
            });

            $(document).on('click', '#ajax_pagination_search a ', function(e) {
                e.preventDefault();
                var search_by_text = $("#search_by_text").val();
                var account_type = $("#account_type_search").val();
                var is_parent = $("#is_parent_search").val();
                var active_search = $("#active_search").val();
                var url = $(this).attr("href");
                jQuery.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        search_by_text: search_by_text,
                        account_type: account_type,
                        is_parent: is_parent,
                        active_search: active_search,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $("#ajax_search_result").html(data);
                    },
                    error: function() {}
                });
            });

        });
    </script>

@endsection --}}
