@extends('layout.admin')

@section('title')
    شفتات الخزن
@endsection

@section('contentheader')
    حركات شفتات الخزن
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.admin_shifts.index') }}">شفتات الخزن</a>
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
            <h3 class="card-title card_title_center"> استلام شفت جديد من خزنة</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form class="form-horizantal" action="{{ route('admin.admin_shifts.store') }}" method="post" >
                <div class="row">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>الخزن التي تمتلك الصلاحيات عليها ومتاحه حالياً</label>
                            <select name="treasuries_id" id="treasuries_id" class="form-control select2">
                                <option value=''>اختر الخزنة</option>
                                @if (@isset($treasuries) && !@empty($treasuries))
                                    @foreach ($treasuries as $info )
                                        <option @if (old('treasuries_id') == $info->treasuries_id) selected @endif value="{{ $info->treasuries_id }}"> {{ $info->treasuries_name }} </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('treasuries_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-sm">استلام شفت</button>
                            <a href="{{ route('admin.admin_shifts.index') }}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
