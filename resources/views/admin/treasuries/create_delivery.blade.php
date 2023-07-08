
@extends('layout.admin')


@section('title')
    اضافة خزنة مستلم منها
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
    <form class="form-horizontal" action="{{ route('admin.treasuries_delivery.store', $master_id) }}" method="post">
        @csrf
        <div class="card-body">

            <div class="form-group row">
                <label class="col-sm-2 control-label">اسم الخزنة</label>
                <div class="col-sm-10">
                    <select name="receive_from_id" class="form-control select2">
                        @if (!@empty($data))
                            <option value="">اختر خزينة...</option>
                            @foreach ($data as $datum)
                                <option @if (old('receive_from_id') == $datum['id']) selected @endif value="{{ $datum->id }}">{{ $datum->name }}</option>
                            @endforeach
                        @endif
                    </select>

                    @error('receive_from_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>


        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button type="submit" class="btn btn-info">اضافة</button>
        <a href="{{ route('admin.treasuries.details', $master_id) }}" class="btn btn-default float-right">الغاء</a>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>

@endsection

@section('contentheader')
    الحسابات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.treasuries.index') }}">الخزينة</a>
@endsection

@section('contentheaderactive')
    اضافة
@endsection
