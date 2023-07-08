
@extends('layout.admin')


@section('title')
تعديل فئة الاصناف
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
      <h3 class="card-title">تعديل فئة الاصناف</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" action="{{ route('inv_item_categories.update', $data->id) }}" method="post">
        @method('PUT')
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">اسم فئة الصنف</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}" placeholder="اسم فئة الصنف">

                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">مفعلة</label>
                <div class="col-sm-10">
                    <select name="active" class="form-control select2">
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
        <a href="{{ route('inv_item_categories.index') }}" class="btn btn-danger float-right">
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
    <a href="{{ route('inv_item_categories.index') }}">فئة الاصناف</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection
