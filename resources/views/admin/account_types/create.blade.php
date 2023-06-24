@extends('layout.admin')

@section('title')
    اضافة نوع حساب
@endsection

@section('contentheader')
    الحسابات المالية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.accounts.index') }}">انواع الحسابات المالية</a>
@endsection

@section('contentheaderactive')
    اضافة
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
            <h3 class="card-title"> اضافة نوع حساب مالي جديد</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form class="form-horizantal" action="{{ route('admin.account_types.store') }}" method="post" >
                <div class="row">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> اسم نوع الحساب</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">

                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>هل نوع الحساب داخلي</label>
                            <select name="related_internal_accounts" id="related_internal_accounts" class="form-control">
                                <option value="">اختر الحالة</option>
                                <option @if (old('related_internal_accounts') == 1) selected  @endif value="1">نعم</option>
                                <option @if (old('related_internal_accounts') == 0 and old('related_internal_accounts') != "") selected  @endif value="0">لا</option>
                            </select>
                            @error('related_internal_accounts')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>حالة التفعيل</label>
                            <select name="active" id="active" class="form-control">
                                <option value="">اختر الحالة</option>
                                <option @if(old('active') == 1 ) selected  @endif value="1">نعم</option>
                                <option @if(old('active') == 0  and old('active') != "") selected @endif value="0">لا</option>
                            </select>

                            @error('active')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-sm">اضافة</button>
                            <a href="{{ route('admin.account_types.index') }}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
