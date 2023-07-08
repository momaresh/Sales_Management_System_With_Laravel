<html lang="en">
    <head>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </head>
    <body>


    <div class="form-group">
        <label>الفواتير</label>
        <select name="supplier_pills_add" id="supplier_pills_add" class="form-control select2">
            <option value="">اختر الفاتورة</option>
            @if (@isset($pills) && !@empty($pills))
                @foreach ($pills as $info )
                    <option value="{{ $info->pill_code }}"> فاتورة رقم {{ $info->pill_code }}  بتاريخ {{ $info->order_date }} </option>
                @endforeach
            @endif
        </select>
    </div>

        <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
        <script>
            $(function () {
                //Initialize Select2 Elements
                $('.select2').select2({
                theme: 'bootstrap4'
                })
            });
        </script>
    </body>
</html>
