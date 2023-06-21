<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> طباعة جرد مخازن </title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
        <style>
            td{font-size: 15px !important;text-align: center;}
        </style>
   </head>
    <body style="padding-top: 10px;font-family: tahoma;">
        <table class="mb-3" cellspacing="0" style="width: 30%; margin-right: 5px; float: right;  border: 1px dashed black "  dir="rtl">
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;"> تاريخ الجرد
                    <span style="margin-right: 10px;">/ {{ $data["inventory_date"] }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">مخزن الجرد<span style="margin-right: 10px;">/ {{ $data['store_name'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">نوع الجرد<span style="margin-right: 10px;">/
                @if ($data->inventory_type == 1)
                    يومي
                @elseif ($data->inventory_type == 2)
                    اسبوعي
                @elseif ($data->inventory_type == 3)
                    شهري
                @elseif ($data->inventory_type == 4)
                    سنوي
                @endif</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">حالة الجرد<span style="margin-right: 10px;">/
                @if ($data->is_closed == 1)
                    مغلق ومرحل
                @else
                    مفتوح
                @endif
                </span></td>
            </tr>
        </table>

        <br>

        <table style="width: 30%;float: right;  margin-right: 5px;" dir="rtl">
            <tr>
                <td style="text-align: center;padding: 5px;">  <span style=" display: inline-block;
                    width: 200px;
                    height: 30px;
                    text-align: center;
                    background: yellow !important;
                    border: 1px solid black; border-radius: 15px;font-weight: bold;">جرد مخازن </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding: 5px;font-weight: bold;">  <span style=" display: inline-block;
                    width: 200px;
                    height: 30px;
                    text-align: center;
                    color: red;
                    border: 1px solid black; "> رقم : {{ $data['id'] }} </span>
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

        <div class="row" style="width:98%; margin: 0 auto;">
            @if (!@empty($details[0]))
            <table  dir="rtl" border="1" style="width: 98%; margin: 0 auto;"  id="example2" cellpadding="1" cellspacing="0"  aria-describedby="example2_info" >
                <tr style="background-color: gainsboro">
                <td style="font-weight: bold;">م</td>
                <td style="font-weight: bold;">كود الباتش</td>
                <td  style="font-weight: bold;">الصنف</td>
                <td style="font-weight: bold;">الكمية بالباتش</td>
                <td style="font-weight: bold;">الكمية الدفترية</td>
                <td  style="font-weight: bold;">الفرق </td>
                <td  style="font-weight: bold;">سعر الوحدة</td>
                <td style="font-weight: bold;">سبب النقص/الزيادة</td>
                </tr>
                    @php $i = 1; $totalItems = 0; @endphp
                    @foreach($details as $info)
                        <tr>
                            <td>
                                {{ $i }}
                            </td>
                            <td>
                                {{ $info->batch_id }}
                            </td>
                            <td>
                                {{ $info->item_name }}
                            </td>
                            <td>
                                {{ $info->old_quantity * 1}}
                            </td>
                            <td>
                                {{ $info->new_quantity * 1 }}
                            </td>
                            <td>
                                {{ $info->different_quantity * 1 }}
                            </td>
                            <td>
                                {{ $info->unit_price * 1 }}
                            </td>
                            <td>
                                {{ $info->notes }}
                            </td>
                        </tr>
                        <?php $i++;?>
                    @endforeach
            </table>
        @else
            <div class="alert col-md-12 text-center alert-info">
                لا يوجد بيانات لعرضها
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
            "> {{ $systemData['address'] }} - {{ $systemData['phone'] }} </p>
        <script>
            window.print();
        </script>
    </body>
</html>
