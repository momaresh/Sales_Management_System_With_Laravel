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
                        كشف حساب حركة النقدية من ({{ $data['from_date'] }}) الى ({{ $data['to_date'] }})
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
                <th style="width: 30%; text-align:center">اجمالي صرف النقدية</th>
                <td style="padding-right: 10px">({{ $data['all_exchange'] }}) ريال</td>
            </tr>
            <tr>
                <th style="width: 30%; text-align:center">اجمالي تحصيل النقدية</th>
                <td style="padding-right: 10px">({{ $data['all_collection'] }}) ريال</td>
            </tr>
        </table>


        <div class="row my-2 mx-1 justify-content-center m-3" dir="rtl" border="1">
            <div class="alert alert-danger">
                <h4>الحركات الصرفية للحساب</h4>
            </div>
            @if ($data['report_type'] == 5 && !@empty($exchange_transactions[0]))
                <table class="table table-striped table-borderless mytable">
                    <thead style="background-color:#84B0CA ;" class="text-white">
                        <tr>
                            <th scope="col">رقم الايصال</th>
                            <th scope="col">تاريخ الحركة</th>
                            <th scope="col">نوع الحركة</th>
                            <th scope="col">المبلغ المصروف</th>
                            <th scope="col">البيان</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exchange_transactions as $trans)
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


        <div class="row my-2 mx-1 justify-content-center m-3" dir="rtl" border="1">
            <div class="alert alert-danger">
                <h4>الحركات التحصيلية من الحساب</h4>
            </div>
            @if ($data['report_type'] == 5 && !@empty($collection_transactions[0]))
            <table class="table table-striped table-borderless mytable">
                <thead style="background-color:#84B0CA ;" class="text-white">
                    <tr>
                        <th scope="col">رقم الايصال</th>
                        <th scope="col">تاريخ الحركة</th>
                        <th scope="col">نوع الحركة</th>
                        <th scope="col">المبلغ المحصل</th>
                        <th scope="col">البيان</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collection_transactions as $trans)
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

        <div class="row my-2 mx-1 justify-content-center m-3" dir="rtl" border="1">
            <div class="alert alert-danger">
                <h4>الحركات الآجل</h4>
            </div>
            @if ($data['report_type'] == 5 && !@empty($unpaid_transactions[0]))
            <table class="table table-striped table-borderless mytable">
                <thead style="background-color:#84B0CA ;" class="text-white">
                    <tr>
                        <th scope="col">رقم الايصال</th>
                        <th scope="col">تاريخ الحركة</th>
                        <th scope="col">نوع الحركة</th>
                        <th scope="col">المبلغ للحساب او عليه</th>
                        <th scope="col">البيان</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unpaid_transactions as $trans)
                        <tr>
                            <td>{{ $trans['last_arrive'] }}</td>
                            <td>{{ $trans['date'] }}</td>
                            <td>{{ $trans['type_name'] }}</td>
                            <td>{{ $trans['money_for_account'] }}</td>
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
