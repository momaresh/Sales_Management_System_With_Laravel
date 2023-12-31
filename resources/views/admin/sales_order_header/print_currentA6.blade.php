<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> طباعة فاتورة مبيعات </title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <style>
            td{font-size: 15px !important;text-align: center;}
            .mainheadtable{
            width: 30%; margin-right: 5px; float: right;  border: 1px dashed black
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
                td{font-size: 9px !important;text-align: center;}
                table{margin: 0 auto;}

                .mainheadtable{
                    width: 50%; margin-right: 1px; float: right; margin-bottom: 10px; border: 1 solid black
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
                <td style="padding: 5px; text-align: right;font-weight: bold;">  فاتورة مبيعات رقم  <span>/ {{ $data['pill_code'] }}</span></td>
            </tr>
            <tr>
                <td class="tdhead"> كود العميل
                    @if(!@empty($data['customer_code']))
                        <span>/{{ $data["customer_code"] }}</span>
                    @else
                        لايوجد
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">اسم العميل<span>/{{ $data['customer_name'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">رقم التيلفون<span>/{{ $data['customer_phone'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">تاريخ الفاتورة<span>/{{ $data['order_date'] }}</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">نوع الفاتورة<span>/@if($data['pill_type'] == 1)كاش @else آجل @endif</span></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: right;font-weight: bold;">حالة الفاتورة<span>/@if($data['is_approved'] == 1) معتمدة @else غير معتمدة @endif</span></td>
            </tr>
        </table>

        <br>

        <table class="headimg"  dir="rtl" style="margin-bottom: 5px;">
            <tr>
                <td style="text-align:left !important;padding: 5px;">
                    <img class="headimg_img"  src="{{ asset('assets/admin/uploads/images').'/'.$systemData['photo'] }}">
                    <p>{{ $systemData['system_name'] }}</p>
                </td>
            </tr>
        </table>
        <table  dir="rtl" border="1" style="width: 98%; margin: 0 auto;"  id="example2" cellpadding="1" cellspacing="0"  aria-describedby="example2_info" >
            <tr style="background-color: gainsboro">
                <td style="font-weight: bold;">م</td>
                <td  style="font-weight: bold;">الصنف</td>
                <td  style="font-weight: bold;">الوحدة </td>
                <td style="font-weight: bold;">الكمية</td>
                <td  style="font-weight: bold;">السعر</td>
                <td style="font-weight: bold;">اجمالي</td>
                <td style="font-weight: bold;" >المخزن</td>
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
                        <td>
                            {{ $info->store_name }}
                        </td>
                    </tr>
                    <?php $i++;?>
                @endforeach
                <tr>
                    <td colspan="8" style="color:brown !important"><br>  اجمالي الفاتورة
                        {{ $data['total_cost'] * 1 }}
                    </td>
                </tr>
            @endif
        </table>

        <br>
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
