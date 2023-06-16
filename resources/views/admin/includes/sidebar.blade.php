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
            <li class="nav-item has-treeview
                    {{ request()->is('admin/panelSetting*') ||
                     request()->is('admin/treasuries/*') ?  'menu-open' : '' }}">

                <a href="#" class="nav-link
                    {{ request()->is('admin/panelSetting*') ||
                     request()->is('admin/treasuries/*') ?  'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    الضبط العام
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.panelSetting.index') }}" class="nav-link {{ request()->is('admin/panelSetting*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-th"></i>
                          <p>
                            الضبط العام
                            {{-- <span class="right badge badge-danger">New</span> --}}
                          </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.treasuries.index') }}" class="nav-link {{ request()->is('admin/treasuries/*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-th"></i>
                          <p>
                            الخزن
                          </p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-treeview
                {{  request()->is('admin/sales_matrial_type*') ||
                    request()->is('admin/stores*') ||
                    request()->is('admin/inv_units*') ||
                    request()->is('admin/inv_item_categories*') ||
                    request()->is('admin/inv_item_card*')  ?  'menu-open' : '' }}">

                <a href="#" class="nav-link
                {{  request()->is('admin/sales_matrial_type*') ||
                    request()->is('admin/stores*') ||
                    request()->is('admin/inv_units*') ||
                    request()->is('admin/inv_item_categories*') ||
                    request()->is('admin/inv_item_card*')  ?  'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    المخازن
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.sales_matrial_type.index') }}" class="nav-link {{ request()->is('admin/sales_matrial_type*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-th"></i>
                          <p>
                            فئات الفواتير
                          </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.stores.index') }}" class="nav-link {{ request()->is('admin/store*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-th"></i>
                          {{-- <i class="fa-solid fa-store fa-1x"></i> --}}
                          <p>
                            المخازن
                          </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.inv_units.index') }}" class="nav-link {{ request()->is('admin/inv_units*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-th"></i>
                          {{-- <i class="fa-solid fa-store fa-1x"></i> --}}
                          <p>
                            الوحدات
                          </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('inv_item_categories.index') }}" class="nav-link {{ request()->is('admin/inv_item_categories*') ? 'active' : '' }}" >
                          <i class="nav-icon fas fa-th"></i>
                          {{-- <i class="fa-solid fa-store fa-1x"></i> --}}
                          <p>
                            فئات الاصناف
                          </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.inv_item_card.index') }}" class="nav-link {{ request()->is('admin/inv_item_card*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-th"></i>
                          {{-- <i class="fa-solid fa-store fa-1x"></i> --}}
                          <p>
                            الاصناف
                          </p>
                        </a>
                    </li>

                </ul>
            </li>




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
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    الحسابات
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.account_types.index') }}" class="nav-link {{ request()->is('admin/account_types*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                انواع الحسابات
                                {{-- <span class="right badge badge-danger">New</span> --}}
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.accounts.index') }}" class="nav-link {{ request()->is('admin/accounts*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                الحسابات
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                العملاء
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.delegates.index') }}" class="nav-link {{ request()->is('admin/delegates*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                المناديب
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ request()->is('admin/suppliers*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                الموردين
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.treasuries_transactions.index') }}" class="nav-link {{ request()->is('admin/treasuries_transactions*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                شاشة تحصيل النقدية
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.exchange_transactions.index') }}" class="nav-link {{ request()->is('admin/exchange_transactions*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                شاشة صرف النقدية
                            </p>
                        </a>
                    </li>
                </ul>
            </li>


            <li class="nav-item has-treeview
                    {{ request()->is('admin/purchase_header/*') ||
                        request()->is('admin/purchase_order_header_general_return/*') ||
                        request()->is('admin/items_in_stores*') ?  'menu-open' : '' }}">

                <a href="#" class="nav-link
                    {{ request()->is('admin/purchase_header/*') ||
                        request()->is('admin/purchase_order_header_general_return/*') ||
                        request()->is('admin/items_in_stores/*') ?  'active' : '' }}">

                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    الحركات المخزنية
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.purchase_header.index') }}" class="nav-link {{ request()->is('admin/purchase_header/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                فواتير المشتريات
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.purchase_order_header_general_return.index') }}" class="nav-link {{ request()->is('admin/purchase_order_header_general_return/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                فواتير المرتجعات العام
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.items_in_stores.index') }}" class="nav-link {{ request()->is('admin/items_in_stores/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                الاصناف في المخازن
                            </p>
                        </a>
                    </li>


                </ul>
            </li>


            <li class="nav-item has-treeview
                    {{ request()->is('admin/sales_header/*') ||
                        request()->is('admin/sales_order_header_general_return/*')?  'menu-open' : '' }}">
                <a href="#" class="nav-link
                {{ request()->is('admin/sales_header/*') ||
                    request()->is('admin/sales_order_header_general_return/*') ?  'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    المبيعات
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.sales_header.index') }}" class="nav-link {{ request()->is('admin/sales_header/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                فواتير المبيعات
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.sales_order_header_general_return.index') }}" class="nav-link {{ request()->is('admin/sales_order_header_general_return/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                فواتير المرتجعات العام
                            </p>
                        </a>
                    </li>
                </ul>
            </li>


            <li class="nav-item has-treeview
                    {{ request()->is('admin/admins*') ?  'menu-open' : '' }}">

                <a href="#" class="nav-link
                {{ request()->is('admin/admins*') ?  'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    الصلاحيات
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.admins.index') }}" class="nav-link {{ request()->is('admin/admins*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                المستخدمين
                            </p>
                        </a>
                    </li>
                </ul>
            </li>

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
                    <li class="nav-item">
                        <a href="{{ route('admin.admin_shifts.index') }}" class="nav-link {{ request()->is('admin/admin_shifts*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                شفتات الخزن
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>




