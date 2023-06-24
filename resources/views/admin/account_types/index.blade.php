@extends('layout.admin')

@section('title')
    انواع الحسابات
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


<div class="row">
    @if (check_control_menu_role('الحسابات', 'انواع الحسابات' , 'اضافة') == true)
        <a href="{{ route('admin.account_types.create') }}" style="background-color: #007bff; font-size: 15px; margin: 10px auto; width: fit-content; display: block; color: white" class="btn">
            <i class="fas fa-plus-circle"></i> اضافة جديد
        </a>
    @endif
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات انواع الحسابات</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div id="ajax_search_result">
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($data[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>كود نوع الحساب</th>
                            <th>اسم نوع الحساب</th>
                            <th>هل الحساب داخلي</th>
                            <th>حالة التفعيل</th>
                            <th>تاريخ الاضافة</th>
                        </tr>

                        @foreach ($data as $datum)
                            <tr>
                                <td>{{ $datum->id }}</td>
                                <td>{{ $datum->name }}</td>
                                <td>
                                    @if ($datum->related_internal_accounts == 1)
                                        نعم
                                    @else
                                        لا
                                    @endif
                                </td>
                                @if ($datum->active == 1)
                                <td style="background-color: #5ab6a0a1;">
                                    مفعل
                                </td>
                                @elseif ($datum->active == 0)
                                <td style="background-color: #c15670a1;;">
                                    غير مفعل
                                </td>
                                @endif
                                <td>
                                    @if ($datum['added_by'] != null)
                                        @php
                                            $d = new DateTime($datum['created_at']);
                                            $date = $d->format('d/m/Y الساعة h:i:sA');
                                        @endphp

                                        {{ $date }}
                                        بواسطة
                                        {{ $datum['added_by_name'] }}
                                    @else
                                        لم يتم تسجيل بيانات المضاف
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    @else
                        <div class="alert alert-danger">
                            لا يوجد بيانات لعرضها
                        </div>
                    @endif

                </table>
            </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>

@endsection

@section('contentheader')
    الحسابات
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.account_types.index') }}">انواع الحسابات</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection
