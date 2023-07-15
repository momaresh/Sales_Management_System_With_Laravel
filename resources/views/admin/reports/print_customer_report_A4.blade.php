<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> طباعة كشف حساب عميل </title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
        <style>
            td{font-size: 15px !important;}
            @media print {
                .mytable,
                .mytable th,
                .mytable td {
                    border: 1px solid black
                }
            }
        </style>
   </head>
    <body style="padding-top: 10px;font-family: tahoma;">
        <table class="mb-3" cellspacing="0" style="width: 30%; margin-right: 5px; float: right;  border: 1px dashed black "  dir="rtl">
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;"> كود العميل
                    @if (!@empty($data['customer_code']))
                        <span style="margin-right: 10px;">/ {{ $data["customer_code"] }}</span>
                    @else
                    /لايوجد
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">اسم العميل<span style="margin-right: 10px;">/ {{ $data->first_name }} {{ $data->last_name }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">رقم التيلفون<span style="margin-right: 10px;">/ {{ $data['phone'];}}</span></td>
            </tr>
        </table>

        <br>

        <table style="width: 30%;float: right;  margin-right: 5px;" dir="rtl">
            <tr>
                <td style="text-align: center;padding: 5px;">  <span style=" display: inline-block;
                    width: 250px;
                    height: 30px;
                    text-align: center;
                    background: yellow !important;
                    border: 1px solid black; border-radius: 15px;font-weight: bold;">كشف حساب عميل</span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding: 5px;font-weight: bold;">
                <span style=" display: inline-block;
                    width: 200px;
                    text-align: center;
                    color: red;
                    border: 1px solid black; ">
                    @if ($data['report_type'] == 1)
                        كشف حساب اجمالي
                    @elseif ($data['report_type'] == 2)
                        كشف حساب تفصيلي من ({{ $data['from_date'] }}) الى ({{ $data['to_date'] }})
                    @endif
                </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding: 5px;"><span style=" display: inline-block;
                    width: 200px;
                    height: 30px;
                    text-align: center;
                    background: rgb(255, 255, 255) !important;">بواسطة {{ auth()->user()->name }}</span>
                </td>
            </tr>
        </table>

        <table style="width: 35%;float: right; margin-left: 5px; " dir="rtl">
            <tr>
                <td style="text-align:left !important;padding: 5px;">
                    <img style="width: 150px; height: 110px; border-radius: 10px;" src="{{ asset('assets/admin/uploads/images').'/'.$systemData['photo'] }}">
                    <p>{{ $systemData['system_name'] }}</p>
                </td>
            </tr>
        </table>

        <table dir="rtl" border="1" style="width: 98%; margin: 0 auto;">
            <tr>
                <th style="width: 30%; text-align:center">رقم الحساب المالي للعميل</th>
                <td style="padding-right: 10px">{{ $data['account_number'] }}</td>
            </tr>
            <tr>
                <th style="width: 30%; text-align:center">رصيد أول المدة</th>
                <td style="padding-right: 10px">
                    @if ($data['start_balance'] < 0)
                        دائن ({{ $data['start_balance'] * (-1) }})
                    @elseif ($data['start_balance'] > 0)
                        مدين ({{ $data['start_balance'] * (1) }})
                    @else
                        متزن
                    @endif
                </td>
            </tr>
            <tr>
                <th style="width: 30%; text-align:center">الرصيد الحالي</th>
                <td style="padding-right: 10px">
                    @if ($data['current_balance'] < 0)
                    دائن ({{ $data['current_balance'] * (-1) }})
                    @elseif ($data['current_balance'] > 0)
                        مدين ({{ $data['current_balance'] * (1) }})
                    @else
                        متزن
                    @endif
                </td>
            </tr>
            <tr>
                <th style="width: 30%; text-align:center">المبيعات</th>
                <td style="padding-right: 10px">عدد ({{ $data['all_purchase_count'] }}) فاتورة بقيمة ({{ $data['all_purchase_cost'] }})</td>
            </tr>
            <tr>
                <th style="width: 30%; text-align:center">مرتجع المبيعات العام</th>
                <td style="padding-right: 10px">عدد ({{ $data['all_general_return_purchase_count'] }}) فاتورة بقيمة ({{ $data['all_general_return_purchase_cost'] }})</td>
            </tr>
            <tr>
                <th style="width: 30%; text-align:center">مرتجع المبيعات بالاصل</th>
                <td style="padding-right: 10px">عدد ({{ $data['all_original_return_purchase_count'] }}) فاتورة بقيمة ({{ $data['all_original_return_purchase_cost'] }})</td>
            </tr>
            <tr>
                <th style="width: 30%; text-align:center">اجمالي صرف النقدية</th>
                <td style="padding-right: 10px">({{ $data['all_exchange'] }}) ريال</td>
            </tr>
            <tr>
                <th style="width: 30%; text-align:center">اجمالي تحصيل النقدية</th>
                <td style="padding-right: 10px">({{ $data['all_collection'] }}) ريال</td>
            </tr>
            <tr>
                <th style="width: 30%; text-align:center">اجمالي الآجل للحساب</th>
                <td style="padding-right: 10px">({{ $data['all_unpaid'] }}) ريال</td>
            </tr>
        </table>


        @if ($data['report_type'] == 2)
            <div class="row my-2 mx-1 justify-content-center m-3" dir="rtl" border="1">
                <div class="alert alert-danger">
                    <h4>فواتير المبيعات</h4>
                </div>
                @if ($data['report_type'] == 2 && !@empty($sales_pill[0]))
                    @foreach ($sales_pill as $sales)
                        <table class="table table-striped table-borderless mytable mb-0">
                            <thead style="background-color:#84B0CA ;" class="text-white">
                                <tr>
                                <th scope="col">رقم الفاتورة</th>
                                <th scope="col">تاريخ الفاتورة</th>
                                <th scope="col">نوع الفاتورة</th>
                                <th scope="col">الاجمالي</th>
                                <th scope="col">المدفوع</th>
                                <th scope="col">المتبقي</th>
                                <th scope="col">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $sales['pill_code'] }}</td>
                                    <td>{{ $sales['order_date'] }}</td>
                                    <td>
                                        @if ($sales['pill_type'] == 1)
                                            كاش
                                        @else
                                            آجل
                                        @endif
                                    </td>
                                    <td>{{ $sales['total_cost'] }}</td>
                                    <td>{{ $sales['what_paid'] }}</td>
                                    <td>{{ $sales['what_remain'] }}</td>
                                    <td>
                                        @if ($sales['is_approved'] == 1)
                                            معتمدة
                                        @else
                                            غير معتمدة
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <table id="example2" class="table table-bordered table-hover">
                            <tr style="background-color: #535f6c; color:white;">
                                <th>#</th>
                                <th>اسم الصنف</th>
                                <th>الوحدة</th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>الاجمالي</th>
                                <th>المخزن</th>
                            </tr>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($sales['details'] as $detail)
                                <tr>
                                    <td>
                                        @php
                                            echo $i;
                                        @endphp
                                    </td>
                                    <td>{{ $detail['item_name'] }}</td>
                                    <td>{{ $detail['unit_name'] }}</td>
                                    <td>{{ $detail['quantity'] }}</td>
                                    <td>{{ $detail['unit_price'] }}</td>
                                    <td>{{ $detail['total_price'] }}</td>
                                    <td>{{ $detail['store_name'] }}</td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </table>
                    @endforeach
                @else
                    <div class="alert alert-info col-12 text-center">
                        لا يوجد بيانات
                    </div>
                @endif
            </div>

            <div class="row my-2 mx-1 justify-content-center m-3" dir="rtl" border="1">
                <div class="alert alert-danger">
                    <h4>فواتير مرتجع المبيعات العام</h4>
                </div>
                @if ($data['report_type'] == 2 && !@empty($sales_general_return_pill[0]))
                    @foreach ($sales_general_return_pill as $sales)
                        <table class="table table-striped table-borderless mytable mb-0">
                            <thead style="background-color:#84B0CA ;" class="text-white">
                                <tr>
                                <th scope="col">رقم الفاتورة</th>
                                <th scope="col">تاريخ الفاتورة</th>
                                <th scope="col">نوع الفاتورة</th>
                                <th scope="col">الاجمالي</th>
                                <th scope="col">المدفوع</th>
                                <th scope="col">المتبقي</th>
                                <th scope="col">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $sales['pill_code'] }}</td>
                                    <td>{{ $sales['order_date'] }}</td>
                                    <td>
                                        @if ($sales['pill_type'] == 1)
                                            كاش
                                        @else
                                            آجل
                                        @endif
                                    </td>
                                    <td>{{ $sales['total_cost'] }}</td>
                                    <td>{{ $sales['what_paid'] }}</td>
                                    <td>{{ $sales['what_remain'] }}</td>
                                    <td>
                                        @if ($sales['is_approved'] == 1)
                                            معتمدة
                                        @else
                                            غير معتمدة
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <table id="example2" class="table table-bordered table-hover">
                            <tr style="background-color: #535f6c; color:white;">
                                <th>#</th>
                                <th>اسم الصنف</th>
                                <th>الوحدة</th>
                                <th>الكمية المرتجعة</th>
                                <th>سعر الوحدة</th>
                                <th>الاجمالي</th>
                                <th>المخزن</th>
                            </tr>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($sales['details'] as $detail)
                                <tr>
                                    <td>
                                        @php
                                            echo $i;
                                        @endphp
                                    </td>
                                    <td>{{ $detail['item_name'] }}</td>
                                    <td>{{ $detail['unit_name'] }}</td>
                                    <td>{{ $detail['quantity'] }}</td>
                                    <td>{{ $detail['unit_price'] }}</td>
                                    <td>{{ $detail['total_price'] }}</td>
                                    <td>{{ $detail['store_name'] }}</td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </table>
                    @endforeach
                @else
                    <div class="alert alert-info col-12 text-center">
                        لا يوجد بيانات
                    </div>
                @endif
            </div>

            <div class="row my-2 mx-1 justify-content-center m-3" dir="rtl" border="1">
                <div class="alert alert-danger">
                    <h4>فواتير مرتجع المبيعات بالاصل</h4>
                </div>
                @if ($data['report_type'] == 2 && !@empty($sales_original_return_pill[0]))
                    @foreach ($sales_original_return_pill as $sales)
                        <table class="table table-striped table-borderless mytable mb-0">
                            <thead style="background-color:#84B0CA ;" class="text-white">
                                <tr>
                                <th scope="col">رقم الفاتورة الاصل</th>
                                <th scope="col">تاريخ الفاتورة</th>
                                <th scope="col">نوع الفاتورة</th>
                                <th scope="col">الاجمالي</th>
                                <th scope="col">المدفوع</th>
                                <th scope="col">المتبقي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $sales['parent_pill_code'] }}</td>
                                    <td>{{ $sales['return_date'] }}</td>
                                    <td>
                                        @if ($sales['pill_type'] == 1)
                                            كاش
                                        @else
                                            آجل
                                        @endif
                                    </td>
                                    <td>{{ $sales['total_cost'] }}</td>
                                    <td>{{ $sales['what_paid'] }}</td>
                                    <td>{{ $sales['what_remain'] }}</td>
                                </tr>
                            </tbody>
                        </table>


                        <table id="example2" class="table table-bordered table-hover">
                            <tr style="background-color: #535f6c; color:white;">
                                <th>#</th>
                                <th>اسم الصنف</th>
                                <th>الوحدة</th>
                                <th>الكمية المرتجعة</th>
                                <th>سعر الوحدة</th>
                                <th>الاجمالي</th>
                                <th>المخزن</th>
                            </tr>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($sales['details'] as $detail)
                                <tr>
                                    <td>
                                        @php
                                            echo $i;
                                        @endphp
                                    </td>
                                    <td>{{ $detail['item_name'] }}</td>
                                    <td>{{ $detail['unit_name'] }}</td>
                                    <td>{{ $detail['quantity'] }}</td>
                                    <td>{{ $detail['unit_price'] }}</td>
                                    <td>{{ $detail['total_price'] }}</td>
                                    <td>{{ $detail['store_name'] }}</td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </table>
                    @endforeach
                @else
                    <div class="alert alert-info col-12 text-center">
                        لا يوجد بيانات
                    </div>
                @endif
            </div>

            <div class="row my-2 mx-1 justify-content-center m-3" dir="rtl" border="1">
                <div class="alert alert-danger">
                    <h4>الحركات على الخزن</h4>
                </div>
                @if ($data['report_type'] == 2 && !@empty($transactions[0]))
                    <table class="table table-striped table-borderless mytable">
                        <thead style="background-color:#84B0CA ;" class="text-white">
                            <tr>
                                <th scope="col">رقم الايصال</th>
                                <th scope="col">تاريخ الحركة</th>
                                <th scope="col">نوع الحركة</th>
                                <th scope="col">المبلغ</th>
                                <th scope="col">البيان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $trans)
                                <tr>
                                    <td>{{ $trans['last_arrive'] }}</td>
                                    <td>{{ $trans['date'] }}</td>
                                    <td>{{ $trans['type_name'] }}</td>
                                    <td>{{ $trans['money'] }}</td>
                                    <td>{{ $trans['byan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info col-12 text-center">
                        لا يوجد بيانات
                    </div>
                @endif
            </div>
        @endif

        <br>

        <p style="position: fixed;
            padding: 10px 10px 0px 10px;
            bottom: 0;
            width: 100%;
            /* Height of the footer*/
            text-align: center;font-size: 16px; font-weight: bold;
            "> {{ $systemData['address'] }} - {{ $systemData['phone'] }}
        </p>

    </body>
</html>
