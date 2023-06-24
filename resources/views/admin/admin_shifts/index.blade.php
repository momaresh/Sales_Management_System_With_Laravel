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
    @if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'اضافة') == true)
        <a href="{{ route('admin.admin_shifts.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-save"></i> استلام شفت
        </a>
    @endif
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
                            <th>كود الشفت</th>
                            <th>اسم المستخدم</th>
                            <th>اسم الخزنة</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ النهاية</th>
                            <th>تم الانتهاء</th>
                            <th>التحكم</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                @if ($datum->is_finished == 0 && $datum->admin_id == auth()->user()->id)
                                    <td style="background-color: #eee880a1">{{ $datum->shift_code }}</td>
                                @else
                                    <td>{{ $datum->shift_code }}</td>
                                @endif
                                <td>{{ $datum->admin_name }}</td>
                                <td>{{ $datum->treasuries_name }}</td>
                                <td>{{ $datum->start_date }}</td>
                                <td>{{ $datum->end_date }}</td>
                                @if ($datum->is_finished == 1)
                                    <td style="background-color: #c15670a1">مغلق</td>
                                @else
                                    <td style="background-color: #5ab6a0a1">مفتوح</td>
                                @endif

                                <td>

                                    @if ($datum->is_finished == 0 && check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'انهاء شفت') == true)
                                        <a href="{{ route('admin.admin_shifts.end_shift', $datum->id) }}" class="btn btn-danger">
                                            انهاء
                                        </a>
                                    @endif

                                    @if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'مراجعة شفت') == true && $datum->is_finished == 1 && @empty($datum->delivered_to_shift_id) && !@empty($check_shift) && $datum->allowed_review == true)
                                        <button data-id="{{ $datum->id }}"  data-money="{{ $datum->money_should_delivered }}" class="btn btn-info review_shift_btn">
                                            مراجعة
                                        </button>
                                    @endif

                                    @if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'طباعة') == true)
                                        <a href="{{ route('admin.admin_shifts.printA4', [$datum->id]) }}" class="btn btn-success">
                                            A4 <i class="fa-solid fa-print"></i>
                                        </a>
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

<div class="modal fade" id="review_shift_modal">
    <div class="modal-dialog modal-xl" style="width: 95%;">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #0793a9; color: white">
            <h4 class="modal-title">مراجعة الشفت</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="add_new_customer_result">
            <form action="{{ route('admin.admin_shifts.review_shift') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label>المبلغ الذي يجب استلامه من الشفت</label>
                        <input type="text" readonly oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="should_paid" name="should_paid" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>المبلغ الذي تم استلامه من الشفت</label>
                        <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'')" id="what_paid" name="what_paid" class="form-control">
                    </div>
                </div>

                <input type="hidden" name="do_review_shift_id" value="@if (!@empty($check_shift->id)) $check_shift['id'] @endif">
                <input type="hidden" name="was_review_shift_id" id="was_review_shift_id" value="">

                <div class="col-md-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary" id="review_sub">استلام ومراجعة</button>
                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default " data-dismiss="modal">الغاء</button>
        </div>
        </div>
    </div>
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

@section('script')
    <script>
        $(function() {
            $(document).on('click', '.review_shift_btn', function() {
                var money = $(this).data('money');
                var id = $(this).data('id');
                $("#should_paid").val(money);
                $("#what_paid").val(money);
                $("#was_review_shift_id").val(id);

                $('#review_shift_modal').modal('show');
            });

            $(document).on('click', '#review_sub', function(e) {
                if ($("#what_paid").val() == '') {
                    alert('يجب ادخال المبلغ المستلم');
                    $("#what_paid").focus();
                    return false;
                }

                if ($("#should_paid").val() == '') {
                    alert('يجب ادخال المبلغ المتوجب دفعه');
                    $("#should_paid").focus();
                    return false;
                }

                if ($("#do_review_shift_id").val() == '') {
                    alert('يجب عدم حذف الشفت الذي سوف يقوم بالمراجعة');
                    return false;
                }
                if ($("#was_review_shift_id").val() == '') {
                    alert('يجب عدم حذف الشفت الذي سوف يراجع');
                    return false;
                }
            })
        })
    </script>
@endsection
