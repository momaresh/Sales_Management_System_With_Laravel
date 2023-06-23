
@extends('layout.admin')


@section('title')
    تعديل القائمة الفرعية
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
      <h3 class="card-title">تعديل القائمة الفرعية</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" action="{{ route('admin.roles_sub_menu.update', $data->id) }}" method="post">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">اسم القائمة الفرعية</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}" placeholder="اسم القائمة">

                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <input type="hidden" name="id" value="{{  $data->id }}">


            <div class="form-group row">
                <label class="col-sm-2 control-label">اسم القائمة الرئيسية</label>
                <div class="col-sm-10">
                    <select name="main_menu_id" class="form-control select2">
                        <option value="">اختر القائمة</option>
                        @if (@isset($main_menus) && !@empty($main_menus))
                            @foreach ($main_menus as $menu)
                            <option @if (old('main_menu_id', $data['roles_main_menu_id']) == $menu->id) selected @endif value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        @endif
                    </select>

                    @error('main_menu_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">حالة التفعيل</label>
                <div class="col-sm-10">
                    <select name="active" class="form-control">
                        <option value="">اختر الحالة</option>
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
        <a href="{{ route('admin.roles_sub_menu.index') }}" class="btn btn-danger float-right">
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
    <a href="{{ route('admin.roles_sub_menu.index') }}">القوائم الفرعية للصلاحيات</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection
