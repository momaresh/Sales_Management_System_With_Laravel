@extends('layout.admin')

@section('title')
    تفاصيل مرتجع المشتريات بالاصل
@endsection

<style>
    th {
        width: 20%;
    }
</style>

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
    <div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الفاتورة</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">

            @if (!@empty($data))

                <tr>
                    <th>كود الفاتورة</th>
                    <td>{{ $data->pill_code }}</td>
                </tr>

                <tr>
                    <th>اسم العميل</th>
                    <td>{{ $data->customer_name }}</td>
                </tr>

                <tr>
                    <th>اسم المندوب</th>
                    <td>{{ $data->delegate_name }}</td>
                </tr>

                <tr>
                    <th>تاريخ الفاتورة</th>
                    <td>{{ $data->order_date }}</td>
                </tr>


                <tr>
                    <th>نوع الفاتورة</th>
                    <td>
                        @if ($data->pill_type == 1)
                            نقداً
                        @elseif ($data->pill_type == 2)
                            آجل
                        @else
                            غير محدد
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>كود الفاتورة بأصل فاتورة الاصل لدى المورد</th>
                    <td>{{ $data->pill_number }}</td>
                </tr>

                <tr>
                    <th>حالة الفاتورة</th>
                    @if ($data->is_approved == 0)
                    <td style="background-color: #5ab6a0a1;">
                        مفتوحة
                    </td>
                    @elseif ($data->is_approved == 1)
                    <td style="background-color: #c15670a1;;">
                        معتمدة
                    </td>
                    @endif
                </tr>

                <tr>
                    <th>الخصم على الفاتورة</th>
                    <td>
                        خصم بنسبة {{ $data['discount_percent'] }}%
                    </td>
                </tr>

                <tr>
                    <th>نسبة الضريبة</th>
                    <td>{{ $data['tax_percent'] }}%</td>
                </tr>

                <tr>
                    <th>اجمالي الفاتورة</th>
                    <td>{{ $data['total_cost'] }}</td>
                </tr>

                <tr>
                    <th>تم الارجاع</th>
                    <td>
                        @if ($data['updated_by'] != null)
                            @php
                                $d = new DateTime($data['created_at']);
                                $date = $d->format('d/m/Y الساعة h:i:sA');
                            @endphp

                            {{ $date }}
                            بواسطة
                            {{ $data['updated_by_name'] }}
                        @else
                            لا يوجد اي بيانات
                        @endif
                    </td>
                </tr>
            @else
                <div class="text-danger">
                    لا يوجد بيانات لعرضها
                </div>
            @endif

        </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الاصناف المرتجعة</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body ">
                <div>
                    <table id="example2" class="table table-bordered table-hover">

                        @if (!@empty($details[0]))

                            <tr style="background-color: #007bff; color:white;">
                                <th>اسم الصنف</th>
                                <th>المخزن</th>
                                <th>الكمية المرتجعة</th>
                                <th>الوحدة</th>
                                <th>سعر الوحدة</th>
                                <th>الاجمالي</th>
                            </tr>

                            @foreach ($details as $detail)
                                <tr>
                                    <td>{{ $detail['item_card_name'] }} <br/>
                                        <span style="color: green">{{ $detail['production_date'] }}</span> <br/>
                                        <span style="color: red">{{ $detail['expire_date'] }}</span>
                                    </td>
                                    <td>{{ $detail->store_name }}</td>
                                    <td>{{ $detail->rejected_quantity * 1 }}</td>
                                    <td>{{ $detail->unit_name }}</td>
                                    <td>{{ $detail->unit_price * 1 }}</td>
                                    <td>{{ $detail->total_price * 1 }}</td>
                                </tr>
                            @endforeach

                        @else
                            <div class="alert alert-danger">
                                لا يوجد بيانات لعرضها
                            </div>
                        @endif

                    </table>
                    <br>
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
    الحركات المخزنية
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.purchase_order_header_original_return.index') }}">مرتجع المشتريات</a>
@endsection

@section('contentheaderactive')
    عرض الاصناف
@endsection
