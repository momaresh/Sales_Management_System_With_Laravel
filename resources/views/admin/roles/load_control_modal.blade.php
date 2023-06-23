<html lang="en">
    <head>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/css.mycustomstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </head>
    <body>
    <form action="{{ route('admin.roles.store_permission_sub_menu_control') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <label>اسم التحكم</label>
                <select name="control_menu_id[]" multiple class="form-control select2">
                    @if (@isset($control_menus) && !@empty($control_menus))
                        @foreach ($control_menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <input type="hidden" name="roles_id" value="{{ $roles_id }}">
        <input type="hidden" name="roles_main_menu_id" value="{{ $roles_main_menu_id }}">
        <input type="hidden" name="roles_sub_menu_id" value="{{ $roles_sub_menu_id }}">

        <div class="col-md-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">اضافة</button>
        </div>
    </form>

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
