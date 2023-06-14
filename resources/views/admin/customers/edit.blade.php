
@extends('layout.admin')


@section('title')
    العملاء
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
      <h3 class="card-title">تعديل العميل</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <form class="form-horizontal" action="{{ route('admin.customers.update', $data->id) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="inputEmail3">اسم العميل الاول</label>
                    <div class="form-group">
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $data->first_name) }}" placeholder="اسم العميل الاول">

                        @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="inputEmail3">اسم العميل الاخير</label>
                    <div class="form-group">
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $data->last_name) }}" placeholder="اسم العميل الاخير">

                        @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="inputEmail3">العنوان</label>
                    <div class="form-group">
                        <input type="text" name="address" class="form-control" value="{{ old('address', $data->address) }}" placeholder="العنوان">

                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="inputEmail3">الهاتف</label>
                    <div class="form-group">
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $data->phone) }}" placeholder="الهاتف">

                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <label>مفعل</label>
                    <div class="form-group">
                        <select name="active" class="form-control">
                            <option @if (old('active', $data->active) == 0) selected @endif value="0">No</option>
                            <option @if (old('active', $data->active) == 1) selected @endif value="1">Yes</option>
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
          </div>
          <!-- /.card-footer -->
        </form>
    </div>
  </div>

@endsection

@section('contentheader')
    الحسابات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.customers.index') }}">العملاء</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection
