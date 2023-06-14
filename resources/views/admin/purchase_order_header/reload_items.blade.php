<div class="col-12">
    <div class="card">
        <div class="card-header">
        <h3 class="text-center" style="font-weight: 600; font-size: 20px;">بيانات الاصناف</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body ">
            <div>
                <table id="example2" class="table table-bordered table-hover">

                    @if (!@empty($details[0]))

                        <tr style="background-color: #007bff; color:white;">
                            <th>اسم الصنف</th>
                            <th>الكمية</th>
                            <th>سعر الوحدة</th>
                            <th>الاجمالي</th>
                            <th>تاريخ الاضافة</th>
                            <th>التحكم</th>
                        </tr>

                        @foreach ($details as $detail)
                            <tr>
                                <td>{{ $detail['item_card_name'] }} <br/>
                                    <span style="color: green">{{ $detail['production_date'] }}</span> <br/>
                                    <span style="color: red">{{ $detail['expire_date'] }}</span>
                                </td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->unit_price }}</td>
                                <td>{{ $detail->total_price }}</td>

                                <td>
                                    @if ($detail['added_by'] != null)
                                        @php
                                            $d = new DateTime($detail['created_at']);
                                            $date = $d->format('d/m/Y الساعة h:i:sA');
                                        @endphp

                                        {{ $date }}
                                        بواسطة
                                        {{ $detail['added_by_name'] }}
                                    @else
                                        لم يتم تسجيل بيانات المضاف
                                    @endif
                                </td>

                                <td>
                                    @if ($data->is_approved == 0)
                                        <button data-purchase_order_detail_id="{{ $detail->id }}" class="btn btn-info edit_item_button">
                                            تعديل
                                        </button>
                                        <a href="{{ route('admin.purchase_header.delete_item', [$detail->id, $data->id]) }}" class="btn btn-danger are_you_sure">
                                            حذف
                                        </a>
                                    @endif
                                </td>
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
