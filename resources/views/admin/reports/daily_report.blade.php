@extends('layout.admin')

@section('title')
    تقرير الحركات اليومية
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
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">التقارير اليومية</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('admin.reports.daily_report') }}" method="post" target="_blank">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="">نوع التقرير</label>
                        <select class="form-control select2" name="report_type" id="report_typee">
                            <option value="1">كشف تقرير اجمالي</option>
                            <option value="2">كشف تقرير تفصيلي</option>
                        </select>
                    </div>

                    <div class="col-md-4 related_date">
                        <label class="control-label" for="from_date">من تاريخ</label>
                        <input class="form-control" type="date" id="from_date" name="from_date" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-4 related_date">
                        <label class="control-label" for="to_date">الى تاريخ</label>
                        <input class="form-control" type="date" id="to_date" name="to_date" value="{{ date('Y-m-d') }}">
                    </div>
                    @if (check_control_menu_role('التقارير', 'كشف التقارير اليومية' , 'طباعة') == true)
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
    <a href="{{ route('admin.reports.supplier_account_report') }}">تقرير الحركات اليومية</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/reports.js') }}"></script>
@endsection
