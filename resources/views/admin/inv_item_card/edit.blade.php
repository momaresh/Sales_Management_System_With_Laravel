@extends('layout.admin')

@section('title')
تعديل صنف
@endsection

@section('contentheader')
المخازن
@endsection

@section('contentheaderlink')
<a href="{{ route('admin.inv_item_card.index') }}">  الاصناف </a>
@endsection

@section('contentheaderactive')
تعديل
@endsection

@section('content')

<style>
    .parent_unit_name {
        color: red;
    }
    .child_unit_name {
        color: #0062cc;
    }
</style>

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
      <h3 class="card-title card_title">تعديل صنف</h3>
    </div>
   <!-- /.card-header -->
    <div class="card-body">
        <form  action="{{ route('admin.inv_item_card.update', $data->id) }}" method="post" enctype="multipart/form-data" >
        @csrf
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>اسم الصنف</label>  <span id="nameCheckMessage"> </span>
                        <input name="name" id="name" class="form-control" value="{{ old('name', $data->name) }}" placeholder="ادخل اسم الصنف"   >
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6" @if(!@empty($data['is_used'])) style="display: none" @endif>
                    <div class="form-group">
                        <label>نوع الصنف</label>
                        <select name="item_type" id="item_type" class="form-control select2">
                            <option value="">اختر النوع</option>
                            <option   @if(old('item_type', $data->item_type) == 1) selected  @endif value="1"> مخزني</option>
                            <option   @if(old('item_type', $data->item_type) == 2) selected  @endif value="2"> استهلاكي بتاريخ صلاحية</option>
                            <option   @if(old('item_type', $data->item_type) == 3) selected  @endif value="3"> عهدة</option>
                        </select>
                        @error('item_type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>فئة الصنف</label>
                        <select name="inv_itemcard_categories_id" id="inv_itemcard_categories_id" class="form-control select2">
                            <option value="">اختر الفئة</option>
                            @if (@isset($inv_itemCard_categories) && !@empty($inv_itemCard_categories))
                            @foreach ($inv_itemCard_categories as $info )
                                <option @if(old('inv_itemcard_categories_id', $data->inv_itemcard_categories_id) == $info->id) selected @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('inv_itemcard_categories_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>الصنف الاب له</label>
                        <select name="parent_inv_itemcard_id" id="parent_inv_itemcard_id" class="form-control select2">
                            <option selected value="0"> هو اب</option>

                            @if (@isset($item_card_data) && !@empty($item_card_data))

                                @foreach ($item_card_data as $info )
                                    <option @if(old('parent_inv_itemcard_id', $data->parent_inv_itemcard_id) == $info->id) selected @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach

                            @endif
                        </select>
                        @error('inv_itemcard_categories_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6" @if(!@empty($data['is_used'])) style="display: none" @endif>
                    <div class="form-group">
                        <label>وحدة القياس الاساسية</label>
                        <select name="unit_id" id="unit_id" class="form-control select2">
                            <option value="">اختر الوحدة الاب</option>

                            @if (@isset($inv_unit_parent) && !@empty($inv_unit_parent))

                                @foreach ($inv_unit_parent as $info )
                                    <option @if (old('unit_id', $data->unit_id) == $info->id) selected @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach

                            @endif

                        </select>
                        @error('unit_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6" @if(!@empty($data['is_used'])) style="display: none" @endif>
                    <div class="form-group" >
                        <label>هل للصنف وحدة تجزئة</label>
                        <select name="does_has_retailunit" id="does_has_retailunit" class="form-control select2">
                            <option value="">اختر الحالة</option>
                            <option   @if (old('does_has_retailunit', $data->does_has_retailunit) == 1) selected  @endif value="1"> نعم </option>
                            <option @if (old('does_has_retailunit', $data->does_has_retailunit) == 0 and old('does_has_retailunit', $data->does_has_retailunit) != "" ) selected   @endif value="0"> لا</option>
                        </select>
                        @error('does_has_retailunit')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 @if(@empty($data['is_used'])) relatied_retial_counter @endif" @if (old('does_has_retailunit') != 1 ) style="display: none;" @endif  id="retail_uom_idDiv" @if(!@empty($data['is_used'])) style="display: none" @endif>
                    <div class="form-group">
                        <label>وحدة القياس التجزئة بالنسبة للاساسية(<span class="parent_unit_name"></span>)</label>
                        <select name="retail_unit_id" id="retail_unit_id" class="form-control select2">
                            <option value="">اختر الوحدة التجزئة</option>

                            @if (@isset($inv_unit_child) && !@empty($inv_unit_child))

                                @foreach ($inv_unit_child as $info )
                                    <option @if(old('retail_unit_id', $data->retail_unit_id)==$info->id) selected @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach

                            @endif

                        </select>
                        @error('retail_unit_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 @if(@empty($data['is_used'])) relatied_retial_counter @endif "  @if(old('retail_unit_id')=="" ) style="display: none;" @endif @if(!@empty($data['is_used'])) style="display: none" @endif>
                    <div class="form-group">
                        <label>عدد وحدات التجزئة  (<span class="child_unit_name"></span>) بالنسبة للجملة (<span class="parent_unit_name"></span>)  </label>
                        <input oninput="this.value = this.value.replace(/[^0-9.]/g,'');" name="retail_uom_quntToParent" id="retail_uom_quntToParent" class="form-control"  value="{{ old('retail_uom_quntToParent', $data->retail_uom_quntToParent) }}" placeholder="ادخل  عدد وحدات التجزئة"  >

                        @error('retail_uom_quntToParent')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>هل للصنف سعر ثابت</label>
                        <select name="has_fixed_price" id="has_fixed_price" class="form-control select2">
                        <option value="">اختر الحالة</option>
                        <option @if (old('has_fixed_price', $data->has_fixed_price) == 1) selected  @endif value="1"> نعم ثابت ولايتغير بالفواتير</option>
                        <option @if (old('has_fixed_price', $data->has_fixed_price) == 0 and old('has_fixed_price', $data->has_fixed_price) != "" ) selected   @endif value="0"> لا وقابل للتغير بالفواتير</option>
                        </select>
                        @error('has_fixed_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>  حالة التفعيل</label>
                        <select name="active" id="active" class="form-control select2">
                        <option value="">اختر الحالة</option>
                        <option   @if (old('active', $data->active) == 1) selected  @endif value="1"> نعم</option>
                        <option @if (old('active', $data->active) == 0 and old('active', $data->active) != "" ) selected   @endif value="0"> لا</option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6" style="margin:10px;">
                    <div class="form-group">
                        <label class="col-md-12">صورة الصنف ان وجدت</label>
                        <img id="uploadedimg" src="{{ asset('assets/admin/uploads/item_card_images/'.$data->item_img) }}" alt="uploaded img" style="width: 200px; width: 200px;" >
                        <input onchange="readURL(this)" type="file" id="Item_img" name="item_img" class="form-control col-md-8 mt-3 mb-3">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_add_item_card" type="submit" class="btn btn-primary float-left"> حفظ التعديلات</button>
                        <a href="{{ route('admin.inv_item_card.index') }}" class="btn btn-danger float-right">الغاء</a>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection



@section('script')

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#uploadedimg').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        var unit_id = $("#unit_id").val();
        if (unit_id != ""){
            var name = $("#unit_id option:selected").text();
            $(".parent_unit_name").text(name);
        }
        if (unit_id != 0 || unit_id != '') {
            $('.relatied_parent_counter').show();
        }
        else {
            $('.relatied_parent_counter').hide();
        }


        var does_has_retailunit = $('#does_has_retailunit').val();
        if (does_has_retailunit == 1) {
            $('.relatied_retial_counter').show();
        }
        else {
            $('.relatied_retial_counter').hide();
        }

        var retail_unit_id = $("#retail_unit_id").val();
        if (retail_unit_id != ""){
            var name = $("#retail_unit_id option:selected").text();
            $(".child_unit_name").text(name);
        }

    </script>
    <script src="{{ asset('assets/admin/js/inv_item_card.js') }}"></script>

@endsection
