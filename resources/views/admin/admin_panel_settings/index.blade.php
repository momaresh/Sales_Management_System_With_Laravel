@extends('layout.admin')

<style>
    th {
        width: 30%;
    }
</style>


@section('title')
    Panel settings
@endsection

@section('content')

@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif


    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h3 class="card-title">بيانات الضبط العام</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">

                @if (!@empty($data))

                    <tr>
                        <th>اسم الشركة</th>
                        <td>{{ $data->system_name }}</td>
                    </tr>

                    <tr>
                        <th>كود الشركة</th>
                        <td>{{ $data->id }}</td>
                    </tr>

                    <tr>
                        <th>حالة الشركة</th>
                        <td>
                            @if ($data->active == 1)
                                مفعل
                            @else
                                غير مفعل
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>عنوان الشركة</th>
                        <td>{{ $data->address }}</td>
                    </tr>

                    <tr>
                        <th>هاتف الشركة</th>
                        <td>{{ $data->phone }}</td>
                    </tr>

                    <tr>
                        <th>الحساب الاب للعملاء</th>
                        <td>{{ $data->customer_parent_account_name }} ({{ $data->customer_parent_account }})</td>
                    </tr>

                    <tr>
                        <th>الحساب الاب للموردين</th>
                        <td>{{ $data->supplier_parent_account_name }} ({{ $data->supplier_parent_account }})</td>
                    </tr>

                    <tr>
                        <th>الحساب الاب للمناديب</th>
                        <td>{{ $data->delegate_parent_account_name }} ({{ $data->delegate_parent_account }})</td>
                    </tr>

                    <tr>
                        <th>الحساب الاب للموظفين</th>
                        <td>{{ $data->employee_parent_account_name }} ({{ $data->employee_parent_account }})</td>
                    </tr>

                    <tr>
                        <th>الحساب الاب للخزن</th>
                        <td>{{ $data->treasury_parent_account_name }} ({{ $data->treasury_parent_account }})</td>
                    </tr>

                    <tr>
                        <th>صيغة كود العملاء</th>
                        <td>{{ $data->customer_first_code }}</td>
                    </tr>

                    <tr>
                        <th>صيغة كود الموردين</th>
                        <td>{{ $data->supplier_first_code }}</td>
                    </tr>

                    <tr>
                        <th>صيغة كود للمناديب</th>
                        <td>{{ $data->delegate_first_code }}</td>
                    </tr>

                    <tr>
                        <th>صيغة كود الموظفين</th>
                        <td>{{ $data->employee_first_code }}</td>
                    </tr>

                    <tr>
                        <th>هاتف الشركة</th>
                        <td>{{ $data->phone }}</td>
                    </tr>

                    <tr>
                        <th>اخر تحديث</th>

                        <td>
                            @if ($data['updated_by'] != null)
                                @php
                                    $d = new DateTime($data['updated_at']);
                                    $date = $d->format('d/m/Y الساعة h:i:sA');
                                @endphp

                                {{ $date }}
                                بواسطة
                                {{ $data['updated_by_name'] }}
                            @else
                                لا يوجد اي تحديث
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>لوجو الشركة</th>
                        <td>
                            <img src="{{ asset("assets\admin\uploads\images\\$data->photo") }}" alt="Company logo" style="width:100px; height:100px;">
                        </td>
                    </tr>

                @else
                    <div class="text-danger">
                        لا يوجد بيانات لعرضها
                    </div>
                @endif

            </table>
            @if (check_control_menu_role('الضبط العام', 'الضبط العام' , 'تعديل') == true)
                <a href="{{ route('admin.panelSetting.edit', $data->id) }}" class="btn btn-primary mt-3">
                    تعديل
                </a>
            @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

@endsection

@section('contentheader')
    الضبط العام
@endsection

@section('contentheaderlink')
    <a href="#">الضبط</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection
