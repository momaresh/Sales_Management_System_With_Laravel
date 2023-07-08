<html lang="en">
    <head>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/css.mycustomstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </head>
    <body>
    <div class="form-group">
        <label>بيانات الباتشات في المخزن</label>
        <select  id="batch_id_add" class="form-control select2 batch_id_add_return" style="width: 100%;">
            <option value="new">اضافة في باتش جديدة</option>
            @if (@isset($item_card_batches) && !@empty($item_card_batches))
                @foreach ($item_card_batches as $info)
                    <option
                        @if ($batch_id == $info->id)
                            selected
                        @endif
                        data-batch_id="{{ $info->id }}"
                        data-quantity="{{ $info->quantity }}"
                        data-production_date="{{ $info->production_date }}"
                        data-expire_date="{{ $info->expire_date }}"
                        value="{{ $info->id }}"> {{ $info->all_data }}
                    </option>
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

                if ($(window).width() < 1100) {
                    $('table').addClass('table-responsive');
                }
                else {
                    $('table').removeClass('table-responsive');
                }

            });
        </script>
</body>
</html>
