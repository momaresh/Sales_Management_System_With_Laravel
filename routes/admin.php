<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminPanelSettingController;
use App\Http\Controllers\Admin\TreasuryController;
use App\Http\Controllers\Admin\SalesMatrialTypeController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\InvUnitController;
use App\Http\Controllers\Admin\InvItemCategoryController;
use App\Http\Controllers\Admin\InvItemCardController;
use App\Http\Controllers\Admin\AccountTypeController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DelegateController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseOrderHeaderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminShiftController;
use App\Http\Controllers\Admin\CollectTransactionController;
use App\Http\Controllers\Admin\ExchangeTransactionController;
use App\Http\Controllers\Admin\InvStoreInventoryController;
use App\Http\Controllers\Admin\ItemInStoreController;
use App\Http\Controllers\Admin\PurchaseOrderHeaderGeneralReturnController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoleMainMenuController;
use App\Http\Controllers\Admin\RoleSubMenuController;
use App\Http\Controllers\Admin\SalesOrderHeaderController;
use App\Http\Controllers\Admin\SalesOrderHeaderGeneralReturnController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

define('PAGINATION_COUNT', 10);


Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function(){
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('logout', function() {
        auth()->logout();
        return redirect()->route('admin.login');
    })->name('admin.logout');

    Route::get('panelSetting/index', [AdminPanelSettingController::class, 'index'])->name('admin.panelSetting.index');
    Route::get('panelSetting/edit/{id}', [AdminPanelSettingController::class, 'edit'])->name('admin.panelSetting.edit');
    Route::post('panelSetting/update/{id}', [AdminPanelSettingController::class, 'update'])->name('admin.panelSetting.update');

    /* begin Treasuries */
    Route::get('treasuries/index', [TreasuryController::class, 'index'])->name('admin.treasuries.index');
    Route::get('treasuries/create', [TreasuryController::class, 'create'])->name('admin.treasuries.create');
    Route::post('treasuries/create', [TreasuryController::class, 'store'])->name('admin.treasuries.store');
    Route::get('treasuries/edit/{id}', [TreasuryController::class, 'edit'])->name('admin.treasuries.edit');
    Route::post('treasuries/update/{id}', [TreasuryController::class, 'update'])->name('admin.treasuries.update');
    Route::post('treasuries/ajax_search', [TreasuryController::class, 'ajax_search'])->name('admin.treasuries.ajax_search');
    Route::get('treasuries/details/{id}', [TreasuryController::class, 'details'])->name('admin.treasuries.details');
    Route::get('treasuries_delivery/create/{id}', [TreasuryController::class, 'create_delivery'])->name('admin.treasuries_delivery.create');
    Route::post('treasuries_delivery/store/{id}', [TreasuryController::class, 'store_delivery'])->name('admin.treasuries_delivery.store');
    Route::get('treasuries_delivery/delete/{id}/{from_id}', [TreasuryController::class, 'delete_delivery'])->name('admin.treasuries_delivery.delete');
    /* end Treasuries */


    /* begin Sales_matrial_type */
    Route::get('sales_matrial_type/index', [SalesMatrialTypeController::class, 'index'])->name('admin.sales_matrial_type.index');
    Route::get('sales_matrial_type/create', [SalesMatrialTypeController::class, 'create'])->name('admin.sales_matrial_type.create');
    Route::post('sales_matrial_type/store', [SalesMatrialTypeController::class, 'store'])->name('admin.sales_matrial_type.store');
    Route::get('sales_matrial_type/edit/{id}', [SalesMatrialTypeController::class, 'edit'])->name('admin.sales_matrial_type.edit');
    Route::post('sales_matrial_type/update/{id}', [SalesMatrialTypeController::class, 'update'])->name('admin.sales_matrial_type.update');
    Route::get('sales_matrial_type/delete/{id}', [SalesMatrialTypeController::class, 'destroy'])->name('admin.sales_matrial_type.delete');
    Route::post('sales_matrial_type/ajax_search', [SalesMatrialTypeController::class, 'ajax_search'])->name('admin.sales_matrial_type.ajax_search');
    /* end Sales_matrial_type */


    /* begin Stores */
    Route::get('stores/index', [StoreController::class, 'index'])->name('admin.stores.index');
    Route::get('stores/create', [StoreController::class, 'create'])->name('admin.stores.create');
    Route::post('stores/store', [StoreController::class, 'store'])->name('admin.stores.store');
    Route::get('stores/edit/{id}', [StoreController::class, 'edit'])->name('admin.stores.edit');
    Route::post('stores/update/{id}', [StoreController::class, 'update'])->name('admin.stores.update');
    Route::get('stores/delete/{id}', [StoreController::class, 'destroy'])->name('admin.stores.delete');
    Route::post('stores/ajax_search', [StoreController::class, 'ajax_search'])->name('admin.stores.ajax_search');
    /* end Stores */

    /* begin Inv_units */
    Route::get('inv_units/index', [InvUnitController::class, 'index'])->name('admin.inv_units.index');
    Route::get('inv_units/create', [InvUnitController::class, 'create'])->name('admin.inv_units.create');
    Route::post('inv_units/store', [InvUnitController::class, 'store'])->name('admin.inv_units.store');
    Route::get('inv_units/edit/{id}', [InvUnitController::class, 'edit'])->name('admin.inv_units.edit');
    Route::post('inv_units/update/{id}', [InvUnitController::class, 'update'])->name('admin.inv_units.update');
    Route::get('inv_units/delete/{id}', [InvUnitController::class, 'destroy'])->name('admin.inv_units.delete');
    Route::post('inv_units/ajax_search', [InvUnitController::class, 'ajax_search'])->name('admin.inv_units.ajax_search');
    /* end Inv_units */


    /* begin Inv_item_categories */
    Route::resource('/inv_item_categories', InvItemCategoryController::class);
    Route::get('inv_item_categories/delete/{id}', [InvItemCategoryController::class, 'delete'])->name('inv_item_categories.delete');
    Route::post('inv_item_categories/ajax_search', [InvItemCategoryController::class, 'ajax_search'])->name('inv_item_categories.ajax_search');
    /* end Inv_item_categories */


    /* begin Inv_item_card */
    Route::get('inv_item_card/index', [InvItemCardController::class, 'index'])->name('admin.inv_item_card.index');
    Route::get('inv_item_card/create', [InvItemCardController::class, 'create'])->name('admin.inv_item_card.create');
    Route::post('inv_item_card/store', [InvItemCardController::class, 'store'])->name('admin.inv_item_card.store');
    Route::get('inv_item_card/edit/{id}', [InvItemCardController::class, 'edit'])->name('admin.inv_item_card.edit');
    Route::post('inv_item_card/update/{id}', [InvItemCardController::class, 'update'])->name('admin.inv_item_card.update');
    Route::get('inv_item_card/delete/{id}', [InvItemCardController::class, 'delete'])->name('admin.inv_item_card.delete');
    Route::post('inv_item_card/ajax_search', [InvItemCardController::class, 'ajax_search'])->name('admin.inv_item_card.ajax_search');
    Route::post('inv_item_card/moves_ajax_search', [InvItemCardController::class, 'moves_ajax_search'])->name('admin.inv_item_card.moves_ajax_search');
    Route::get('inv_item_card/details/{id}', [InvItemCardController::class, 'details'])->name('admin.inv_item_card.details');
    /* end Inv_item_card */

    /* begin AccountTypeController */
    Route::get('account_types/index', [AccountTypeController::class, 'index'])->name('admin.account_types.index');
    Route::get('account_types/create', [AccountTypeController::class, 'create'])->name('admin.account_types.create');
    Route::post('account_types/store', [AccountTypeController::class, 'store'])->name('admin.account_types.store');
    // Route::get('account_types/edit/{id}', [AccountTypeController::class, 'edit'])->name('admin.account_types.edit');
    // Route::post('account_types/update/{id}', [AccountTypeController::class, 'update'])->name('admin.account_types.update');
    // Route::get('account_types/delete/{id}', [AccountTypeController::class, 'delete'])->name('admin.account_types.delete');
    // Route::post('account_types/ajax_search', [AccountTypeController::class, 'ajax_search'])->name('admin.account_types.ajax_search');
    // Route::get('account_types/details/{id}', [AccountTypeController::class, 'details'])->name('admin.account_types.details');
    /* end AccountTypeController */


    /* begin accounts */
    Route::get('accounts/index', [AccountController::class, 'index'])->name('admin.accounts.index');
    Route::get('accounts/create', [AccountController::class, 'create'])->name('admin.accounts.create');
    Route::post('accounts/store', [AccountController::class, 'store'])->name('admin.accounts.store');
    Route::get('accounts/edit/{id}', [AccountController::class, 'edit'])->name('admin.accounts.edit');
    Route::post('accounts/update/{id}', [AccountController::class, 'update'])->name('admin.accounts.update');
    Route::get('accounts/delete/{id}', [AccountController::class, 'delete'])->name('admin.accounts.delete');
    Route::post('accounts/ajax_search', [AccountController::class, 'ajax_search'])->name('admin.accounts.ajax_search');
    Route::get('accounts/details/{id}', [AccountController::class, 'details'])->name('admin.accounts.details');
    /* end accounts */

    /* begin customers */
    Route::get('customers/index', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('admin.customers.create');
    Route::post('customers/store', [CustomerController::class, 'store'])->name('admin.customers.store');
    Route::get('customers/edit/{id}', [CustomerController::class, 'edit'])->name('admin.customers.edit');
    Route::post('customers/update/{id}', [CustomerController::class, 'update'])->name('admin.customers.update');
    Route::get('customers/delete/{id}', [CustomerController::class, 'delete'])->name('admin.customers.delete');
    Route::post('customers/ajax_search', [CustomerController::class, 'ajax_search'])->name('admin.customers.ajax_search');
    Route::post('customers/details', [CustomerController::class, 'details'])->name('admin.customers.details');
    /* end customers */

    /* begin delegates */
    Route::get('delegates/index', [DelegateController::class, 'index'])->name('admin.delegates.index');
    Route::get('delegates/create', [DelegateController::class, 'create'])->name('admin.delegates.create');
    Route::post('delegates/store', [DelegateController::class, 'store'])->name('admin.delegates.store');
    Route::get('delegates/edit/{id}', [DelegateController::class, 'edit'])->name('admin.delegates.edit');
    Route::post('delegates/update/{id}', [DelegateController::class, 'update'])->name('admin.delegates.update');
    Route::get('delegates/delete/{id}', [DelegateController::class, 'delete'])->name('admin.delegates.delete');
    Route::post('delegates/ajax_search', [DelegateController::class, 'ajax_search'])->name('admin.delegates.ajax_search');
    Route::post('delegates/details', [DelegateController::class, 'details'])->name('admin.delegates.details');
    /* end delegates */

    /* begin suppliers */
    Route::get('suppliers/index', [SupplierController::class, 'index'])->name('admin.suppliers.index');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('admin.suppliers.create');
    Route::post('suppliers/store', [SupplierController::class, 'store'])->name('admin.suppliers.store');
    Route::get('suppliers/edit/{id}', [SupplierController::class, 'edit'])->name('admin.suppliers.edit');
    Route::post('suppliers/update/{id}', [SupplierController::class, 'update'])->name('admin.suppliers.update');
    Route::get('suppliers/delete/{id}', [SupplierController::class, 'delete'])->name('admin.suppliers.delete');
    Route::post('suppliers/ajax_search', [SupplierController::class, 'ajax_search'])->name('admin.suppliers.ajax_search');
    Route::post('suppliers/details', [SupplierController::class, 'details'])->name('admin.suppliers.details');
    /* end suppliers */


    /* begin purchase_header */
    Route::get('purchase_header/index', [PurchaseOrderHeaderController::class, 'index'])->name('admin.purchase_header.index');
    Route::get('purchase_header/create', [PurchaseOrderHeaderController::class, 'create'])->name('admin.purchase_header.create');
    Route::post('purchase_header/store', [PurchaseOrderHeaderController::class, 'store'])->name('admin.purchase_header.store');
    Route::get('purchase_header/edit/{id}', [PurchaseOrderHeaderController::class, 'edit'])->name('admin.purchase_header.edit');
    Route::post('purchase_header/update/{id}', [PurchaseOrderHeaderController::class, 'update'])->name('admin.purchase_header.update');
    Route::get('purchase_header/delete/{id}', [PurchaseOrderHeaderController::class, 'delete'])->name('admin.purchase_header.delete');
    Route::post('purchase_header/ajax_search', [PurchaseOrderHeaderController::class, 'ajax_search'])->name('admin.purchase_header.ajax_search');
    Route::get('purchase_header/details/{id}', [PurchaseOrderHeaderController::class, 'details'])->name('admin.purchase_header.details');
    Route::post('purchase_header/get_item_unit', [PurchaseOrderHeaderController::class, 'get_item_unit'])->name('admin.purchase_header.get_item_unit');
    Route::post('purchase_header/add_new_item', [PurchaseOrderHeaderController::class, 'add_new_item'])->name('admin.purchase_header.add_new_item');
    Route::post('purchase_header/reload_items', [PurchaseOrderHeaderController::class, 'reload_items'])->name('admin.purchase_header.reload_items');
    Route::post('purchase_header/reload_total_price', [PurchaseOrderHeaderController::class, 'reload_total_price'])->name('admin.purchase_header.reload_total_price');
    Route::post('purchase_header/edit_item', [PurchaseOrderHeaderController::class, 'edit_item'])->name('admin.purchase_header.edit_item');
    Route::post('purchase_header/create_item', [PurchaseOrderHeaderController::class, 'create_item'])->name('admin.purchase_header.create_item');
    Route::post('purchase_header/update_item', [PurchaseOrderHeaderController::class, 'update_item'])->name('admin.purchase_header.update_item');
    Route::get('purchase_header/delete_item/{id1}/{id2}', [PurchaseOrderHeaderController::class, 'delete_item'])->name('admin.purchase_header.delete_item');
    Route::post('purchase_header/load_modal_approved', [PurchaseOrderHeaderController::class, 'load_modal_approved'])->name('admin.purchase_header.load_modal_approved');
    Route::post('purchase_header/check_shift_and_reload_money', [PurchaseOrderHeaderController::class, 'check_shift_and_reload_money'])->name('admin.purchase_header.check_shift_and_reload_money');
    Route::post('purchase_header/do_approve/{id}', [PurchaseOrderHeaderController::class, 'do_approve'])->name('admin.purchase_header.do_approve');
    Route::get('purchase_header/printA4/{id}/{type}', [PurchaseOrderHeaderController::class, 'printA4'])->name('admin.purchase_header.printA4');
    /* end purchase_header */

    /* begin purchase_order_header_general_return */
    Route::get('purchase_order_header_general_return/index', [PurchaseOrderHeaderGeneralReturnController::class, 'index'])->name('admin.purchase_order_header_general_return.index');
    Route::post('purchase_order_header_general_return/store', [PurchaseOrderHeaderGeneralReturnController::class, 'store'])->name('admin.purchase_order_header_general_return.store');
    Route::get('purchase_order_header_general_return/delete/{id}', [PurchaseOrderHeaderGeneralReturnController::class, 'delete'])->name('admin.purchase_order_header_general_return.delete');
    Route::post('purchase_order_header_general_return/ajax_search', [PurchaseOrderHeaderGeneralReturnController::class, 'ajax_search'])->name('admin.purchase_order_header_general_return.ajax_search');
    Route::post('purchase_order_header_general_return/get_item_unit', [PurchaseOrderHeaderGeneralReturnController::class, 'get_item_unit'])->name('admin.purchase_order_header_general_return.get_item_unit');
    Route::post('purchase_order_header_general_return/get_item_batch', [PurchaseOrderHeaderGeneralReturnController::class, 'get_item_batch'])->name('admin.purchase_order_header_general_return.get_item_batch');
    Route::post('purchase_order_header_general_return/get_item_price', [PurchaseOrderHeaderGeneralReturnController::class, 'get_item_price'])->name('admin.purchase_order_header_general_return.get_item_price');
    Route::post('purchase_order_header_general_return/add_new_item', [PurchaseOrderHeaderGeneralReturnController::class, 'add_new_item'])->name('admin.purchase_order_header_general_return.add_new_item');
    Route::post('purchase_order_header_general_return/add_new_item_row', [PurchaseOrderHeaderGeneralReturnController::class, 'add_new_item_row'])->name('admin.purchase_order_header_general_return.add_new_item_row');
    Route::post('purchase_order_header_general_return/add_to_pill', [PurchaseOrderHeaderGeneralReturnController::class, 'add_to_pill'])->name('admin.purchase_order_header_general_return.add_to_pill');
    Route::post('purchase_order_header_general_return/load_pill_adding_items_modal', [PurchaseOrderHeaderGeneralReturnController::class, 'load_pill_adding_items_modal'])->name('admin.purchase_order_header_general_return.load_pill_adding_items_modal');
    Route::post('purchase_order_header_general_return/store_item', [PurchaseOrderHeaderGeneralReturnController::class, 'store_item'])->name('admin.purchase_order_header_general_return.store_item');
    Route::post('purchase_order_header_general_return/remove_item', [PurchaseOrderHeaderGeneralReturnController::class, 'remove_item'])->name('admin.purchase_order_header_general_return.remove_item');
    Route::post('purchase_order_header_general_return/approve_pill/{id}', [PurchaseOrderHeaderGeneralReturnController::class, 'approve_pill'])->name('admin.purchase_order_header_general_return.approve_pill');
    Route::post('purchase_order_header_general_return/check_shift_and_reload_money', [PurchaseOrderHeaderGeneralReturnController::class, 'check_shift_and_reload_money'])->name('admin.purchase_order_header_general_return.check_shift_and_reload_money');
    Route::post('purchase_order_header_general_return/create_pill', [PurchaseOrderHeaderGeneralReturnController::class, 'create_pill'])->name('admin.purchase_order_header_general_return.create_pill');
    Route::get('purchase_order_header_general_return/printA4/{id}/{type}', [PurchaseOrderHeaderGeneralReturnController::class, 'printA4'])->name('admin.purchase_order_header_general_return.printA4');
    /* end purchase_order_header_general_return */


    /* begin admins */
    Route::get('admins/index', [AdminController::class, 'index'])->name('admin.admins.index');
    Route::get('admins/create', [AdminController::class, 'create'])->name('admin.admins.create');
    Route::get('admins/create_treasuries/{id}', [AdminController::class, 'create_treasuries'])->name('admin.admins.create_treasuries');
    Route::post('admins/store', [AdminController::class, 'store'])->name('admin.admins.store');
    Route::post('admins/store_treasuries/{id}', [AdminController::class, 'store_treasuries'])->name('admin.admins.store_treasuries');
    Route::get('admins/edit/{id}', [AdminController::class, 'edit'])->name('admin.admins.edit');
    Route::post('admins/update/{id}', [AdminController::class, 'update'])->name('admin.admins.update');
    Route::get('admins/delete/{id}', [AdminController::class, 'delete'])->name('admin.admins.delete');
    Route::get('admins/delete_treasuries/{id1}/{id2}', [AdminController::class, 'delete_treasuries'])->name('admin.admins.delete_treasuries');
    Route::post('admins/ajax_search', [AdminController::class, 'ajax_search'])->name('admin.admins.ajax_search');
    Route::get('admins/details/{id}', [AdminController::class, 'details'])->name('admin.admins.details');
    /* end admins */

    /* begin admin_shifts */
    Route::get('admin_shifts/index', [AdminShiftController::class, 'index'])->name('admin.admin_shifts.index');
    Route::get('admin_shifts/create', [AdminShiftController::class, 'create'])->name('admin.admin_shifts.create');
    Route::post('admin_shifts/store', [AdminShiftController::class, 'store'])->name('admin.admin_shifts.store');
    Route::get('admin_shifts/end_shift/{id}', [AdminShiftController::class, 'end_shift'])->name('admin.admin_shifts.end_shift');
    Route::get('admin_shifts/printA4/{id}', [AdminShiftController::class, 'printA4'])->name('admin.admin_shifts.printA4');
    Route::post('admin_shifts/review_shift', [AdminShiftController::class, 'review_shift'])->name('admin.admin_shifts.review_shift');
    /* end admin_shifts */

    /* begin treasuries_transactions */
    Route::get('treasuries_transactions/index', [CollectTransactionController::class, 'index'])->name('admin.treasuries_transactions.index');
    Route::get('treasuries_transactions/create', [CollectTransactionController::class, 'create'])->name('admin.treasuries_transactions.create');
    Route::post('treasuries_transactions/store', [CollectTransactionController::class, 'store'])->name('admin.treasuries_transactions.store');
    Route::post('treasuries_transactions/get_status', [CollectTransactionController::class, 'get_status'])->name('admin.treasuries_transactions.get_status');
    Route::post('treasuries_transactions/ajax_search', [CollectTransactionController::class, 'ajax_search'])->name('admin.treasuries_transactions.ajax_search');
    /* end treasuries_transactions */

    /* begin exchange_transactions */
    Route::get('exchange_transactions/index', [ExchangeTransactionController::class, 'index'])->name('admin.exchange_transactions.index');
    Route::get('exchange_transactions/create', [ExchangeTransactionController::class, 'create'])->name('admin.exchange_transactions.create');
    Route::post('exchange_transactions/store', [ExchangeTransactionController::class, 'store'])->name('admin.exchange_transactions.store');
    Route::post('exchange_transactions/get_status', [ExchangeTransactionController::class, 'get_status'])->name('admin.exchange_transactions.get_status');
    Route::post('exchange_transactions/ajax_search', [ExchangeTransactionController::class, 'ajax_search'])->name('admin.exchange_transactions.ajax_search');
    /* end exchange_transactions */


    /* begin sales_header */
    Route::get('sales_header/index', [SalesOrderHeaderController::class, 'index'])->name('admin.sales_header.index');
    Route::post('sales_header/store', [SalesOrderHeaderController::class, 'store'])->name('admin.sales_header.store');
    Route::get('sales_header/delete/{id}', [SalesOrderHeaderController::class, 'delete'])->name('admin.sales_header.delete');
    Route::post('sales_header/ajax_search', [SalesOrderHeaderController::class, 'ajax_search'])->name('admin.sales_header.ajax_search');
    Route::post('sales_header/get_item_unit', [SalesOrderHeaderController::class, 'get_item_unit'])->name('admin.sales_header.get_item_unit');
    Route::post('sales_header/get_item_batch', [SalesOrderHeaderController::class, 'get_item_batch'])->name('admin.sales_header.get_item_batch');
    Route::post('sales_header/get_item_price', [SalesOrderHeaderController::class, 'get_item_price'])->name('admin.sales_header.get_item_price');
    Route::post('sales_header/add_new_item', [SalesOrderHeaderController::class, 'add_new_item'])->name('admin.sales_header.add_new_item');
    Route::post('sales_header/add_new_item_row', [SalesOrderHeaderController::class, 'add_new_item_row'])->name('admin.sales_header.add_new_item_row');
    Route::post('sales_header/add_to_pill', [SalesOrderHeaderController::class, 'add_to_pill'])->name('admin.sales_header.add_to_pill');
    Route::post('sales_header/pill_mirror', [SalesOrderHeaderController::class, 'pill_mirror'])->name('admin.sales_header.pill_mirror');
    Route::post('sales_header/load_pill_adding_items_modal', [SalesOrderHeaderController::class, 'load_pill_adding_items_modal'])->name('admin.sales_header.load_pill_adding_items_modal');
    Route::post('sales_header/store_item', [SalesOrderHeaderController::class, 'store_item'])->name('admin.sales_header.store_item');
    Route::post('sales_header/remove_item', [SalesOrderHeaderController::class, 'remove_item'])->name('admin.sales_header.remove_item');
    Route::post('sales_header/approve_pill/{id}', [SalesOrderHeaderController::class, 'approve_pill'])->name('admin.sales_header.approve_pill');
    Route::post('sales_header/check_shift_and_reload_money', [SalesOrderHeaderController::class, 'check_shift_and_reload_money'])->name('admin.sales_header.check_shift_and_reload_money');
    Route::post('sales_header/create_pill', [SalesOrderHeaderController::class, 'create_pill'])->name('admin.sales_header.create_pill');
    Route::post('sales_header/add_to_customer', [SalesOrderHeaderController::class, 'add_to_customer'])->name('admin.sales_header.add_to_customer');
    Route::post('sales_header/get_added_customer', [SalesOrderHeaderController::class, 'get_added_customer'])->name('admin.sales_header.get_added_customer');
    Route::get('sales_header/printA4/{id}/{type}', [SalesOrderHeaderController::class, 'printA4'])->name('admin.sales_header.printA4');
    /* end sales_header */

    /* begin sales_order_header_general_return */
    Route::get('sales_order_header_general_return/index', [SalesOrderHeaderGeneralReturnController::class, 'index'])->name('admin.sales_order_header_general_return.index');
    Route::post('sales_order_header_general_return/store', [SalesOrderHeaderGeneralReturnController::class, 'store'])->name('admin.sales_order_header_general_return.store');
    Route::get('sales_order_header_general_return/delete/{id}', [SalesOrderHeaderGeneralReturnController::class, 'delete'])->name('admin.sales_order_header_general_return.delete');
    Route::post('sales_order_header_general_return/ajax_search', [SalesOrderHeaderGeneralReturnController::class, 'ajax_search'])->name('admin.sales_order_header_general_return.ajax_search');
    Route::post('sales_order_header_general_return/get_item_unit', [SalesOrderHeaderGeneralReturnController::class, 'get_item_unit'])->name('admin.sales_order_header_general_return.get_item_unit');
    Route::post('sales_order_header_general_return/get_item_batch', [SalesOrderHeaderGeneralReturnController::class, 'get_item_batch'])->name('admin.sales_order_header_general_return.get_item_batch');
    Route::post('sales_order_header_general_return/get_item_price', [SalesOrderHeaderGeneralReturnController::class, 'get_item_price'])->name('admin.sales_order_header_general_return.get_item_price');
    Route::post('sales_order_header_general_return/add_new_item', [SalesOrderHeaderGeneralReturnController::class, 'add_new_item'])->name('admin.sales_order_header_general_return.add_new_item');
    Route::post('sales_order_header_general_return/add_new_item_row', [SalesOrderHeaderGeneralReturnController::class, 'add_new_item_row'])->name('admin.sales_order_header_general_return.add_new_item_row');
    Route::post('sales_order_header_general_return/add_to_pill', [SalesOrderHeaderGeneralReturnController::class, 'add_to_pill'])->name('admin.sales_order_header_general_return.add_to_pill');
    Route::post('sales_order_header_general_return/load_pill_adding_items_modal', [SalesOrderHeaderGeneralReturnController::class, 'load_pill_adding_items_modal'])->name('admin.sales_order_header_general_return.load_pill_adding_items_modal');
    Route::post('sales_order_header_general_return/store_item', [SalesOrderHeaderGeneralReturnController::class, 'store_item'])->name('admin.sales_order_header_general_return.store_item');
    Route::post('sales_order_header_general_return/remove_item', [SalesOrderHeaderGeneralReturnController::class, 'remove_item'])->name('admin.sales_order_header_general_return.remove_item');
    Route::post('sales_order_header_general_return/approve_pill/{id}', [SalesOrderHeaderGeneralReturnController::class, 'approve_pill'])->name('admin.sales_order_header_general_return.approve_pill');
    Route::post('sales_order_header_general_return/check_shift_and_reload_money', [SalesOrderHeaderGeneralReturnController::class, 'check_shift_and_reload_money'])->name('admin.sales_order_header_general_return.check_shift_and_reload_money');
    Route::post('sales_order_header_general_return/create_pill', [SalesOrderHeaderGeneralReturnController::class, 'create_pill'])->name('admin.sales_order_header_general_return.create_pill');
    Route::get('sales_order_header_general_return/printA4/{id}/{type}', [SalesOrderHeaderGeneralReturnController::class, 'printA4'])->name('admin.sales_order_header_general_return.printA4');
    /* end sales_order_header_general_return */


    /* begin items_in_stores */
    Route::get('items_in_stores/index', [ItemInStoreController::class, 'index'])->name('admin.items_in_stores.index');
    Route::post('items_in_stores/ajax_search', [ItemInStoreController::class, 'ajax_search'])->name('admin.items_in_stores.ajax_search');
    /* end items_in_stores */

    /* begin reports */
    Route::get('reports/index', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('reports/supplier_account_report', [ReportController::class, 'supplier_account_report'])->name('admin.reports.supplier_account_report');
    Route::post('reports/supplier_account_report', [ReportController::class, 'supplier_account_report'])->name('admin.reports.supplier_account_report');
    Route::get('reports/customer_account_report', [ReportController::class, 'customer_account_report'])->name('admin.reports.customer_account_report');
    Route::post('reports/customer_account_report', [ReportController::class, 'customer_account_report'])->name('admin.reports.customer_account_report');
    /* end reports */

    /* begin inv_stores_inventory */
    Route::get('inv_stores_inventory/index', [InvStoreInventoryController::class, 'index'])->name('admin.inv_stores_inventory.index');
    Route::get('inv_stores_inventory/create', [InvStoreInventoryController::class, 'create'])->name('admin.inv_stores_inventory.create');
    Route::post('inv_stores_inventory/store', [InvStoreInventoryController::class, 'store'])->name('admin.inv_stores_inventory.store');
    Route::get('inv_stores_inventory/edit/{id}', [InvStoreInventoryController::class, 'edit'])->name('admin.inv_stores_inventory.edit');
    Route::post('inv_stores_inventory/update/{id}', [InvStoreInventoryController::class, 'update'])->name('admin.inv_stores_inventory.update');
    Route::post('inv_stores_inventory/ajax_search', [InvStoreInventoryController::class, 'ajax_search'])->name('admin.inv_stores_inventory.ajax_search');
    Route::get('inv_stores_inventory/delete/{id}', [InvStoreInventoryController::class, 'delete'])->name('admin.inv_stores_inventory.delete');
    Route::get('inv_stores_inventory/details/{id}', [InvStoreInventoryController::class, 'details'])->name('admin.inv_stores_inventory.details');
    Route::post('inv_stores_inventory/create_detail/{id}', [InvStoreInventoryController::class, 'create_detail'])->name('admin.inv_stores_inventory.create_detail');
    Route::post('inv_stores_inventory/load_modal_update_batch', [InvStoreInventoryController::class, 'load_modal_update_batch'])->name('admin.inv_stores_inventory.load_modal_update_batch');
    Route::post('inv_stores_inventory/edit_detail', [InvStoreInventoryController::class, 'edit_detail'])->name('admin.inv_stores_inventory.edit_detail');
    Route::get('inv_stores_inventory/delete_detail/{detail_id}/{header_id}', [InvStoreInventoryController::class, 'delete_detail'])->name('admin.inv_stores_inventory.delete_detail');
    Route::get('inv_stores_inventory/close_detail/{detail_id}/{header_id}', [InvStoreInventoryController::class, 'close_detail'])->name('admin.inv_stores_inventory.close_detail');
    Route::get('inv_stores_inventory/close_header/{header_id}', [InvStoreInventoryController::class, 'close_header'])->name('admin.inv_stores_inventory.close_header');
    Route::get('inv_stores_inventory/printA4/{header_id}', [InvStoreInventoryController::class, 'printA4'])->name('admin.inv_stores_inventory.printA4');
    /* end inv_stores_inventory */

    /* begin roles */
    Route::get('roles/index', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('roles/store', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('roles/edit/{id}', [RoleController::class, 'edit'])->name('admin.roles.edit');
    Route::post('roles/update/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::get('roles/delete/{id}', [RoleController::class, 'destroy'])->name('admin.roles.delete');
    Route::post('roles/ajax_search', [RoleController::class, 'ajax_search'])->name('admin.roles.ajax_search');
    Route::get('roles/details/{id}', [RoleController::class, 'details'])->name('admin.roles.details');
    Route::post('roles/store_permission_main_menu', [RoleController::class, 'store_permission_main_menu'])->name('admin.roles.store_permission_main_menu');
    Route::get('roles/delete_permission_main_menu/{role_id}/{main_id}', [RoleController::class, 'delete_permission_main_menu'])->name('admin.roles.delete_permission_main_menu');
    Route::get('roles/main_menu_details/{role_id}/{main_id}', [RoleController::class, 'main_menu_details'])->name('admin.roles.main_menu_details');
    Route::post('roles/store_permission_sub_menu', [RoleController::class, 'store_permission_sub_menu'])->name('admin.roles.store_permission_sub_menu');
    Route::post('roles/store_permission_sub_menu_control', [RoleController::class, 'store_permission_sub_menu_control'])->name('admin.roles.store_permission_sub_menu_control');
    Route::post('roles/load_control_modal', [RoleController::class, 'load_control_modal'])->name('admin.roles.load_control_modal');
    Route::get('roles/delete_permission_sub_menu/{role_id}/{main_id}/{sub_id}', [RoleController::class, 'delete_permission_sub_menu'])->name('admin.roles.delete_permission_sub_menu');
    Route::get('roles/delete_permission_sub_menu_control/{role_id}/{main_id}/{sub_id}/{control_id}', [RoleController::class, 'delete_permission_sub_menu_control'])->name('admin.roles.delete_permission_sub_menu_control');
    Route::get('roles/sub_menu_details/{role_id}/{main_id}/{sub_id}', [RoleController::class, 'sub_menu_details'])->name('admin.roles.sub_menu_details');

    /* end roles */

    /* begin roles_main_menu */
    Route::get('roles_main_menu/index', [RoleMainMenuController::class, 'index'])->name('admin.roles_main_menu.index');
    Route::get('roles_main_menu/create', [RoleMainMenuController::class, 'create'])->name('admin.roles_main_menu.create');
    Route::post('roles_main_menu/store', [RoleMainMenuController::class, 'store'])->name('admin.roles_main_menu.store');
    Route::get('roles_main_menu/edit/{id}', [RoleMainMenuController::class, 'edit'])->name('admin.roles_main_menu.edit');
    Route::post('roles_main_menu/update/{id}', [RoleMainMenuController::class, 'update'])->name('admin.roles_main_menu.update');
    Route::get('roles_main_menu/delete/{id}', [RoleMainMenuController::class, 'destroy'])->name('admin.roles_main_menu.delete');
    Route::post('roles_main_menu/ajax_search', [RoleMainMenuController::class, 'ajax_search'])->name('admin.roles_main_menu.ajax_search');
    /* end roles_main_menu */

    /* begin roles_sub_menu */
    Route::get('roles_sub_menu/index', [RoleSubMenuController::class, 'index'])->name('admin.roles_sub_menu.index');
    Route::get('roles_sub_menu/create', [RoleSubMenuController::class, 'create'])->name('admin.roles_sub_menu.create');
    Route::post('roles_sub_menu/store', [RoleSubMenuController::class, 'store'])->name('admin.roles_sub_menu.store');
    Route::get('roles_sub_menu/edit/{id}', [RoleSubMenuController::class, 'edit'])->name('admin.roles_sub_menu.edit');
    Route::post('roles_sub_menu/update/{id}', [RoleSubMenuController::class, 'update'])->name('admin.roles_sub_menu.update');
    Route::get('roles_sub_menu/delete/{id}', [RoleSubMenuController::class, 'destroy'])->name('admin.roles_sub_menu.delete');
    Route::get('roles_sub_menu/details/{id}', [RoleSubMenuController::class, 'details'])->name('admin.roles_sub_menu.details');
    Route::get('roles_sub_menu/create_control/{id}', [RoleSubMenuController::class, 'create_control'])->name('admin.roles_sub_menu.create_control');
    Route::get('roles_sub_menu/edit_control/{id}', [RoleSubMenuController::class, 'edit_control'])->name('admin.roles_sub_menu.edit_control');
    Route::get('roles_sub_menu/delete_control/{id}', [RoleSubMenuController::class, 'delete_control'])->name('admin.roles_sub_menu.delete_control');
    Route::post('roles_sub_menu/store_control/{id}', [RoleSubMenuController::class, 'store_control'])->name('admin.roles_sub_menu.store_control');
    Route::post('roles_sub_menu/update_control/{id}', [RoleSubMenuController::class, 'update_control'])->name('admin.roles_sub_menu.update_control');
    Route::post('roles_sub_menu/ajax_search', [RoleSubMenuController::class, 'ajax_search'])->name('admin.roles_sub_menu.ajax_search');
    /* end roles_sub_menu */
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function(){
    Route::get('login', [LoginController::class, 'showLogin'])->name('admin.show');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});
