
@extends('layout.admin')


@section('title')
    تعديل الوحدة
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
      <h3 class="card-title">تعديل الوحدات</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" action="{{ route('admin.inv_units.update', $data->id) }}" method="post">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">اسم الوحدة</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}" placeholder="اسم الوحدة">

                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            @if ($data['unit_used'] == false)
                <div class="form-group row">
                    <label class="col-sm-2 control-label">نوع الوحدة</label>
                    <div class="col-sm-10">
                        <select name="master" class="form-control select2">
                            <option value="">اختر النوع</option>
                            <option @if (old('master', $data->master) == 0) selected @endif value="0">وحدة جزئية</option>
                            <option @if (old('master', $data->master) == 1) selected @endif value="1">وحدة رئيسية</option>
                        </select>
                    </div>
                </div>
            @endif

            <div class="form-group row">
                <label class="col-sm-2 control-label">مفعلة</label>
                <div class="col-sm-10">
                    <select name="active" class="form-control select2">
                        <option value="">اختر الحالة</option>
                        <option @if (old('active', $data->active) == 0) selected @endif value="0">لا</option>
                        <option @if (old('active', $data->active) == 1) selected @endif value="1">نعم</option>
                    </select>

                    @error('active')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button type="submit" class="btn btn-info">حفظ التعديلات</button>
        <a href="{{ route('admin.inv_units.index') }}" class="btn btn-danger float-right">
            الغاء
        </a>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>

@endsection

@section('contentheader')
    المخازن
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.inv_units.index') }}">الوحدات</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection
