<html lang="en">
    <head>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/css.mycustomstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </head>
    <body>
        <hr style="border: 1px solid" class="my-col-main">

        <h5 class="my-col-main" style="width:fit-content; margin: 10px auto">اضافة فاتورة</h5>
        <div class="row">
            <input type="hidden" readonly name="pill_number" id="pill_n" class="form-control">

            <div class="col-md-3">
                <div class="form-group">
                    <label>نوع الفاتورة</label>
                    <select name="pill_type" id="pill_type" class="form-control select2">
                        <option value="1">نقدا</option>
                        <option value="2">آجل</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3" id="customer_code_div">
                <div class="form-group">
                    <label>اسم العميل</label>
                    <select name="customer_code" id="customer_code" class="form-control select2">
                        <option value="">اختر العميل</option>
                        @if (@isset($customers) && !@empty($customers))
                            @foreach ($customers as $info )
                                <option value="{{ $info->customer_code }}"> {{ $info->first_name }}  {{ $info->last_name }} </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <label>اسم المندوب</label>
                    <select name="delegate_code" id="delegate_code" class="form-control select2">
                        <option value="">اختر المندوب</option>
                        @if (@isset($delegates) && !@empty($delegates))
                            @foreach ($delegates as $info )
                                <option value="{{ $info->delegate_code }}"> {{ $info->first_name }}  {{ $info->last_name }} </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>


            <div class="col-md-3">
                <label for="inputEmail3">تاريخ الفاتورة</label>
                <div class="form-group">
                    <input type="date" name="order_date" id="pill_date" class="form-control" value="{{ date('Y-m-d') }}" placeholder="تاريخ الفاتورة">
                </div>
            </div>

            <div class="col-md-3">
                <label>ملاحضات</label>
                <div class="form-group">
                    <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="store">اضافة</button>


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


