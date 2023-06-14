
@extends('layout.admin')


@section('title')
    المناديب
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
      <h3 class="card-title">تعديل المندوب</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <form class="form-horizontal" action="{{ route('admin.delegates.update', $data->id) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="inputEmail3">اسم المندوب الاول</label>
                    <div class="form-group">
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $data->first_name) }}" placeholder="اسم المندوب الاول">

                        @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="inputEmail3">اسم المندوب الاخير</label>
                    <div class="form-group">
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $data->last_name) }}" placeholder="اسم المندوب الاخير">

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
                        <label>نوع التحصيل على الفاتورة</label>
                        <select name="percent_type" id="percent_type" class="form-control">
                            <option value="1">قيمة</option>
                            <option value="2" selected>نسبة</option>
                        </select>
                        @error('percent_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>كم (<span class="percent_type"></span>) في حالة بيع الجملة</label>
                        <input name="group" id="group" class="form-control"  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{ old('group', $data['group']) }}">
                        @error('group')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>كم (<span class="percent_type"></span>) في حالة بيع نص جملة</label>
                        <input name="half_group" id="half_group" class="form-control"  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{ old('half_group', $data['half_group']) }}">
                        @error('half_group')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>كم (<span class="percent_type"></span>) في حالة بيع القطاعي</label>
                        <input name="one" id="one" class="form-control"  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{ old('one', $data['one']) }}">
                        @error('one')
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
    <a href="{{ route('admin.delegates.index') }}">المناديب</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection

@section('script')
    <script>
        $(function() {
            if($("#percent_type").val() == '1') {
            $(".percent_type").text('القيمة');
            }
            else {
                $(".percent_type").text('النسبة');
            }

            $('#percent_type').change(function() {
                if($(this).val() == '1') {
                    $(".percent_type").text('القيمة');
                }
                else {
                    $(".percent_type").text('النسبة');
                }
            })
        })
    </script>
@endsection
