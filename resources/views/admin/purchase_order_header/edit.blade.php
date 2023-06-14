
@extends('layout.admin')


@section('title')
    المشتريات
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
      <h3 class="card-title">تعديل فاتورة</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <form class="form-horizontal" action="{{ route('admin.purchase_header.update', $data->id) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="inputEmail3">رقم الفاتورة المسجل بالفاتورة الاصل</label>
                    <div class="form-group">
                        <input type="number" name="pill_number" class="form-control" value="{{ old('pill_number', $data->pill_number) }}" placeholder="رقم الفاتورة المسجل بالفاتورة الاصل">

                        @error('pill_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>اسم المورد</label>
                        <select name="supplier_code" id="supplier_code" class="form-control select2">
                            <option value="">اختر المورد</option>
                            @if (@isset($suppliers_code) && !@empty($suppliers_code))
                            @foreach ($suppliers_code as $info )
                                <option @if(old('supplier_code', $data->supplier_code) == $info->supplier_code) selected @endif value="{{ $info->supplier_code }}"> {{ $info->first_name }}  {{ $info->last_name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('supplier_code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>اسم المخزن</label>
                        <select name="store_id" id="store_id" class="form-control select2">
                            <option value="">اختر المخزن</option>
                            @if (@isset($stores) && !@empty($stores))
                            @foreach ($stores as $info )
                                <option @if(old('store_id', $data->store_id) == $info->id) selected @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('store_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>نوع الفاتورة</label>
                        <select name="pill_type" id="pill_type" class="form-control">
                            <option value="">اختر النوع</option>
                            <option   @if (old('pill_type', $data->pill_type) == 1) selected  @endif value="1">نقدا</option>
                            <option   @if (old('pill_type', $data->pill_type) == 2) selected  @endif value="2">آجل</option>
                        </select>

                        @error('pill_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="inputEmail3">تاريخ الفاتورة</label>
                    <div class="form-group">
                        <input type="date" name="order_date" class="form-control" value="{{ $data->order_date }}" placeholder="تاريخ الفاتورة">

                        @error('order_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label>ملاحضات</label>
                    <div class="form-group">
                        <textarea name="notes" class="form-control">{{ old('notes', $data->notes) }}</textarea>
                        @error('notes')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info">تعديل</button>
                <a href="{{ route('admin.purchase_header.index') }}" class="btn btn-default float-right">الغاء</a>
            </div>
            <!-- /.card-footer -->
         </form>
    </div>
</div>

@endsection

@section('contentheader')
الحركات المخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.purchase_header.index') }}">المشتريات</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection

@section('script')

@endsection
