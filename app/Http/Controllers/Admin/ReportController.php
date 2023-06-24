<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Delegate;
use App\Models\InvoiceOrderDetail;
use App\Models\InvoiceOrderHeader;
use App\Models\PurchaseOrderHeader;
use App\Models\SalesOrderHeader;
use App\Models\Supplier;
use App\Models\Admin;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\AdminPanelSetting;
use App\Models\InvItemCard;
use App\Models\InvUnit;
use App\Models\MoveType;
use App\Models\Person;
use App\Models\Store;
use App\Models\TreasuryTransaction;
use Exception;

class ReportController extends Controller
{
    public function index()
    {
        //
        try {

        }
        catch (Exception $e) {

        }
    }

    public function supplier_account_report(Request $request)
    {
        if (check_control_menu_role('التقارير', 'كشف حساب مورد' , 'عرض') == true || check_control_menu_role('التقارير', 'كشف حساب مورد' , 'طباعة') == true) {
            try {
                $com_code = auth()->user()->com_code;
                if ($_POST) {
                    if (check_control_menu_role('التقارير', 'كشف حساب مورد' , 'طباعة') == true) {
                        $supplier = Person::where(['id' => $request->code])->get(['first_name', 'last_name', 'date', 'id', 'account_number', 'phone'])->first();
                        if (!empty($suppliers)) {
                            return redirect()->back()->with('error', 'لا يمكن الوصول الا البيانات المطلوبة');
                        }
                        $supplier['report_type'] = $request->report_type;
                        $supplier['supplier_code'] = Supplier::where(['person_id' => $request->code])->value('supplier_code');
                        $supplier['start_balance'] = Account::where(['account_number' => $supplier['account_number'], 'com_code' => $com_code])->value('start_balance');
                        $supplier['current_balance'] = Account::where(['account_number' => $supplier['account_number'], 'com_code' => $com_code])->value('current_balance');

                        if ($supplier['report_type'] == 1) {
                            $all_purchase = PurchaseOrderHeader::where(['supplier_code' => $supplier['supplier_code'], 'com_code' => $com_code])->get('invoice_id');
                            $supplier['all_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->count();
                            $supplier['all_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->sum('total_cost');
                            $supplier['all_return_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->count();
                            $supplier['all_return_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->sum('total_cost');
                            $supplier['all_exchange'] = TreasuryTransaction::where(['account_number' => $supplier['account_number'], 'com_code' => $com_code, 'transaction_type' => 1])->sum('money');
                            $supplier['all_collection'] = TreasuryTransaction::where(['account_number' => $supplier['account_number'], 'com_code' => $com_code, 'transaction_type' => 2])->sum('money');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();
                            return view('admin.reports.print_supplier_report_A4', ['data' => $supplier, 'systemData' => $systemData]);
                        }
                        else if ($supplier['report_type'] == 2) {
                            $supplier['from_date'] = $request->from_date;
                            $supplier['to_date'] = $request->to_date;
                            $all_purchase = PurchaseOrderHeader::where(['supplier_code' => $supplier['supplier_code'], 'com_code' => $com_code])->get('invoice_id');
                            $supplier['all_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->count();
                            $supplier['all_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->sum('total_cost');
                            $supplier['all_return_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->count();
                            $supplier['all_return_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->sum('total_cost');
                            $supplier['all_exchange'] = TreasuryTransaction::where(['account_number' => $supplier['account_number'], 'com_code' => $com_code, 'transaction_type' => 1])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->sum('money');
                            $supplier['all_collection'] = TreasuryTransaction::where(['account_number' => $supplier['account_number'], 'com_code' => $com_code, 'transaction_type' => 2])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->sum('money');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();

                            $sales_pill = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->get();
                            if(!empty($sales_pill)) {
                                foreach ($sales_pill as $pill) {
                                    $pill['store_id'] = PurchaseOrderHeader::where('invoice_id', $pill['id'])->value('store_id');
                                    $pill['store_name'] = Store::where('id', $pill['store_id'])->value('name');
                                    $pill['details'] = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                                    if (!empty($pill['details'])) {
                                        foreach($pill['details'] as $s) {
                                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                                            $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                                        }
                                    }
                                }
                            }

                            $sales_return_pill = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->get();
                            if(!empty($sales_return_pill)) {
                                foreach ($sales_return_pill as $pill) {
                                    $pill['store_id'] = PurchaseOrderHeader::where('invoice_id', $pill['id'])->value('store_id');
                                    $pill['store_name'] = Store::where('id', $pill['store_id'])->value('name');
                                    $pill['details'] = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                                    if (!empty($pill['details'])) {
                                        foreach($pill['details'] as $s) {
                                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                                            $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                                        }
                                    }
                                }
                            }

                            $transactions = TreasuryTransaction::where(['account_number' => $supplier['account_number'], 'com_code' => $com_code])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->get();
                            if (!empty($transactions)) {
                                foreach ($transactions as $tran) {
                                    $tran['type_name'] = MoveType::where('id', $tran['move_type'])->value('name');
                                }
                            }

                            return view('admin.reports.print_supplier_report_A4', ['data' => $supplier, 'systemData' => $systemData, 'sales_pill' => $sales_pill, 'sales_return_pill' => $sales_return_pill, 'transactions' => $transactions]);
                        }
                        else if ($supplier['report_type'] == 3) {
                            $supplier['from_date'] = $request->from_date;
                            $supplier['to_date'] = $request->to_date;
                            $all_purchase = PurchaseOrderHeader::where(['supplier_code' => $supplier['supplier_code'], 'com_code' => $com_code])->get('invoice_id');
                            $supplier['all_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->count();
                            $supplier['all_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->sum('total_cost');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();

                            $sales_pill = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->get();
                            if(!empty($sales_pill)) {
                                foreach ($sales_pill as $pill) {
                                    $pill['store_id'] = PurchaseOrderHeader::where('invoice_id', $pill['id'])->value('store_id');
                                    $pill['store_name'] = Store::where('id', $pill['store_id'])->value('name');
                                    $pill['details'] = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                                    if (!empty($pill['details'])) {
                                        foreach($pill['details'] as $s) {
                                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                                            $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                                        }
                                    }
                                }
                            }
                            return view('admin.reports.print_supplier_purchase_report_A4', ['data' => $supplier, 'systemData' => $systemData, 'sales_pill' => $sales_pill]);
                        }
                        else if ($supplier['report_type'] == 4) {
                            $supplier['from_date'] = $request->from_date;
                            $supplier['to_date'] = $request->to_date;
                            $all_purchase = PurchaseOrderHeader::where(['supplier_code' => $supplier['supplier_code'], 'com_code' => $com_code])->get('invoice_id');
                            $supplier['all_return_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->count();
                            $supplier['all_return_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->sum('total_cost');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();

                            $sales_return_pill = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 1, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->get();
                            if(!empty($sales_return_pill)) {
                                foreach ($sales_return_pill as $pill) {
                                    $pill['store_id'] = PurchaseOrderHeader::where('invoice_id', $pill['id'])->value('store_id');
                                    $pill['store_name'] = Store::where('id', $pill['store_id'])->value('name');
                                    $pill['details'] = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                                    if (!empty($pill['details'])) {
                                        foreach($pill['details'] as $s) {
                                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                                            $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                                        }
                                    }
                                }
                            }
                            return view('admin.reports.print_supplier_purchase_return_report_A4', ['data' => $supplier, 'systemData' => $systemData, 'sales_return_pill' => $sales_return_pill]);
                        }

                        else if ($supplier['report_type'] == 5) {
                            $supplier['from_date'] = $request->from_date;
                            $supplier['to_date'] = $request->to_date;
                            $supplier['all_exchange'] = TreasuryTransaction::where(['account_number' => $supplier['account_number'], 'com_code' => $com_code, 'transaction_type' => 1])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->sum('money');
                            $supplier['all_collection'] = TreasuryTransaction::where(['account_number' => $supplier['account_number'], 'com_code' => $com_code, 'transaction_type' => 2])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->sum('money');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();
                            $exchange_transactions = TreasuryTransaction::where(['account_number' => $supplier['account_number'], 'transaction_type' => 1, 'com_code' => $com_code])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->get();
                            if (!empty($exchange_transactions)) {
                                foreach ($exchange_transactions as $tran) {
                                    $tran['type_name'] = MoveType::where('id', $tran['move_type'])->value('name');
                                }
                            }

                            $collection_transactions = TreasuryTransaction::where(['account_number' => $supplier['account_number'], 'transaction_type' => 2, 'com_code' => $com_code])->where('date', '>=', $supplier['from_date'])->where('date', '<=', $supplier['to_date'])->get();
                            if (!empty($collection_transactions)) {
                                foreach ($collection_transactions as $tran) {
                                    $tran['type_name'] = MoveType::where('id', $tran['move_type'])->value('name');
                                }
                            }

                            return view('admin.reports.print_supplier_transaction_report_A4', ['data' => $supplier, 'systemData' => $systemData, 'exchange_transactions' => $exchange_transactions, 'collection_transactions' => $collection_transactions]);
                        }
                    }
                    else {
                        return redirect()->back();
                    }
                }
                else {
                    if (check_control_menu_role('التقارير', 'كشف حساب مورد' , 'عرض') == true) {
                        $suppliers = Person::where(['person_type' => 2, 'com_code' => $com_code])->get(['first_name', 'last_name', 'date', 'id']);
                        return view('admin.reports.supplier_account_report', ['suppliers' => $suppliers]);
                    }
                    else {
                        return redirect()->back();
                    }
                }

            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }

    }


    public function customer_account_report(Request $request)
    {
        if (check_control_menu_role('التقارير', 'كشف حساب عميل' , 'عرض') == true || check_control_menu_role('التقارير', 'كشف حساب عميل' , 'طباعة') == true) {
            try {
                $com_code = auth()->user()->com_code;
                if ($_POST) {
                    if (check_control_menu_role('التقارير', 'كشف حساب عميل' , 'طباعة') == true) {
                        $customer = Person::where(['id' => $request->code])->get(['first_name', 'last_name', 'date', 'id', 'account_number', 'phone'])->first();
                        if (!empty($customers)) {
                            return redirect()->back()->with('error', 'لا يمكن الوصول الا البيانات المطلوبة');
                        }
                        $customer['report_type'] = $request->report_type;
                        $customer['customer_code'] = Customer::where(['person_id' => $request->code])->value('customer_code');
                        $customer['start_balance'] = Account::where(['account_number' => $customer['account_number'], 'com_code' => $com_code])->value('start_balance');
                        $customer['current_balance'] = Account::where(['account_number' => $customer['account_number'], 'com_code' => $com_code])->value('current_balance');

                        if ($customer['report_type'] == 1) {
                            $all_purchase = SalesOrderHeader::where(['customer_code' => $customer['customer_code'], 'com_code' => $com_code])->get('invoice_id');
                            $customer['all_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 1, 'com_code' => $com_code])->count();
                            $customer['all_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 1, 'com_code' => $com_code])->sum('total_cost');
                            $customer['all_return_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->count();
                            $customer['all_return_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->sum('total_cost');
                            $customer['all_exchange'] = TreasuryTransaction::where(['account_number' => $customer['account_number'], 'com_code' => $com_code, 'transaction_type' => 1])->sum('money');
                            $customer['all_collection'] = TreasuryTransaction::where(['account_number' => $customer['account_number'], 'com_code' => $com_code, 'transaction_type' => 2])->sum('money');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();
                            return view('admin.reports.print_customer_report_A4', ['data' => $customer, 'systemData' => $systemData]);
                        }
                        else if ($customer['report_type'] == 2) {
                            $customer['from_date'] = $request->from_date;
                            $customer['to_date'] = $request->to_date;
                            $all_purchase = SalesOrderHeader::where(['customer_code' => $customer['customer_code'], 'com_code' => $com_code])->get('invoice_id');
                            $customer['all_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->count();
                            $customer['all_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->sum('total_cost');
                            $customer['all_return_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->count();
                            $customer['all_return_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->sum('total_cost');
                            $customer['all_exchange'] = TreasuryTransaction::where(['account_number' => $customer['account_number'], 'com_code' => $com_code, 'transaction_type' => 1])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->sum('money');
                            $customer['all_collection'] = TreasuryTransaction::where(['account_number' => $customer['account_number'], 'com_code' => $com_code, 'transaction_type' => 2])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->sum('money');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();

                            $sales_pill = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->get();
                            if(!empty($sales_pill)) {
                                foreach ($sales_pill as $pill) {
                                    $pill['details'] = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                                    if (!empty($pill['details'])) {
                                        foreach($pill['details'] as $s) {
                                            $s['store_name'] = Store::where('id', $s['store_id'])->value('name');
                                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                                            $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                                        }
                                    }
                                }
                            }

                            $sales_return_pill = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->get();
                            if(!empty($sales_return_pill)) {
                                foreach ($sales_return_pill as $pill) {
                                    $pill['details'] = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                                    if (!empty($pill['details'])) {
                                        foreach($pill['details'] as $s) {
                                            $s['store_name'] = Store::where('id', $s['store_id'])->value('name');
                                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                                            $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                                        }
                                    }
                                }
                            }

                            $transactions = TreasuryTransaction::where(['account_number' => $customer['account_number'], 'com_code' => $com_code])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->get();
                            if (!empty($transactions)) {
                                foreach ($transactions as $tran) {
                                    $tran['type_name'] = MoveType::where('id', $tran['move_type'])->value('name');
                                }
                            }

                            return view('admin.reports.print_customer_report_A4', ['data' => $customer, 'systemData' => $systemData, 'sales_pill' => $sales_pill, 'sales_return_pill' => $sales_return_pill, 'transactions' => $transactions]);
                        }
                        else if ($customer['report_type'] == 3) {
                            $customer['from_date'] = $request->from_date;
                            $customer['to_date'] = $request->to_date;
                            $all_purchase = SalesOrderHeader::where(['customer_code' => $customer['customer_code'], 'com_code' => $com_code])->get('invoice_id');
                            $customer['all_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->count();
                            $customer['all_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->sum('total_cost');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();

                            $sales_pill = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 1, 'com_code' => $com_code])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->get();
                            if(!empty($sales_pill)) {
                                foreach ($sales_pill as $pill) {
                                    $pill['details'] = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                                    if (!empty($pill['details'])) {
                                        foreach($pill['details'] as $s) {
                                            $s['store_name'] = Store::where('id', $s['store_id'])->value('name');
                                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                                            $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                                        }
                                    }
                                }
                            }
                            return view('admin.reports.print_customer_purchase_report_A4', ['data' => $customer, 'systemData' => $systemData, 'sales_pill' => $sales_pill]);
                        }
                        else if ($customer['report_type'] == 4) {
                            $customer['from_date'] = $request->from_date;
                            $customer['to_date'] = $request->to_date;
                            $all_purchase = SalesOrderHeader::where(['customer_code' => $customer['customer_code'], 'com_code' => $com_code])->get('invoice_id');
                            $customer['all_return_purchase_count'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->count();
                            $customer['all_return_purchase_cost'] = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->sum('total_cost');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();

                            $sales_return_pill = InvoiceOrderHeader::whereIn('id', $all_purchase)->where(['invoice_type' => 2, 'order_type' => 3, 'com_code' => $com_code])->orWhere('order_type', 2)->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->get();
                            if(!empty($sales_return_pill)) {
                                foreach ($sales_return_pill as $pill) {
                                    $pill['details'] = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                                    if (!empty($pill['details'])) {
                                        foreach($pill['details'] as $s) {
                                            $s['store_name'] = Store::where('id', $s['store_id'])->value('name');
                                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                                            $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                                        }
                                    }
                                }
                            }
                            return view('admin.reports.print_customer_purchase_return_report_A4', ['data' => $customer, 'systemData' => $systemData, 'sales_return_pill' => $sales_return_pill]);
                        }

                        else if ($customer['report_type'] == 5) {
                            $customer['from_date'] = $request->from_date;
                            $customer['to_date'] = $request->to_date;
                            $customer['all_exchange'] = TreasuryTransaction::where(['account_number' => $customer['account_number'], 'com_code' => $com_code, 'transaction_type' => 1])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->sum('money');
                            $customer['all_collection'] = TreasuryTransaction::where(['account_number' => $customer['account_number'], 'com_code' => $com_code, 'transaction_type' => 2])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->sum('money');
                            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();
                            $exchange_transactions = TreasuryTransaction::where(['account_number' => $customer['account_number'], 'transaction_type' => 1, 'com_code' => $com_code])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->get();
                            if (!empty($exchange_transactions)) {
                                foreach ($exchange_transactions as $tran) {
                                    $tran['type_name'] = MoveType::where('id', $tran['move_type'])->value('name');
                                }
                            }

                            $collection_transactions = TreasuryTransaction::where(['account_number' => $customer['account_number'], 'transaction_type' => 2, 'com_code' => $com_code])->where('date', '>=', $customer['from_date'])->where('date', '<=', $customer['to_date'])->get();
                            if (!empty($collection_transactions)) {
                                foreach ($collection_transactions as $tran) {
                                    $tran['type_name'] = MoveType::where('id', $tran['move_type'])->value('name');
                                }
                            }

                            return view('admin.reports.print_customer_transaction_report_A4', ['data' => $customer, 'systemData' => $systemData, 'exchange_transactions' => $exchange_transactions, 'collection_transactions' => $collection_transactions]);
                        }
                    }
                    else {
                        return redirect()->back();
                    }
                }
                else {
                    if (check_control_menu_role('التقارير', 'كشف حساب مورد' , 'عرض') == true) {
                        $customers = Person::where(['person_type' => 1, 'com_code' => $com_code])->get(['first_name', 'last_name', 'date', 'id']);
                        return view('admin.reports.customer_account_report', ['customers' => $customers]);
                    }
                    else {
                        return redirect()->back();
                    }
                }
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }
    }

}
