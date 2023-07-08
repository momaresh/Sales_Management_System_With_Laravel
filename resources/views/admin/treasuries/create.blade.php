
@extends('layout.admin')

@section('title')
    الخزنات
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
                    <select name="master" class="form-control select2">
                        <option @if (old('master') == 0) selected @endif value="0">لا</option>
                        <option @if (old('master') == 1) selected @endif value="1">نعم</option>
                    </select>

                    @error('master')
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

            <div class="form-group row">
                <label class="col-sm-2 control-label">اخر ايصال آجل</label>
                <div class="col-sm-10">
                    <input type="number" value="{{ old('last_unpaid_arrive') }}" name="last_unpaid_arrive" class="form-control" placeholder="اخر ايصال آجل">

                    @error('last_unpaid_arrive')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">حالة رصيد اول المدة</label>
                <div class="col-sm-10">
                    <select name="start_balance_status" id="start_balance_status" class="form-control select2">
                        <option value="">اختر الحالة</option>
                        <option @if (old('start_balance_status') == 1) selected  @endif value="1">دائن</option>
                        <option @if (old('start_balance_status') == 2) selected  @endif value="2">مدين</option>
                        <option @if (old('start_balance_status') == 3) selected  @endif value="3">متزن</option>
                    </select>

                    @error('start_balance_status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">رصيد أول المدة للحساب</label>
                <div class="col-sm-10">
                    <input name="start_balance" id="start_balance" class="form-control"  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{ old('start_balance') }}">

                    @error('start_balance')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">ملاحظات</label>
                <div class="col-sm-10">
                    <input name="notes" id="notes" class="form-control" value="{{ old('notes') }}">

                    @error('notes')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">مفعلة</label>
                <div class="col-sm-10">
                    <select name="active" class="form-control select2">
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
        <a href="{{ route('admin.treasuries.index') }}" class="btn btn-default float-right">الغاء</a>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>

@endsection

@section('contentheader')
    الحسابات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.treasuries.index') }}">الخزن</a>
@endsection

@section('contentheaderactive')
    اضافة
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/accounts.js') }}"></script>
@endsection
