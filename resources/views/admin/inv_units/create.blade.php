
@extends('layout.admin')


@section('title')
    اضافة وحدة
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
      <h3 class="card-title">اضافة وحدة</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" action="{{ route('admin.inv_units.store') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">اسم الوحدة</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="اسم الوحدة">

                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">نوع الوحدة</label>
                <div class="col-sm-10">
                    <select name="master" class="form-control">
                        <option value="">اختر النوع</option>
                        <option @if (old('master') == 0 && old('master') != '') selected @endif value="0">تجزئة</option>
                        <option @if (old('master') == 1) selected @endif value="1">جملة</option>
                    </select>

                    @error('master')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">مفعلة</label>
                <div class="col-sm-10">
                    <select name="active" class="form-control">
                        <option value="">اختر الحالة</option>
                        <option @if (old('active') == 0 && old('active') != '') selected @endif value="0">No</option>
                        <option @if (old('active') == 1) selected @endif value="1">Yes</option>
                    </select>

                    @error('active')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


        </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-info">اضافة</button>
        <a href="{{ route('admin.inv_units.index') }}" class="btn btn-danger float-right">
            الغاء
        </a>
    </div>
    <!-- /.card-footer -->
    </form>
</div>

@endsection

@section('contentheader')
    الضبط العام
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.inv_units.index') }}">الوحدة</a>
@endsection

@section('contentheaderactive')
    اضافة
@endsection
