<html lang="en">
    <head>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/css.mycustomstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </head>
    <body>

        <div class="form-group">
            <label>بيانات وحدات الصنف</label>
            <select  id="unit_id_add" class="form-control select2" style="width: 100%;">
                <option value="">اختر الوحده</option>
                @if (@isset($item_card_data) && !@empty($item_card_data))
                    @if($item_card_data['does_has_retailunit']==1)
                        <option data-isparentunit="1" selected   value="{{ $item_card_data['unit_id'] }}"> {{ $item_card_data['parent_unit_name']  }} (وحده اب) </option>
                        <option  data-isparentunit="0"   value="{{ $item_card_data['retail_unit_id'] }}"> {{ $item_card_data['retail_unit_name']  }} (وحدة تجزئة) </option>
                    @else
                        <option   data-isparentunit="1" selected   value="{{ $item_card_data['unit_id'] }}"> {{ $item_card_data['parent_unit_name']  }} (وحده اب) </option>
                    @endif
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
