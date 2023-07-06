<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('assets/admin/uploads/images/Mo_MareshLogo.png') }}" alt="Mo_Maresh Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Mo_Maresh</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ route('admin.admins.details', auth()->user()->id) }}" class="d-block">{{ auth()->user()->user_name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview"  role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            @if (check_main_menu_role('الضبط العام') == true)
                <li class="nav-item has-treeview
                        {{ request()->is('admin/panelSetting*') ||
                        request()->is('admin/treasuries/*') ?  'menu-open' : '' }}">

                    <a href="#" class="nav-link
                        {{ request()->is('admin/panelSetting*') ||
                        request()->is('admin/treasuries/*') ?  'active' : '' }}">
                        <i class="fa-solid fa-screwdriver-wrench mr-2"></i>
                    <p>
                        الضبط العام
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (check_sub_menu_role('الضبط العام', 'الضبط العام') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.panelSetting.index') }}" class="nav-link {{ request()->is('admin/panelSetting*') ? 'active' : '' }}">
                                <i class="fa-solid fa-gears mr-1 ml-3"></i>
                                <p>
                                    الضبط العام
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الضبط العام', 'الخزن') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.treasuries.index') }}" class="nav-link {{ request()->is('admin/treasuries/*') ? 'active' : '' }}">
                                <i class="fa-solid fa-cash-register mr-1 ml-3"></i>
                                <p>
                                    الخزن
                                </p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            @if (check_main_menu_role('المخازن') == true)
                <li class="nav-item has-treeview
                    {{
                        request()->is('admin/stores*') ||
                        request()->is('admin/inv_units*') ||
                        request()->is('admin/inv_item_categories*') ||
                        request()->is('admin/inv_item_card*')  ?  'menu-open' : '' }}">

                    <a href="#" class="nav-link
                    {{
                        request()->is('admin/stores*') ||
                        request()->is('admin/inv_units*') ||
                        request()->is('admin/inv_item_categories*') ||
                        request()->is('admin/inv_item_card*')  ?  'active' : '' }}">
                    <i class="fa-brands fa-stack-overflow mr-2"></i>
                    <p>
                        المخازن
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (check_sub_menu_role('المخازن', 'المخازن') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.stores.index') }}" class="nav-link {{ request()->is('admin/store*') ? 'active' : '' }}">
                                <i class="fa-solid fa-store mr-1 ml-3"></i>
                                <p>
                                    المخازن
                                </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('المخازن', 'الوحدات') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.inv_units.index') }}" class="nav-link {{ request()->is('admin/inv_units*') ? 'active' : '' }}">
                                <i class="fa-solid fa-tags mr-1 ml-3"></i>
                                <p>
                                    الوحدات
                                </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('المخازن', 'فئات الاصناف') == true)
                            <li class="nav-item">
                                <a href="{{ route('inv_item_categories.index') }}" class="nav-link {{ request()->is('admin/inv_item_categories*') ? 'active' : '' }}" >
                                    <i class="nav-icon fas fa-th mr-0 ml-3" style="font-size: 15px"></i>
                                    <p>
                                    فئات الاصناف
                                </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('المخازن', 'الاصناف') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.inv_item_card.index') }}" class="nav-link {{ request()->is('admin/inv_item_card*') ? 'active' : '' }}">
                                <i class="fa-solid fa-cart-plus mr-1 ml-3"></i>
                                <p>
                                    الاصناف
                                </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif



            @if (check_main_menu_role('الحسابات') == true)
                <li class="nav-item has-treeview
                        {{ request()->is('admin/account_types*') ||
                            request()->is('admin/accounts*') ||
                            request()->is('admin/customers*') ||
                            request()->is('admin/delegates*') ||
                            request()->is('admin/suppliers*') ||
                            request()->is('admin/treasuries_transactions*') ||
                            request()->is('admin/exchange_transactions*') ?  'menu-open' : '' }}">

                    <a href="#" class="nav-link
                            {{ request()->is('admin/account_types*') ||
                            request()->is('admin/accounts*') ||
                            request()->is('admin/customers*') ||
                            request()->is('admin/delegates*') ||
                            request()->is('admin/suppliers*') ||
                            request()->is('admin/treasuries_transactions*') ||
                            request()->is('admin/exchange_transactions*') ?  'active' : '' }}">
                    <i class="fa-solid fa-money-bill-trend-up mr-2"></i>
                    <p>
                        الحسابات
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (check_sub_menu_role('الحسابات', 'انواع الحسابات') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.account_types.index') }}" class="nav-link {{ request()->is('admin/account_types*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-th mr-0 ml-3" style="font-size: 15px"></i>
                                    <p>
                                        انواع الحسابات
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحسابات', 'الحسابات') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.accounts.index') }}" class="nav-link {{ request()->is('admin/accounts*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-dollar-sign mr-1 ml-3"></i>
                                    <p>
                                        الحسابات
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحسابات', 'العملاء') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-user-tag mr-1 ml-3"></i>
                                    <p>
                                        العملاء
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحسابات', 'المناديب') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.delegates.index') }}" class="nav-link {{ request()->is('admin/delegates*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-user-tag mr-1 ml-3"></i>
                                    <p>
                                        المناديب
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحسابات', 'الموردين') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ request()->is('admin/suppliers*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-user-tag mr-1 ml-3"></i>
                                    <p>
                                        الموردين
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحسابات', 'شاشة تحصيل النقدية') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.treasuries_transactions.index') }}" class="nav-link {{ request()->is('admin/treasuries_transactions*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-hand-holding-dollar mr-1 ml-3"></i>
                                    <p>
                                        شاشة تحصيل النقدية
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحسابات', 'شاشة صرف النقدية') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.exchange_transactions.index') }}" class="nav-link {{ request()->is('admin/exchange_transactions*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-sack-dollar mr-1 ml-3"></i>
                                    <p>
                                        شاشة صرف النقدية
                                    </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (check_main_menu_role('الحركات المخزنية') == true)
                <li class="nav-item has-treeview
                        {{ request()->is('admin/purchase_header/*') ||
                            request()->is('admin/purchase_order_header_general_return/*') ||
                            request()->is('admin/items_in_stores*') ||
                            request()->is('admin/inv_stores_inventory/*') ||
                            request()->is('admin/purchase_order_header_original_return/*') ?  'menu-open' : '' }}">

                    <a href="#" class="nav-link
                        {{ request()->is('admin/purchase_header/*') ||
                            request()->is('admin/purchase_order_header_general_return/*') ||
                            request()->is('admin/items_in_stores/*') ||
                            request()->is('admin/inv_stores_inventory/*') ||
                            request()->is('admin/purchase_order_header_original_return/*') ?  'active' : '' }}">

                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        الحركات المخزنية
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (check_sub_menu_role('الحركات المخزنية', 'فواتير المشتريات') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.purchase_header.index') }}" class="nav-link {{ request()->is('admin/purchase_header/*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-basket-shopping mr-1 ml-3"></i>
                                    <p>
                                        فواتير المشتريات
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحركات المخزنية', 'فواتير المرتجعات العام') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.purchase_order_header_general_return.index') }}" class="nav-link {{ request()->is('admin/purchase_order_header_general_return/*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-basket-shopping mr-1 ml-3"></i>
                                    <p>
                                        فواتير المرتجعات العام
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.purchase_order_header_original_return.index') }}" class="nav-link {{ request()->is('admin/purchase_order_header_original_return/*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-basket-shopping mr-1 ml-3"></i>
                                    <p>
                                        فواتير المرتجعات بالاصل
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحركات المخزنية', 'الاصناف في المخازن') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.items_in_stores.index') }}" class="nav-link {{ request()->is('admin/items_in_stores/*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-cubes-stacked mr-1 ml-3"></i>
                                    <p>
                                        الاصناف في المخازن
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الحركات المخزنية', 'جرد المخازن') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.inv_stores_inventory.index') }}" class="nav-link {{ request()->is('admin/inv_stores_inventory/*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-cubes-stacked mr-1 ml-3"></i>
                                    <p>
                                        جرد المخازن
                                    </p>
                                </a>
                            </li>
                        @endif


                    </ul>
                </li>
            @endif

            @if (check_main_menu_role('المبيعات') == true)
                <li class="nav-item has-treeview
                        {{ request()->is('admin/sales_header/*') ||
                            request()->is('admin/sales_order_header_general_return/*') ||
                            request()->is('admin/sales_order_header_original_return/*') ?  'menu-open' : '' }}">
                    <a href="#" class="nav-link
                    {{ request()->is('admin/sales_header/*') ||
                        request()->is('admin/sales_order_header_general_return/*') ||
                        request()->is('admin/sales_order_header_original_return/*') ?  'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        المبيعات
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (check_sub_menu_role('المبيعات', 'فواتير المبيعات') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.sales_header.index') }}" class="nav-link {{ request()->is('admin/sales_header/*') ? 'active' : '' }}">
                                    <i class="fa-brands fa-shopify mr-1 ml-3"></i>
                                    <p>
                                        فواتير المبيعات
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('المبيعات', 'فواتير المرتجعات العام') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.sales_order_header_general_return.index') }}" class="nav-link {{ request()->is('admin/sales_order_header_general_return/*') ? 'active' : '' }}">
                                    <i class="fa-brands fa-shopify mr-1 ml-3"></i>
                                    <p>
                                        فواتير المرتجعات العام
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('المبيعات', 'فواتير المرتجعات بالاصل') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.sales_order_header_original_return.index') }}" class="nav-link {{ request()->is('admin/sales_order_header_original_return/*') ? 'active' : '' }}">
                                    <i class="fa-brands fa-shopify mr-1 ml-3"></i>
                                    <p>
                                        فواتير المرتجعات بالاصل
                                    </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (check_main_menu_role('الصلاحيات') == true)
                <li class="nav-item has-treeview
                    {{ request()->is('admin/admins/*') ||
                    request()->is('admin/roles/*') ||
                    request()->is('admin/roles_main_menu/*') ||
                    request()->is('admin/roles_sub_menu/*') ? 'menu-open' : '' }}">

                    <a href="#" class="nav-link
                        {{ request()->is('admin/admins/*') ||
                        request()->is('admin/roles/*') ||
                        request()->is('admin/roles_main_menu/*') ||
                        request()->is('admin/roles_sub_menu/*') ?  'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        الصلاحيات
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (check_sub_menu_role('الصلاحيات', 'المستخدمين') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.admins.index') }}" class="nav-link {{ request()->is('admin/admins/*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-th" style="font-size: 15px"></i>
                                <p>
                                    المستخدمين
                                </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الصلاحيات', 'الصلاحيات') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->is('admin/roles/*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th" style="font-size: 15px"></i>
                                <p>
                                    الصلاحيات
                                </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الصلاحيات', 'القوائم الرئيسية للصلاحيات') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.roles_main_menu.index') }}" class="nav-link {{ request()->is('admin/roles_main_menu/*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th" style="font-size: 15px"></i>
                                <p>
                                    القوائم الرئيسية للصلاحيات
                                </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('الصلاحيات', 'القوائم الفرعية للصلاحيات') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.roles_sub_menu.index') }}" class="nav-link {{ request()->is('admin/roles_sub_menu/*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th" style="font-size: 15px"></i>
                                <p>
                                    القوائم الفرعية للصلاحيات
                                </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (check_main_menu_role('حركة شفتات الخزن') == true)
                <li class="nav-item has-treeview
                        {{ request()->is('admin/admin_shifts*') ?  'menu-open' : '' }}">

                    <a href="#" class="nav-link
                    {{ request()->is('admin/admin_shifts*') ?  'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        حركة شفتات الخزن
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (check_sub_menu_role('حركة شفتات الخزن', 'شفتات الخزن') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.admin_shifts.index') }}" class="nav-link {{ request()->is('admin/admin_shifts*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-th mr-0 ml-3" style="font-size: 15px"></i>
                                    <p>
                                        شفتات الخزن
                                    </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (check_main_menu_role('التقارير') == true)
                <li class="nav-item has-treeview
                        {{ request()->is('admin/reports/*') ?  'menu-open' : '' }}">

                    <a href="#" class="nav-link
                    {{ request()->is('admin/reports/*') ?  'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        التقارير
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (check_sub_menu_role('التقارير', 'كشف حساب مورد') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.supplier_account_report') }}" class="nav-link {{ request()->is('admin/reports/supplier_account_report') ? 'active' : '' }}">
                                    <i class="fa-regular fa-clipboard mr-1 ml-3"></i>
                                    <p>
                                        كشف حساب مورد
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('التقارير', 'كشف حساب عميل') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.customer_account_report') }}" class="nav-link {{ request()->is('admin/reports/customer_account_report') ? 'active' : '' }}">
                                    <i class="fa-regular fa-clipboard mr-1 ml-3"></i>
                                    <p>
                                        كشف حساب عميل
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (check_sub_menu_role('التقارير', 'كشف التقارير اليومية') == true)
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.daily_report') }}" class="nav-link {{ request()->is('admin/reports/daily_report') ? 'active' : '' }}">
                                    <i class="fa-regular fa-clipboard mr-1 ml-3"></i>
                                    <p>
                                        كشف التقارير اليومية
                                    </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>




