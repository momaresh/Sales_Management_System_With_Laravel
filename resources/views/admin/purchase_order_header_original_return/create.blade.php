
@extends('layout.admin')


@section('title')
    مرتجع المشتريات بالاصل
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

<div class="card">
    <div class="card-header">
      <h3 class="card-title">اضافة فاتورة جديدة</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label for="inputEmail3">كود الفاتورة</label>
                <div class="form-group">
                    <input type="text" name="pill_code" id="pill_code" class="form-control" value="" placeholder="كود الفاتورة">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>اسم المورد</label>
                    <select name="supplier_code_add" id="supplier_code_add" class="form-control select2">
                        <option value="">اختر العميل</option>
                        @if (@isset($suppliers) && !@empty($suppliers))
                            @foreach ($suppliers as $info )
                                <option value="{{ $info->supplier_code }}"> {{ $info->first_name }}  {{ $info->last_name }} </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-4" id="supplier_pills_div">

            </div>
        </div>

        <div class="row" id="pill_details_div">

        </div>

        <input type="hidden" id="ajax_token" value="{{ csrf_token() }}">
        <input type="hidden" id="ajax_get_supplier_pills_route" value="{{ route('admin.purchase_order_header_original_return.get_supplier_pills') }}">
        <input type="hidden" id="ajax_get_pill_details_route" value="{{ route('admin.purchase_order_header_original_return.get_pill_details') }}">

    </div>
</div>

@endsection

@section('contentheader')
الحركات المخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.purchase_order_header_original_return.index') }}">مرتجع المشتريات بالاصل</a>
@endsection

@section('contentheaderactive')
    اضافة
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/original_return.js') }}"></script>
@endsection
