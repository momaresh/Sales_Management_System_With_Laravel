
@extends('layout.admin')


@section('title')
    الموردين
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
      <h3 class="card-title">تعديل المورد</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <form class="form-horizontal" action="{{ route('admin.suppliers.update', $data->id) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="inputEmail3">اسم المورد الاول</label>
                    <div class="form-group">
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $data->first_name) }}" placeholder="اسم المورد الاول">

                        @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="inputEmail3">اسم المورد الاخير</label>
                    <div class="form-group">
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $data->last_name) }}" placeholder="اسم المورد الاخير">

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
                    <div class="form-group">
                        <label>حالة رصيد اول المدة</label>
                        <select name="start_balance_status" id="start_balance_status" class="form-control select2">
                            <option value="">اختر الحالة</option>
                            <option   @if (old('start_balance_status', $data['start_balance_status']) == 1) selected  @endif value="1">دائن</option>
                            <option   @if (old('start_balance_status', $data['start_balance_status']) == 2) selected  @endif value="2">مدين</option>
                            <option   @if (old('start_balance_status', $data['start_balance_status']) == 3) selected  @endif value="3">متزن</option>
                        </select>

                        @error('start_balance_status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>رصيد أول المدة للحساب</label>
                        <input name="start_balance" id="start_balance" class="form-control"  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{ old('start_balance', $data['start_balance']) }}">

                        @error('start_balance')
                            <span class="text-danger">{{ $message }}</span>
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

                <input name="account_number" type="hidden" value="{{ $data['account_number'] }}">

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
    <a href="{{ route('admin.suppliers.index') }}">الموردين</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/accounts.js') }}"></script>
@endsection
