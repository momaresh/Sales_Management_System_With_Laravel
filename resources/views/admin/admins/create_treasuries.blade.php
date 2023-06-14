
@extends('layout.admin')


@section('title')
    المستخدمين
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
      <h3 class="card-title">اضافة خزينة</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" action="{{ route('admin.admins.store_treasuries', $admin_id) }}" method="post">
        @csrf
        <div class="card-body">

            <div class="form-group row">
                <label class="col-sm-2 control-label">اسم الخزنة</label>
                <div class="col-sm-10">
                    <select name="treasuries_id" class="form-control">
                        @if (!@empty($data))
                            <option value="">اختر خزينة...</option>
                            @foreach ($data as $datum)
                                <option @if (old('treasuries_id') == $datum['id']) selected @endif value="{{ $datum->id }}">{{ $datum->name }}</option>
                            @endforeach
                        @endif
                    </select>

                    @error('treasuries_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">مفعلة</label>
                <div class="col-sm-10">
                    <select name="active" class="form-control">
                        <option value="">اختر الحالة...</option>
                        <option @if (old('active') == 1) selected @endif value="1">نعم</option>
                        <option @if (old('active') == 0 && old('active') != '') selected @endif value="0">لا</option>
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
        <a href="{{ route('admin.admins.details', $admin_id) }}" class="btn btn-info">الغاء</a>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>

@endsection

@section('contentheader')
    الصلاحيات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.admins.index') }}">المستخدمين</a>
@endsection

@section('contentheaderactive')
    اضافة خزينة
@endsection
