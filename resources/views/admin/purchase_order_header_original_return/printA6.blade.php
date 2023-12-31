<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> طباعة فاتورة مرتجع مشتريات بالفاتورة الاصل </title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <style>
            td{font-size: 15px !important;text-align: center;}
            .mainheadtable{
                width: 30%; margin-right: 5px; float: right; margin-bottom: 10px; border: 1px dashed black
            }
            .tdhead{
                padding: 3px; text-align: right;font-weight: bold;
            }
            .mainheadtable2{
                width: 30%;float: right;  margin-right: 5px;
            }
            .headimg{
                width: 35%;float: right; margin-left: 5px;
            }
            .headimg_img{
                width: 150px; height: 110px; border-radius: 10px;
            }

            @media print {
                * {font-size: 10px}
                @page {
                size: 105mm 148mm;
                }
                td{font-size: 9px !important; text-align: center;}

                table{margin: 0 auto;}

                .mainheadtable{
                    width: 50%; margin-right: 1px; float: right; margin-bottom: 10px; border: 1 solid black
                }

                .mainheadtable td {
                    line-height: .5em
                }

                .tdhead{
                    padding: 3px; text-align: right;font-weight: bold;
                }

                .headimg{
                    width: 45%;float: right; margin-left: 1px;
                }

                .headimg_img{
                    width: 70px; height: 70px; float: left;
                }
            }
        </style>
    </head>

    <body style="padding-top: 10px;font-family: tahoma;">
        <table class="mainheadtable" cellspacing="0" dir="rtl">
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;"> رقم الفاتورة الاصل <span>/ {{ $data['pill_code'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;"> رقم الفاتورة المرتجع <span>/ {{ $data['child_pill_code'] }}</span></td>
            </tr>
            <tr>
                <td class="tdhead">كود المورد
                    @if(!@empty($data['supplier_code']))
                        <span>/{{ $data["supplier_code"] }}</span>
                    @else
                        لايوجد
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right; font-weight: bold;">اسم المورد<span>/{{ $data['supplier_name'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right; font-weight: bold;">رقم التيلفون<span>/{{ $data['supplier_phone'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right; font-weight: bold;">تاريخ الفاتورة<span>/{{ $data['return_date'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right; font-weight: bold;">نوع الفاتورة<span>/@if ($data['pill_type'] == 1)كاش @else آجل @endif</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right; font-weight: bold;">المخزن<span>/ {{ $data['store_name'] }}</span></td>
            </tr>
        </table>

        <br>

        <table class="headimg"  dir="rtl" style="margin-bottom: 5px;">
            <tr>
                <td style="text-align:left !important; padding: 5px;">
                    <img class="headimg_img"  src="{{ asset('assets/admin/uploads/images').'/'.$systemData['photo'] }}">
                    <p style="font-family:cursive">{{ $systemData['system_name'] }}</p>
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
            @if(!@empty($sales_invoices_details) and count($sales_invoices_details)>0)
                @php $i=1; $totalItems=0; @endphp
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
                        {{ $data['total_cost'] * 1 }} جنيه فقط لاغير
                    </td>
                </tr>
            @endif
        </table>

        <br>

        <table  dir="rtl" border="1" style="width: 98%; margin: 0 auto;"  id="example2" cellpadding="1" cellspacing="0"  aria-describedby="example2_info" >
            <tr >
                <td style="font-weight: bold;">نسبة الضريبة</td>
                <td style="font-weight: bold;">خصم</td>
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
            text-align: center;font-size: 11px; font-weight: bold;
            "> {{ $systemData['address'] }} --- {{ $systemData['phone'] }}
        </p>
        <script>
            window.print();
        </script>
    </body>
</html>
