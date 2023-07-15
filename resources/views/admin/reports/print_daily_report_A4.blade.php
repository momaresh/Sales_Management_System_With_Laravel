<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> طباعة كشف تقرير يومي </title>
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
        <div class="row mb-5" >
            <div class="col-6">
                <table style="float: left; margin-left: 50px">
                    <tr>
                        <td style="text-align:left !important;padding: 5px;">
                            <img style="width: 150px; height: 110px; border-radius: 10px;" src="{{ asset('assets/admin/uploads/images').'/'.$systemData['photo'] }}">
                            <p>{{ $systemData['system_name'] }}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-6">
                <table style="float: right; margin-right: 50px">
                    <tr>
                        <td style="text-align: center;padding: 5px;">  <span style=" display: inline-block;
                            width: 250px;
                            height: 30px;
                            text-align: center;
                            background: yellow !important;
                            border: 1px solid black; border-radius: 15px;font-weight: bold;">كشف تقرير يومي</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;padding: 5px;font-weight: bold;">
                        <span style=" display: inline-block;
                            width: 300px;
                            text-align: center;
                            color: red;
                            border: 1px solid black; ">

                            @if ($report_type == 1)
                            كشف تقرير اجمالي من ({{ $from_date }}) الى ({{ $to_date }})
                            @else
                            كشف تقرير تفصيلي من ({{ $from_date }}) الى ({{ $to_date }})
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
            </div>
        </div>

        <div class="row" dir="rtl" style="margin: auto; width: 98%">
            <div class="col-md-4">
                <h4 class="text-center mb-1 alert alert-info" style="padding: 0.5rem 1.25rem">التحصيلات</h4>
                <table dir="rtl" id="example2" class="table table-bordered table-hover">
                    <tr style="background-color: #007bff; color:white;">
                        <th>نوع الحركة</th>
                        <th>الاجمالي</td>
                    </tr>

                    @foreach ($all_collection_movements as $move)
                        <tr>
                            <td>{{ $move['name'] }}</td>
                            <td>
                                <p class="text-center mb-2" style="color:#d61e0a">{{ $move['total_money'] * (1) }}</p>

                                @if ($report_type == 2)
                                    <table dir="rtl" id="example2" class="table table-bordered table-hover">
                                        <tr style="background-color: #81983f; color:white;">
                                            <th>اسم الحساب</th>
                                            <th>المبلغ</td>
                                        </tr>

                                        @foreach ($move['accounts'] as $acc)
                                            <tr>
                                                <td>{{ $acc->account_name }}</td>
                                                <td>{{ $acc->account_money * (1) }}</td>
                                            </tr>
                                        @endforeach

                                        @if ($move['total_money_with_no_account'] != 0)
                                            <tr>
                                                <td>بدون حساب</td>
                                                <td>{{ $move['total_money_with_no_account'] }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="col-md-4">
                <h4 class="text-center mb-1 alert alert-info" style="padding: 0.5rem 1.25rem">المصروفات</h4>

                <table dir="rtl" id="example2" class="table table-bordered table-hover">
                    <tr style="background-color: #007bff; color:white;">
                        <th>نوع الحركة</th>
                        <th>الاجمالي</td>
                    </tr>

                    @foreach ($all_exchange_movements as $move)
                        <tr>
                            <td>{{ $move['name'] }}</td>
                            <td>
                                <p class="text-center mb-2" style="color:#d61e0a">{{ $move['total_money'] * (-1) }}</p>

                                @if ($report_type == 2)
                                    <table dir="rtl" id="example2" class="table table-bordered table-hover">
                                        <tr style="background-color: #81983f; color:white;">
                                            <th>اسم الحساب</th>
                                            <th>المبلغ</td>
                                        </tr>

                                        @foreach ($move['accounts'] as $acc)
                                            <tr>
                                                <td>{{ $acc->account_name }}</td>
                                                <td>{{ $acc->account_money * (-1)}}</td>
                                            </tr>
                                        @endforeach

                                        @if ($move['total_money_with_no_account'] != 0)
                                            <tr>
                                                <td>بدون حساب</td>
                                                <td>{{ $move['total_money_with_no_account'] }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="col-md-4">
                <h4 class="text-center mb-1 alert alert-info" style="padding: 0.5rem 1.25rem">الآجل</h4>

                <table dir="rtl" id="example2" class="table table-bordered table-hover">
                    <tr style="background-color: #007bff; color:white;">
                        <th>نوع الحركة</th>
                        <th>الاجمالي</td>
                    </tr>

                    @foreach ($all_unpaid_movements as $move)
                        <tr>
                            <td>{{ $move['name'] }}</td>
                            <td>
                                <p class="text-center mb-2" style="color:#d61e0a">{{ $move['total_money'] * (-1) }}</p>

                                @if ($report_type == 2)
                                    <table dir="rtl" id="example2" class="table table-bordered table-hover">
                                        <tr style="background-color: #81983f; color:white;">
                                            <th>اسم الحساب</th>
                                            <th>المبلغ</td>
                                        </tr>

                                        @foreach ($move['accounts'] as $acc)
                                            <tr>
                                                <td>{{ $acc->account_name }}</td>
                                                <td>{{ $acc->account_money * (-1)}}</td>
                                            </tr>
                                        @endforeach

                                        @if ($move['total_money_with_no_account'] != 0)
                                            <tr>
                                                <td>بدون حساب</td>
                                                <td>{{ $move['total_money_with_no_account'] }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>


        <br>

        <p style="position: relative;
            padding: 10px 10px 0px 10px;
            bottom: 0;
            width: 100%;
            /* Height of the footer*/
            text-align: center;font-size: 16px; font-weight: bold;
            "> {{ $systemData['address'] }} - {{ $systemData['phone'] }}
        </p>

    </body>
</html>
