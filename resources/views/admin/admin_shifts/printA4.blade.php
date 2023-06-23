<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> طباعة بيانات شفت </title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
        <style>
            td{font-size: 15px !important;text-align: center;}
        </style>
   </head>
    <body style="padding-top: 10px;font-family: tahoma;">
        <table class="mb-3" cellspacing="0" style="width: 30%; margin-right: 5px; float: right;  border: 1px dashed black "  dir="rtl">
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;"> كود الشفت
                        <span style="margin-right: 10px;">/ {{ $data["shift_code"] }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;"> اسم المستخدم  <span style="margin-right: 10px;">/ {{ $data['admin_name'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">  اسم الخزنة  <span style="margin-right: 10px;">/ {{ $data['treasuries_name'];}}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">   تاريخ بداية الشفت  <span style="margin-right: 10px;">/ {{ $data['start_date'];}}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">   حالة انتهاء الشفت  <span style="margin-right: 10px;">/ @if ($data['is_finished'] == 1) مغلق @else مفتوح @endif</span></td>
            </tr>
            @if ($data['is_finished'] == 1)
                <tr>
                    <td style="padding: 5px; text-align: right;font-weight: bold;">   تاريخ نهاية الشفت  <span style="margin-right: 10px;">/ {{ $data['end_date']}}</span></td>
                </tr>
                <tr>
                    <td style="padding: 5px; text-align: right;font-weight: bold;">   انتهى بواسطة  <span style="margin-right: 10px;">/ {{ $data['finished_by_name']}}</span></td>
                </tr>
            @endif

            @if ($data['is_finished'] == 1)
                <tr>
                    <td style="padding: 5px; text-align: right;font-weight: bold;">   حالة تسليم الشفت  <span style="margin-right: 10px;">/ @if(@empty($data['delivered_to_shift_id'])) لم يسلم @else تم الاستلام @endif</span></td>
                </tr>
            @endif

            @if (!@empty($data['delivered_to_shift_id']))
                <tr>
                    <td style="padding: 5px; text-align: right;font-weight: bold;">   تم الاستلام بواسطة المستخدم  <span style="margin-right: 10px;">/ {{ $data['admin_review_name']}}</span></td>
                </tr>

                <tr>
                    <td style="padding: 5px; text-align: right;font-weight: bold;">   تم الاستلام الى الخزنة  <span style="margin-right: 10px;">/ {{ $data['treasuries_review_name']}}</span></td>
                </tr>
            @endif

        </table>

        <br>

        <table style="width: 30%;float: right;  margin-right: 5px;" dir="rtl">
            <tr>
                <td style="text-align: center;padding: 5px;">  <span style=" display: inline-block;
                    width: 200px;
                    height: 30px;
                    text-align: center;
                    background: yellow !important;
                    border: 1px solid black; border-radius: 15px;font-weight: bold;">بيانات شفت </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding: 5px;font-weight: bold;">  <span style=" display: inline-block;
                    width: 200px;
                    height: 30px;
                    text-align: center;
                    color: red;
                    border: 1px solid black; "> رقم : {{ $data['shift_code'] }} </span>
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
            <h4>الحركات الصرفية على الشفت</h4>
            @if (!@empty($exchange_transactions[0]))
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
            <h4>الحركات التحصيلية على الشفت</h4>
            @if (!@empty($collection_transactions[0]))
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


        <p style="position: fixed;
            padding: 10px 10px 0px 10px;
            bottom: 0;
            width: 100%;
            /* Height of the footer*/
            text-align: center;font-size: 16px; font-weight: bold;
            "> {{ $systemData['address'] }} - {{ $systemData['phone'] }} </p>
        <script>
            window.print();
        </script>
    </body>
</html>
