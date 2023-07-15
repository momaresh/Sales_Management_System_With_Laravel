<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>طباعة فاتورة مرتجع مشتريات بالفاتورة الاصل</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
        <style>
            td{font-size: 15px !important;text-align: center;}
        </style>
   </head>
    <body style="padding-top: 10px;font-family: tahoma;">
        <table class="mb-3" cellspacing="0" style="width: 30%; margin-right: 5px; float: right;  border: 1px dashed black "  dir="rtl">
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;"> كود المورد
                    @if (!@empty($data['supplier_code']))
                        <span style="margin-right: 10px;">/ {{ $data["supplier_code"] }}</span>
                    @else
                    /لايوجد
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">اسم المورد<span style="margin-right: 10px;">/ {{ $data['supplier_name'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">رقم التيلفون<span style="margin-right: 10px;">/ {{ $data['supplier_phone'];}}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">تاريخ الفاتورة<span style="margin-right: 10px;">/ {{ $data['return_date'];}}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">المخزن<span style="margin-right: 10px;">/ {{ $data['store_name'];}}</span></td>
            </tr>
        </table>

        <br>

        <table style="width: 30%;float: right;  margin-right: 5px;" dir="rtl">
            <tr>
                <td style="text-align: center;padding: 5px;">  <span style=" display: inline-block;
                    width: 280px;
                    height: 30px;
                    text-align: center;
                    background: yellow !important;
                    border: 1px solid black; border-radius: 15px;font-weight: bold;">فاتورة مرتجع مشتريات بالفاتورة الاصل</span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding: 5px;font-weight: bold;">  <span style=" display: inline-block;
                    width: 250px;
                    height: 30px;
                    text-align: center;
                    color: red;
                    border: 1px solid black; ">رقم الاصل : {{ $data['pill_code'] }} رقم المرتجع: {{ $data['child_pill_code'] }} </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding: 5px;">  <span style=" display: inline-block;
                    width: 200px;
                    height: 30px;
                    text-align: center;
                    color: blue;
                    border: 1px solid blue;font-weight: bold; "> @if ($data['pill_type'] == 1) كاش @else آجل @endif </span>
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

        <table  dir="rtl" border="1" style="width: 98%; margin: 0 auto;"  id="example2" cellpadding="1" cellspacing="0"  aria-describedby="example2_info" >
            <tr style="background-color: gainsboro">
            <td style="font-weight: bold;">م</td>
            <td  style="font-weight: bold;">الصنف</td>
            <td  style="font-weight: bold;">الوحدة </td>
            <td style="font-weight: bold;">الكمية المرتجعة</td>
            <td  style="font-weight: bold;">السعر</td>
            <td style="font-weight: bold;">اجمالي</td>
            </tr>
            @if (!@empty($sales_invoices_details) and count($sales_invoices_details) > 0)
                @php $i = 1; $totalItems = 0; @endphp
                @foreach($sales_invoices_details as $info)
                    <tr>
                        <td>
                            {{ $i }}
                        </td>
                        <td>
                            {{ $info->item_name }}
                        </td>
                        <td>
                            {{ $info->unit_name }}
                        </td>
                        <td>
                            {{ $info->quantity * 1 }}
                        </td>
                        <td>
                            {{ $info->unit_price * 1 }}
                        </td>
                        <td>
                            {{ $info->total_price * 1 }}
                        </td>
                    </tr>
                    <?php $i++;?>
                @endforeach

                <tr>
                    <td colspan="8" style="color:brown !important"><br>  اجمالي الاصناف
                        {{ $data ['total_cost'] * 1 }} جنيه فقط لاغير
                    </td>
                </tr>
            @endif
        </table>

        <br>

        <table  dir="rtl" border="1" style="width: 98%; margin: 0 auto;"  id="example2" cellpadding="1" cellspacing="0"  aria-describedby="example2_info" >
            <tr >
                <td style="font-weight: bold;">نسبة الضريبة</td>
                <td style="font-weight: bold;">نسبة الخصم</td>
                <td style="font-weight: bold;">صافي الفاتورة </td>
            </tr>

            <tr>
                <td>{{ $data['tax_percent'] * (1) }}</td>
                <td>{{ $data['discount_percent'] * (1) }}</td>
                <td>{{ $data['total_cost'] * (1) }}</td>
            </tr>
        </table>

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
