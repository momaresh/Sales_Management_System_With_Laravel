@extends('layout.admin')

<style>
    th {
        width: 30%;
    }
</style>


@section('title')
    تعديل بيانات الضبط العام
@endsection

@section('content')

<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title"> تعديل بيانات الضبط العام</h3>
    </div>
   <!-- /.card-header -->
    <div class="card-body">
        <form action="{{ route('admin.panelSetting.update', $data->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>اسم الشركة</label> <span id="barcodeCheckMessage"> </span>
                        <input type="text" name="system_name" value="{{ old('system_name', $data->system_name) }}" class="form-control">
                        @error('system_name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>حالة الشركة</label>
                        <select name="active" class="form-control">
                            <option @if (old('ctive', $data->active) == 0) selected @endif  value="0">No</option>
                            <option @if (old('ctive', $data->active) == 1) selected @endif  value="1">Yes</option>
                        </select>
                        @error('active')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>عنوان الشركة</label>
                        <input type="text" name="address" value="{{ old('address', $data->address) }}" class="form-control">
                        @error('address')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>هاتف الشركة</label>
                        <input type="text" name="phone" value="{{ old('phone', $data->phone) }}" class="form-control">
                        @error('phone')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>الحساب الاب للعملاء</label>
                        <select name="customer_parent_account" id="customer_parent_account" class="form-control select2">
                            <option value="">اختر الحساب الاب</option>
                            @if (@isset($accounts) && !@empty($accounts))
                                @foreach ($accounts as $info )
                                    <option @if (old('customer_parent_account', $data->customer_parent_account) == $info->account_number) selected @endif value="{{ $info->account_number }}"> {{ $info->notes }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('customer_parent_account')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>الحساب الاب للموردين</label>
                        <select name="supplier_parent_account" id="supplier_parent_account" class="form-control select2">
                            <option value="">اختر الحساب الاب</option>
                            @if (@isset($accounts) && !@empty($accounts))
                                @foreach ($accounts as $info )
                                    <option @if (old('supplier_parent_account', $data->supplier_parent_account) == $info->account_number) selected @endif value="{{ $info->account_number }}"> {{ $info->notes }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('supplier_parent_account')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>الحساب الاب للمناديب</label>
                        <select name="delegate_parent_account" id="delegate_parent_account" class="form-control select2">
                            <option value="">اختر الحساب الاب</option>
                            @if (@isset($accounts) && !@empty($accounts))
                                @foreach ($accounts as $info )
                                    <option @if (old('delegate_parent_account', $data->delegate_parent_account) == $info->account_number) selected @endif value="{{ $info->account_number }}"> {{ $info->notes }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('delegate_parent_account')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>الحساب الاب للموظفين</label>
                        <select name="employee_parent_account" id="employee_parent_account" class="form-control select2">
                            <option value="">اختر الحساب الاب</option>
                            @if (@isset($accounts) && !@empty($accounts))
                                @foreach ($accounts as $info )
                                    <option @if (old('employee_parent_account', $data->employee_parent_account) == $info->account_number) selected @endif value="{{ $info->account_number }}"> {{ $info->notes }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('employee_parent_account')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>الحساب الاب للخزن</label>
                        <select name="treasury_parent_account" id="treasury_parent_account" class="form-control select2">
                            <option value="">اختر الحساب الاب</option>
                            @if (@isset($accounts) && !@empty($accounts))
                                @foreach ($accounts as $info )
                                    <option @if (old('treasury_parent_account', $data->treasury_parent_account) == $info->account_number) selected @endif value="{{ $info->account_number }}"> {{ $info->notes }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('treasury_parent_account')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>صيغة كود العملاء</label>
                        <input type="text" name="customer_first_code" value="{{ old('customer_first_code', $data->customer_first_code) }}" class="form-control">
                        @error('customer_first_code')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>صيغة كود الموردين</label>
                        <input type="text" name="supplier_first_code" value="{{ old('supplier_first_code', $data->supplier_first_code) }}" class="form-control">
                        @error('supplier_first_code')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>صيغة كود المناديب</label>
                        <input type="text" name="delegate_first_code" value="{{ old('delegate_first_code', $data->delegate_first_code) }}" class="form-control">
                        @error('delegate_first_code')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>صيغة كود الموظفين</label>
                        <input type="text" name="employee_first_code" value="{{ old('employee_first_code', $data->employee_first_code) }}" class="form-control">
                        @error('employee_first_code')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>لوجو الشركة</label>
                        <img src="{{ asset("assets\admin\uploads\images\\$data->photo") }}" alt="Company logo" style="width:100px; height:100px;">
                        <input type="file" name="photo" class="form-control">
                        @error('photo')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-sm">حفظ</button>
                        <a href="{{ route('admin.panelSetting.index') }}" class="btn btn-sm btn-danger">الغاء</a>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection

@section('contentheader')
    الضبط العام
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.panelSetting.index') }}">الضبط</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection
