
@extends('layout.admin')


@section('title')
جرد المخازن
@endsection

@section('content')

<div class="card">
    <div class="card-header">
      <h3 class="card-title">اضافة جرد</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" action="{{ route('admin.inv_stores_inventory.store') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">تاريخ الجرد</label>
                <div class="col-sm-10">
                    <input type="date" readonly name="inventory_date" class="form-control" value="{{ old('inventory_date', date('Y-m-d')) }}">

                    @error('inventory_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">نوع الجرد</label>
                <div class="col-sm-10">
                    <select name="inventory_type" class="form-control select2">
                        <option value="">اختر النوع</option>
                        <option @if (old('inventory_type') == 1) selected @endif value="1">يومي</option>
                        <option @if (old('inventory_type') == 2) selected @endif value="2">اسبوعي</option>
                        <option @if (old('inventory_type') == 3) selected @endif value="3">شهري</option>
                        <option @if (old('inventory_type') == 4) selected @endif value="4">سنوي</option>
                    </select>

                    @error('inventory_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">المخزن</label>
                <div class="col-sm-10">
                    <select name="store_id" class="form-control select2">
                        <option value="">اختر المخزن</option>
                        @foreach ($stores as $store)
                            <option @if (old('store_id') == $store->id) selected @endif value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>

                    @error('store_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">ملاحضة</label>
                <div class="col-sm-10">
                    <textarea name="notes" class="form-control" id="" cols="30" rows="4">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button type="submit" class="btn btn-info">اضافة</button>
        <a href="{{ route('admin.inv_stores_inventory.index') }}" class="btn btn-default float-right">الغاء</a>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>

@endsection

@section('contentheader')
    الحركات المخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.inv_stores_inventory.index') }}">جرد المخازن</a>
@endsection

@section('contentheaderactive')
    اضافة
@endsection
