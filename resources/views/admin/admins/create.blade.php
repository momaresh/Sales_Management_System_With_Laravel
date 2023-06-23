
@extends('layout.admin')


@section('title')
    اضافة مستخدم
@endsection

@section('content')

<div class="card">
    <div class="card-header">
      <h3 class="card-title">اضافة مستخدم</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" action="{{ route('admin.admins.store') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">الاسم كاملاً</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="الاسم كاملاً">
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">اسم المستخدم</label>
                <div class="col-sm-10">
                    <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}" placeholder="اسم المستخدم">
                    @error('user_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">الايميل</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="البريد الالكتروني">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">كلمة السر</label>
                <div class="col-sm-10">
                    <input type="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="كلمة السر">

                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">الصلاحية</label>
                <div class="col-sm-10">
                    <select name="roles_id" class="form-control select2">
                        <option value="">اختر الصلاحية</option>
                        @if (@isset($roles) && !@empty($roles))
                            @foreach ($roles as $role)
                                <option @if (old('roles_id') == $role->id) selected @endif value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        @endif
                    </select>

                    @error('roles_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">مفعل</label>
                <div class="col-sm-10">
                    <select name="active" class="form-control">
                        <option @if (old('active') == 0) selected @endif value="0">لا</option>
                        <option @if (old('active') == 1) selected @endif selected value="1">نعم</option>
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
        <a href="{{ route('admin.admins.index') }}" class="btn btn-default float-right">
            الغاء
        </a>
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
    اضافة
@endsection
