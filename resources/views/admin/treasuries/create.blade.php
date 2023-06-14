
@extends('layout.admin')


@section('title')
    Treasuries
@endsection

@section('content')

<div class="card">
    <div class="card-header">
      <h3 class="card-title">اضافة خزينة</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" action="{{ route('admin.treasuries.store') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">اسم الخزينة</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="اسم الخزينة">

                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">رئيسية</label>
                <div class="col-sm-10">
                    <select name="master" class="form-control">
                        <option @if (old('master') == 0) selected @endif value="0">No</option>
                        <option @if (old('master') == 1) selected @endif value="1">Yes</option>
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
                        <option @if (old('active') == 0) selected @endif value="0">No</option>
                        <option @if (old('active') == 1) selected @endif value="1">Yes</option>
                    </select>

                    @error('active')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">اخر ايصال صرف</label>
                <div class="col-sm-10">
                    <input type="number" value="{{ old('last_exchange_arrive') }}" name="last_exchange_arrive" class="form-control" placeholder="اخر ايصال صرف">
                    @error('last_exchange_arrive')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">اخر ايصال تحصيل</label>
                <div class="col-sm-10">
                    <input type="number" value="{{ old('last_collection_arrive') }}" name="last_collection_arrive" class="form-control" placeholder="اخر ايصال تحصيل">

                    @error('last_collection_arrive')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>

        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button type="submit" class="btn btn-info">اضافة</button>
        <a href="{{ route('admin.treasuries.index') }}" class="btn btn-default float-right">الغاء</a>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>

@endsection

@section('contentheader')
    الضبط العام
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.treasuries.index') }}">الخزينة</a>
@endsection

@section('contentheaderactive')
    اضافة
@endsection
