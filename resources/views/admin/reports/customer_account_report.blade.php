@extends('layout.admin')

@section('title')
    تقرير حساب عميل
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
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">تقارير العملاء</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('admin.reports.customer_account_report') }}" method="post" target="_blank">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">بحث بالعميل</label>
                        <select  class="form-control select2" name="code" id="code">
                            <option value="">بحث بالكل</option>
                            @if (@isset($customers) && !@empty($customers))
                                @foreach ($customers as $info )
                                    <option data-date="{{ $info->date }}" value="{{ $info->id }}">{{ $info->first_name }} {{ $info->last_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="">نوع التقرير</label>
                        <select class="form-control select2" name="report_type" id="report_type">
                            <option value="1">كشف حساب اجمالي</option>
                            <option selected value="2">كشف حساب تفصيلي خلال فترة</option>
                            <option value="3">كشف حساب المبيعات خلال فترة</option>
                            <option value="4">كشف حساب مرتجع المبيعات العام خلال فترة</option>
                            <option value="6">كشف حساب مرتجع المبيعات الاصل خلال فترة</option>
                            <option value="5">كشف حساب حركة النقدية خلال فترة</option>
                        </select>
                    </div>

                    <div class="col-md-4 related_date">
                        <label class="control-label" for="from_date">من تاريخ</label>
                        <input class="form-control" type="date" id="from_date" name="from_date" >
                    </div>

                    <div class="col-md-4 related_date">
                        <label class="control-label" for="to_date">الى تاريخ</label>
                        <input class="form-control" type="date" id="to_date" name="to_date">
                        <input type="hidden" id="current_date" value="{{ date('Y-m-d') }}">
                    </div>
                    @if (check_control_menu_role('التقارير', 'كشف حساب عميل' , 'طباعة') == true)
                        <div class="col-md-12">
                            <button type="submit" id="report_btn" class="btn btn-primary mt-3">عرض التقرير</button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>

@endsection

@section('contentheader')
    التقارير
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.reports.customer_account_report') }}">تقرير حساب عميل</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/reports.js') }}"></script>
@endsection
