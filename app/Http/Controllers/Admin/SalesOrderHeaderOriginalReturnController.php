<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\InvoiceOrderHeader;
use App\Models\Admin;
use App\Models\Person;
use App\Models\AdminPanelSetting;
use App\Models\AdminShift;
use App\Models\Customer;
use App\Models\Delegate;
use App\Models\InvItemCard;
use App\Models\InvUnit;
use App\Models\InvoiceOrderDetail;
use App\Models\Store;
use App\Models\Treasury;
use App\Models\TreasuryTransaction;
use App\Models\InvItemCardBatch;
use App\Models\InvItemCardMovement;
use App\Models\OriginalReturnInvoice;
use App\Models\SalesOrderHeader;
use Exception;

class SalesOrderHeaderOriginalReturnController extends Controller
{

    public function index()
    {
        //
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'عرض') == true) {
            try {
                $data = InvoiceOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_type' => 2, 'is_original_return' => 1])->orderBy('id' , 'desc')->paginate(PAGINATION_COUNT);
                if (!empty($data)) {
                    foreach ($data as $d) {
                        $d['customer_code'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('customer_code');
                        $d['delegate_code'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('delegate_code');
                        $d['sales_code'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('sales_code');
                        $d['auto_serial'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('auto_serial');
                        if ($d['customer_code'] != null) {
                            $person_id = Customer::where(['customer_code' => $d['customer_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                            $customer = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                            $d['customer_name'] = $customer->first_name . ' ' . $customer->last_name;
                        }
                        else {
                            $d['customer_name'] = 'لا يوجد';
                        }

                        if ($d['delegate_code'] != null) {
                            $person_id = Delegate::where(['delegate_code' => $d['delegate_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                            $delegate = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                            $d['delegate_name'] = $delegate->first_name . ' ' . $delegate->last_name;
                        }
                        else {
                            $d['delegate_name'] = 'لا يوجد';
                        }

                        $d['total_cost'] = OriginalReturnInvoice::where('invoice_order_id', $d['id'])->value('total_cost');
                    }
                }


                $com_code = auth()->user()->com_code;
                $customers = Person::where(['active' => 1, 'person_type' => 1, 'com_code' => $com_code])->get(['first_name', 'last_name', 'id']);
                foreach ($customers as $sup) {
                    $sup['customer_code'] = Customer::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('customer_code');
                    $sup['customer_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                }

                $delegates = Person::where(['active' => 1, 'person_type' => 3, 'com_code' => $com_code])->get(['first_name', 'last_name', 'id']);
                foreach ($delegates as $sup) {
                    $sup['delegate_code'] = Delegate::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('delegate_code');
                    $sup['delegate_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                }

                return view('admin.sales_order_header_original_return.index', ['data' => $data, 'customers' => $customers, 'delegates' => $delegates]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function create()
    {
        # code...
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'اضافة') == true) {
            try {
                $com_code = auth()->user()->com_code;

                $customers = Person::where(['active' => 1, 'person_type' => 1, 'com_code' => $com_code])->get(['first_name', 'last_name', 'id']);
                foreach ($customers as $sup) {
                    $sup['customer_code'] = Customer::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('customer_code');
                    $sup['customer_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                }


                $stores = Store::where(['active' => 1, 'com_code' => $com_code])->get(['name', 'id']);

                $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->get(['treasuries_id', 'id'])->first();
                if (empty($check_shift)) {
                    return Response()->json(['error' => 'انت لاتملك شفت حالي لعمل اضافة'], 404);
                }

                $check_shift['treasuries_name'] = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                $check_shift['treasuries_money'] = TreasuryTransaction::where(['shift_code' => $check_shift['id'], 'com_code' => $com_code])->sum('money');


                return view('admin.sales_order_header_original_return.create', ['customers' => $customers, 'stores' => $stores, 'check_shift' => $check_shift]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }

    }

    public function get_customer_pills(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $customer_code = $request->customer_code;

            $pills_in = SalesOrderHeader::where(['customer_code' => $customer_code, 'com_code' => $com_code])->get(['invoice_id']);
            $pills = InvoiceOrderHeader::whereIn('id', $pills_in)->where(['invoice_type' => 2, 'order_type' => 1])->get(['id', 'order_date', 'pill_code']);
            return view("admin.sales_order_header_original_return.get_customer_pills", ['pills' => $pills]);
        }
    }

    public function get_pill_details(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $pill_code = $request->pill_code;

            $pill = InvoiceOrderHeader::where(['pill_code' => $pill_code, 'invoice_type' => 2, 'order_type' => 1, 'com_code' => $com_code])->get()->first();
            $pill_details = array();
            $check_shift = array();
            if (!empty($pill)) {
                if ($pill['is_original_return'] == 0) {
                    if (!empty($pill)) {
                        $pill['tax_value'] = $pill['total_before_discount'] * $pill['tax_percent'] / 100;
                        $pill['total_after_tax'] = $pill['total_before_discount'] + $pill['tax_value'];
                        $pill['discount_percent'] = $pill['discount_value'] / $pill['total_after_tax'];

                        $pill['customer_code'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $pill['id']])->value('customer_code');
                        $pill['delegate_code'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $pill['id']])->value('delegate_code');
                        if ($pill['customer_code'] != null) {
                            $person_id = Customer::where(['customer_code' => $pill['customer_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                            $customer = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                            $pill['customer_name'] = $customer->first_name . ' ' . $customer->last_name;
                        }
                        if ($pill['delegate_code'] != null) {
                            $person_id = Delegate::where(['delegate_code' => $pill['delegate_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                            $customer = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                            $pill['delegate_name'] = $customer->first_name . ' ' . $customer->last_name;
                        }
                    }

                    $pill_details = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                    if (!empty($pill_details)) {
                        foreach ($pill_details as $s) {
                            $s['store_name'] = Store::where('id', $s['store_id'])->value('name');
                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                            $s['item_name'] = InvItemCard::where(['item_code' => $s['item_code'], 'com_code' => $com_code])->value('name');
                            $s['remain_quantity'] = $s['quantity'] - $s['rejected_quantity'];
                        }
                    }

                    $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->get(['treasuries_id', 'id'])->first();
                    $check_shift['treasuries_name'] = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                    $check_shift['treasuries_money'] = TreasuryTransaction::where(['shift_code' => $check_shift['id'], 'com_code' => $com_code])->sum('money');

                }
            }

            return view("admin.sales_order_header_original_return.get_pill_details", ['pill' => $pill, 'pill_details' => $pill_details, 'check_shift' => $check_shift]);
        }
    }

    public function approve_pill(Request $request, $id)
    {
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'اعتماد') == true) {
            try {
                # code...
                $com_code = auth()->user()->com_code;
                $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->first();
                if (empty($check_shift)) {
                    return redirect()->back()->with('error', 'انت لا تمتلك شفت لعمل اعتماد')->withInput();
                }

                $data = InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code, 'order_type' => 1, 'invoice_type' => 2])->get()->first();
                $data['customer_code'] = SalesOrderHeader::where(['invoice_id' => $id, 'com_code' => $com_code])->value('customer_code');
                $data['delegate_code'] = SalesOrderHeader::where(['invoice_id' => $id, 'com_code' => $com_code])->value('delegate_code');
                $data['commission_type'] = SalesOrderHeader::where(['invoice_id' => $id, 'com_code' => $com_code])->value('delegate_commission_type');
                $data['commission'] = SalesOrderHeader::where(['invoice_id' => $id, 'com_code' => $com_code])->value('delegate_commission');

                if (empty($data)) {
                    return redirect()->back()->with('error', 'لا توجد بيانات كهذه');
                }
                if ($data['is_original_return'] == 1) {
                    return redirect()->back()->with('error', 'الفاتورة تم ارجاعها من قبل');
                }
                if ($data['customer_code'] == '' && $request->pill_type == 2) {
                    return redirect()->back()->with('error', 'الفاتورة التي لا تحتوي على عميل يجب ان تكون كاش');
                }


                $updateInvoice['is_original_return'] = 1;
                $updateInvoice['updated_by'] = auth()->user()->id;
                $updateInvoice['updated_at'] = date('Y-m-d H:i:s');

                $flag = InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code, 'order_type' => 1, 'invoice_type' => 2])->update($updateInvoice);

                if ($flag) {
                    $insertReturnInvoice['invoice_order_id'] = $data['id'];
                    $insertReturnInvoice['pill_type'] = $request->pill_type;
                    $insertReturnInvoice['what_paid'] = $request->what_paid;
                    $insertReturnInvoice['what_remain'] = $request->what_remain;
                    $insertReturnInvoice['total_cost'] = $request->total_pill;
                    $insertReturnInvoice['money_for_account'] = $request->total_pill * (-1);
                    $insertReturnInvoice['added_by'] = auth()->user()->id;
                    $insertReturnInvoice['created_at'] = date('Y-m-d H:i:s');
                    $insertReturnInvoice['return_date'] = date('Y-m-d');
                    $insertReturnInvoice['com_code'] = $com_code;

                    $flag = OriginalReturnInvoice::create($insertReturnInvoice);


                    if ($data['delegate_code'] != '' && $data['delegate_code'] != null) {
                        if ($data['commission_type'] == 1) { // نسبة
                            $data['money_for_delegate'] = $request->total_pill * ($data['commission'] / 100);
                        }
                        else if ($data['commission_type'] == 2) { // قيمة
                            $commission = $data['commission'] / $data['total_cost'];
                            $data['money_for_delegate'] = $request->total_pill * $commission;
                        }

                        $person_id = Delegate::where(['delegate_code' => $data['delegate_code'], 'com_code' => $com_code])->value('person_id');
                        $data['delegate_account_number'] = Person::where(['id' => $person_id,'com_code' => $com_code])->value('account_number');
                        $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                        $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                        $data['delegate_name'] = $first_name . ' ' . $last_name;

                        // change the delegate current balance in accounts
                        $get_current = Account::where(['account_number' => $data['delegate_account_number'], 'com_code' => $com_code])->value('current_balance');
                        $update_account['current_balance'] = $get_current + $data['money_for_delegate'];
                        Account::where(['account_number' => $data['delegate_account_number'], 'com_code' => $com_code])->update($update_account);
                    }

                    if ($data['customer_code'] != '' && $data['customer_code'] != null) {
                        $person_id = Customer::where(['customer_code' => $data['customer_code'], 'com_code' => $com_code])->value('person_id');
                        $data['account_number'] = Person::where(['id' => $person_id,'com_code' => $com_code])->value('account_number');
                        $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                        $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                        $data['customer_name'] = $first_name . ' ' . $last_name;

                        // change the customer current balance in accounts
                        $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                        $update_account['current_balance'] = $get_current - $request->total_pill;
                        Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);
                    }

                    if ($request->what_paid > 0) {

                        $max_transaction_code = TreasuryTransaction::where(['transaction_type' => 1,'com_code' => $com_code])->max('transaction_code');
                        if (empty($max_transaction_code)) {
                            $insertTransaction['transaction_code'] = 1;
                        }
                        else {
                            $insertTransaction['transaction_code'] = $max_transaction_code + 1;
                        }

                        $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->first();
                        if (empty($check_shift)) {
                            return redirect()->back()->with('error', 'تم اغلاق الشفت الحالي')->withInput();
                        }
                        else {
                            $insertTransaction['shift_code'] = $check_shift['id'];
                        }

                        $last_exchange_arrive = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('last_exchange_arrive');


                        if (empty($last_exchange_arrive) && $last_exchange_arrive != 0) {
                            return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                        }
                        else {
                            $insertTransaction['last_arrive'] = $last_exchange_arrive + 1;
                        }

                        //صرف نظير مرتجع مبيعات
                        $insertTransaction['move_type'] = 6;
                        // Account number will be like the account number for the customer in the salesHeader
                        $insertTransaction['account_number'] = $data['account_number'];
                        $insertTransaction['transaction_type'] = 1;
                        $insertTransaction['money'] = $request->what_paid * (-1);
                        $insertTransaction['is_approved'] = 1;
                        $insertTransaction['invoice_id'] = $id;
                        $insertTransaction['treasuries_id'] = $check_shift['treasuries_id'];
                        $insertTransaction['move_date'] = date('Y-m-d');


                        if ($data['customer_code'] != '' && $data['customer_code'] != null) {
                            $insertTransaction['byan'] = 'صرف نظير مرتجع مبيعات من العميل' . ' ' . $data['customer_name'] . ' ' . 'فاتورة رقم ' . $id;
                            $insertTransaction['is_account'] = 1;
                            $insertTransaction['money_for_account'] = $request->what_paid * (1);
                        }
                        else {
                            $insertTransaction['byan'] = 'صرف نظير مرتجع مبيعات بدون عميل';
                            $insertTransaction['is_account'] = 0;
                        }

                        $insertTransaction['added_by'] = auth()->user()->id;
                        $insertTransaction['com_code'] = $com_code;
                        $insertTransaction['created_at'] = date('Y-m-d H:i:s');

                        $flag = TreasuryTransaction::create($insertTransaction);

                        if($flag) {
                            $update_treasuries['last_exchange_arrive'] = $last_exchange_arrive + 1;
                            Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->update($update_treasuries);

                            if ($data['customer_code'] != '' && $data['customer_code'] != null) {
                                // change the customer current balance in accounts
                                $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                                $update_account['current_balance'] = $get_current + $request->what_paid;
                                Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);
                            }
                        }
                    }

                    if ($request->what_remain > 0) {
                        $max_transaction_code = TreasuryTransaction::where(['transaction_type' => 3,'com_code' => $com_code])->max('transaction_code');
                        if (empty($max_transaction_code)) {
                            $insertTransaction['transaction_code'] = 1;
                        }
                        else {
                            $insertTransaction['transaction_code'] = $max_transaction_code + 1;
                        }

                        $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'treasuries_id' => $request->treasuries_id, 'com_code' => $com_code, 'is_finished' => 0])->first();
                        if (empty($check_shift)) {
                            return redirect()->back()->with('error', 'تم اغلاق الشفت الحالي')->withInput();
                        }
                        else {
                            $insertTransaction['shift_code'] = $request->shift_code;
                        }

                        $last_unpaid_arrive = Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->value('last_unpaid_arrive');


                        if (empty($last_unpaid_arrive) && $last_unpaid_arrive != 0) {
                            return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                        }
                        else {
                            $insertTransaction['last_arrive'] = $last_unpaid_arrive + 1;
                        }

                        // Move type will number 6 صرف نظير مرتجع مبيعات
                        $insertTransaction['move_type'] = 6;
                        // Account number will be like the account number for the customer in the purchaseHeader
                        $insertTransaction['account_number'] = $data['account_number'];
                        $insertTransaction['transaction_type'] = 3;
                        $insertTransaction['money'] = 0;
                        $insertTransaction['is_approved'] = 1;
                        $insertTransaction['invoice_id'] = $id;
                        $insertTransaction['treasuries_id'] = $request->treasuries_id;
                        $insertTransaction['move_date'] = date('Y-m-d');
                        $insertTransaction['byan'] = ' صرف نظير مرتجع مبيعات للعميل' . ' ' . $data['customer_name'] . ' ' . 'فاتورة رقم ' . $id;
                        $insertTransaction['is_account'] = 1;
                        $insertTransaction['money_for_account'] = $insertReturnInvoice['what_remain'] * (-1);
                        $insertTransaction['added_by'] = auth()->user()->id;
                        $insertTransaction['com_code'] = $com_code;
                        $insertTransaction['created_at'] = date('Y-m-d H:i:s');

                        $flag = TreasuryTransaction::create($insertTransaction);

                        if($flag) {
                            $update_treasuries['last_unpaid_arrive'] = $last_unpaid_arrive + 1;
                            Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->update($update_treasuries);
                        }
                    }

                    if ($data['delegate_code'] != '' && $data['delegate_code'] != null) {
                        $max_transaction_code = TreasuryTransaction::where(['transaction_type' => 3,'com_code' => $com_code])->max('transaction_code');
                        if (empty($max_transaction_code)) {
                            $insertTransaction['transaction_code'] = 1;
                        }
                        else {
                            $insertTransaction['transaction_code'] = $max_transaction_code + 1;
                        }

                        $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'treasuries_id' => $request->treasuries_id, 'com_code' => $com_code, 'is_finished' => 0])->first();
                        if (empty($check_shift)) {
                            return redirect()->back()->with('error', 'تم اغلاق الشفت الحالي')->withInput();
                        }
                        else {
                            $insertTransaction['shift_code'] = $request->shift_code;
                        }

                        $last_unpaid_arrive = Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->value('last_unpaid_arrive');


                        if (empty($last_unpaid_arrive) && $last_unpaid_arrive != 0) {
                            return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                        }
                        else {
                            $insertTransaction['last_arrive'] = $last_unpaid_arrive + 1;
                        }

                        // Move type will number 28 تحصيل عمولة مرتجع مبيعات من مندوب
                        $insertTransaction['move_type'] = 28;
                        // Account number will be like the account number for the customer in the purchaseHeader
                        $insertTransaction['account_number'] = $data['delegate_account_number'];
                        $insertTransaction['transaction_type'] = 3;
                        $insertTransaction['money'] = 0;
                        $insertTransaction['is_approved'] = 1;
                        $insertTransaction['invoice_id'] = $id;
                        $insertTransaction['treasuries_id'] = $request->treasuries_id;
                        $insertTransaction['move_date'] = date('Y-m-d');
                        $insertTransaction['byan'] = ' تحصيل عمولة مرتجع مبيعات من المندوب' . ' ' . $data['delegate_name'] . ' ' . 'فاتورة رقم ' . $id;
                        $insertTransaction['is_account'] = 1;
                        $insertTransaction['money_for_account'] = $data['money_for_delegate'] * (1);
                        $insertTransaction['added_by'] = auth()->user()->id;
                        $insertTransaction['com_code'] = $com_code;
                        $insertTransaction['created_at'] = date('Y-m-d H:i:s');

                        $flag = TreasuryTransaction::create($insertTransaction);

                        if($flag) {
                            $update_treasuries['last_unpaid_arrive'] = $last_unpaid_arrive + 1;
                            Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->update($update_treasuries);
                        }
                    }


                    $i = 0;
                    foreach ($request->id as $detail_id) {
                        $update_detail['rejected_quantity'] = $request->rejected_quantity[$i];
                        $update_detail['updated_by'] = auth()->user()->id;
                        $update_detail['updated_at'] = date('Y-m-d H:i:s');

                        $flag = InvoiceOrderDetail::where(['id' => $detail_id, 'invoice_order_id' => $id])->update($update_detail);
                        if ($flag && $update_detail['rejected_quantity'] > 0) {

                            $detail_data = InvoiceOrderDetail::where(['id' => $detail_id, 'invoice_order_id' => $id])->get()->first();
                            $item_card_data = InvItemCard::where(['item_code' => $detail_data['item_code'], 'com_code' => $com_code])->get(['unit_id', 'retail_unit_id', 'retail_uom_quntToParent', 'item_type', 'does_has_retailunit'])->first();
                            if (!empty($item_card_data)) {
                                // Now we will check if the unit is master or retail because we say that every item will get int with master unit
                                // if master we make the quantity is the same quantity
                                $quantity = 0;
                                if ($detail_data['unit_id'] == $item_card_data['unit_id']) {
                                    $quantity = $detail_data['rejected_quantity'];
                                }
                                else if ($detail_data['unit_id'] == $item_card_data['retail_unit_id']) {
                                    // we will change it to master unit
                                    // by divide the quantity with retail_uom_quntToParent
                                    $quantity = $detail_data['rejected_quantity'] / $item_card_data['retail_uom_quntToParent'];
                                }

                                // before i make insert or update i should get the quantity in all store and current store from the batch
                                $quantity_in_batch_before = InvItemCardBatch::where(['item_code' => $detail_data['item_code'], 'com_code' => $com_code])->sum('quantity');
                                $quantity_in_batch_current_store_before = InvItemCardBatch::where(['item_code' => $detail_data['item_code'], 'store_id' => $detail_data['store_id'], 'com_code' => $com_code])->sum('quantity');

                                // now we check if there is like this batch in the item batches
                                $batch_quantity = InvItemCardBatch::where(['id' => $detail_data['batch_id']])->value('quantity');

                                $updateOldBatch['quantity'] = $batch_quantity + $quantity;
                                $updateOldBatch['updated_by'] = auth()->user()->id;
                                $updateOldBatch['updated_at'] = date('Y-m-d H:i:s');
                                InvItemCardBatch::where(['id' => $detail_data['batch_id']])->update($updateOldBatch);


                                // get the quantity in all store and current store from the batch and we will get the name of the master unit
                                $quantity_in_batch_after = InvItemCardBatch::where(['item_code' => $detail_data['item_code'], 'com_code' => $com_code])->sum('quantity');
                                $quantity_in_batch_current_store_after = InvItemCardBatch::where(['item_code' => $detail_data['item_code'], 'store_id' => $detail_data['store_id'], 'com_code' => $com_code])->sum('quantity');
                                $parent_unit_name = InvUnit::where('id', $item_card_data['unit_id'])->value('name');


                                // Then we will save this change with item card in the item card movements table
                                $insertItemMovement['inv_item_card_movements_categories_id'] = 2;
                                $insertItemMovement['item_code'] = $detail_data['item_code'];
                                $insertItemMovement['inv_item_card_movements_types_id'] = 16;
                                $insertItemMovement['order_header_id'] = $id;
                                $insertItemMovement['order_details_id'] = $detail_data['id'];
                                $insertItemMovement['store_id'] = $detail_data['store_id'];
                                $insertItemMovement['batch_id'] = $detail_data['batch_id'];
                                $insertItemMovement['quantity_before_movement'] = $quantity_in_batch_before . ' ' . $parent_unit_name;
                                $insertItemMovement['quantity_after_movement'] = $quantity_in_batch_after . ' ' . $parent_unit_name;
                                $insertItemMovement['quantity_before_movement_in_current_store'] = $quantity_in_batch_current_store_before . ' ' . $parent_unit_name;
                                $insertItemMovement['quantity_after_movement_in_current_store'] = $quantity_in_batch_current_store_after . ' ' . $parent_unit_name;
                                $insertItemMovement['byan'] = 'مرتجع مبيعات بأصل الفاتورة للعميل ' . $data['customer_name'] . ' فاتورة رقم ' . $id;
                                $insertItemMovement['created_at'] = date('Y-m-d H:i:s');
                                $insertItemMovement['date'] = date('Y-m-d');
                                $insertItemMovement['added_by'] = auth()->user()->id;
                                $insertItemMovement['com_code'] = $com_code;

                                InvItemCardMovement::create($insertItemMovement);


                                // if has retail unit we will update the cost_price_in_master and cost_price_in_retail
                                // update the quantity in item_card
                                $all_quantity = InvItemCardBatch::where(['item_code' => $detail_data['item_code'], 'com_code' => $com_code])->sum('quantity');
                                if ($item_card_data['does_has_retailunit'] == 1) {
                                    $all_retail = $all_quantity * $item_card_data['retail_uom_quntToParent'];
                                    $all_master = intdiv($all_retail, $item_card_data['retail_uom_quntToParent']);
                                    $remain_retail = fmod($all_retail, $item_card_data['retail_uom_quntToParent']);

                                    $update_item_card_price_quantity['all_quantity_with_master_unit'] = $all_master;
                                    $update_item_card_price_quantity['all_quantity_with_retail_unit'] = round($all_retail, 0);
                                    $update_item_card_price_quantity['remain_quantity_in_retail'] = round($remain_retail, 0);

                                }
                                else {
                                    $update_item_card_price_quantity['all_quantity_with_master_unit'] = intval($all_quantity);
                                }

                                InvItemCard::where(['item_code' => $detail_data['item_code'], 'com_code' => $com_code])->update($update_item_card_price_quantity);
                            }

                        }

                        $i++;
                    }

                    return redirect()->route('admin.sales_order_header_original_return.index')->with('success', 'تم اعتماد واضافة الفاتورة بنجاح');
                }

            }
            catch(Exception $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function details($id)
    {
        //
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'التفاصيل') == true) {
            try {
                $com_code = auth()->user()->com_code;
                $data = InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code])->first();
                $sales_data = SalesOrderHeader::where(['invoice_id' => $id, 'com_code' => auth()->user()->com_code])->first();

                if (empty($data)) {
                    return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
                }

                $data['added_by_name'] = Admin::where('id', $data['added_by'])->value('name');
                $data['sales_code'] = $sales_data['sales_code'];
                if ($data['updated_by'] != null) {
                    $data['updated_by_name'] = Admin::where('id', $data['updated_by'])->value('name');
                }

                $data['tax_value'] = $data['total_before_discount'] * $data['tax_percent'] / 100;
                $data['total_after_tax'] = $data['total_before_discount'] + $data['tax_value'];
                $data['discount_percent'] = round($data['discount_value'] / $data['total_after_tax'], 3);

                if ($sales_data['customer_code'] != null) {
                    $person_id = Customer::where(['customer_code' => $sales_data['customer_code'], 'com_code' => $com_code])->value('person_id');
                    $customer = Person::where(['id' => $person_id, 'com_code' => $com_code])->select(['first_name', 'last_name'])->first();
                    $data['customer_name'] = $customer->first_name . ' ' . $customer->last_name;
                }
                else {
                    $data['customer_name'] = 'لا يوجد';
                }

                if ($sales_data['delegate_code'] != null) {
                    $person_id = Delegate::where(['delegate_code' => $sales_data['delegate_code'], 'com_code' => $com_code])->value('person_id');
                    $delegate = Person::where(['id' => $person_id, 'com_code' => $com_code])->select(['first_name', 'last_name'])->first();
                    $data['delegate_name'] = $delegate->first_name . ' ' . $delegate->last_name;
                }
                else {
                    $data['delegate_name'] = 'لا يوجد';
                }
                $data['total_cost'] = OriginalReturnInvoice::where('invoice_order_id', $data['id'])->value('total_cost');


                $details = InvoiceOrderDetail::where(['invoice_order_id' => $id, 'com_code' => $com_code])->where('rejected_quantity', '>', 0)->get();

                if (!empty($details)) {
                    foreach($details as $detail) {
                        $detail['item_card_name'] = InvItemCard::where('item_code', $detail['item_code'])->value('name');
                        $detail['added_by_name'] = Admin::where('id', $detail['added_by'])->value('name');
                        $detail['unit_name'] = InvUnit::where(['id' => $detail['unit_id'], 'com_code' => $com_code])->value('name');
                        $detail['store_name'] = Store::where(['id' => $detail['store_id'], 'com_code' => $com_code])->value('name');

                        if ($detail['updated_by'] != null) {
                            $detail['updated_by_name'] = Admin::where('id', $detail['updated_by'])->value('name');
                        }
                        $total_price = $detail['unit_price'] * $detail['rejected_quantity'];
                        $total_price += $total_price * $data['tax_percent'] / 100;
                        $total_price -= $total_price * $data['discount_percent'];
                        $detail['total_price'] = round($total_price,2);
                    }
                }

                return view('admin.sales_order_header_original_return.details', ['data' => $data, 'details' => $details]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }


    }

    public function ajax_search(Request $request) {
        try {
            if ($request->ajax()) {
                $pill_code_search = $request->pill_code_search;
                $customer_code_search = $request->customer_code_search;
                $delegate_code_search = $request->delegate_code_search;
                $from_date_search = $request->from_date_search;
                $to_date_search = $request->to_date_search;


                if ($pill_code_search == '') {
                    $filed1 = 'id';
                    $operator1 = '>';
                    $value1 = 0;
                }
                else {
                    $filed1 = 'pill_code';
                    $operator1 = 'LIKE';
                    $value1 = '%'. $pill_code_search . '%';
                }



                if ($customer_code_search == 'all') {
                    $filed2 = 'invoice_id';
                    $operator2 = '>';
                    $value2 = 0;
                }
                else {
                    $filed2 = 'customer_code';
                    $operator2 = 'LIKE';
                    $value2 = '%'. $customer_code_search . '%';
                }

                if ($delegate_code_search == 'all') {
                    $filed3 = 'invoice_id';
                    $operator3 = '>';
                    $value3 = 0;
                }
                else {
                    $filed3 = 'delegate_code';
                    $operator3 = 'LIKE';
                    $value3 = '%'.$delegate_code_search.'%';
                }

                if ($from_date_search == '') {
                    $filed4 = 'id';
                    $operator4 = '>';
                    $value4 = 0;
                }
                else {
                    $filed4 = 'order_date';
                    $operator4 = '>=';
                    $value4 = $from_date_search;
                }

                if ($to_date_search == '') {
                    $filed5 = 'id';
                    $operator5 = '>';
                    $value5 = 0;
                }
                else {
                    $filed5 = 'order_date';
                    $operator5 = '<=';
                    $value5 = $to_date_search;
                }


                $data_in = SalesOrderHeader::where("$filed2", "$operator2", "$value2")->where("$filed3", "$operator3", "$value3")->get('invoice_id');
                $data = InvoiceOrderHeader::whereIn('id', $data_in)->where("$filed1", "$operator1", "$value1")->where("$filed4", "$operator4", "$value4")->where("$filed5", "$operator5", "$value5")->where(['com_code' => auth()->user()->com_code, 'invoice_type' => 2, 'order_type' => 1, 'is_original_return' => 1])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);

                if (!empty($data)) {
                    foreach ($data as $d) {
                        $d['customer_code'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('customer_code');
                        $d['delegate_code'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('delegate_code');
                        $d['sales_code'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('sales_code');
                        $d['auto_serial'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('auto_serial');
                        if ($d['customer_code'] != null) {
                            $person_id = Customer::where(['customer_code' => $d['customer_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                            $customer = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                            $d['customer_name'] = $customer->first_name . ' ' . $customer->last_name;
                        }
                        else {
                            $d['customer_name'] = 'لا يوجد';
                        }

                        if ($d['delegate_code'] != null) {
                            $person_id = Delegate::where(['delegate_code' => $d['delegate_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                            $delegate = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                            $d['delegate_name'] = $delegate->first_name . ' ' . $delegate->last_name;
                        }
                        else {
                            $d['delegate_name'] = 'لا يوجد';
                        }

                        $d['total_cost'] = OriginalReturnInvoice::where('invoice_order_id', $d['id'])->value('total_cost');
                    }
                }
                return view('admin.sales_order_header_original_return.ajax_search', ['data' => $data]);
            }
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function printA4($id, $type) {
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات بالاصل' , 'طباعة') == true) {
            try {
                $com_code = auth()->user()->com_code;
                $data = InvoiceOrderHeader::where('id', $id)->get()->first();
                if (!empty($data)) {
                    $data['customer_code'] = SalesOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $data['id']])->value('customer_code');
                    if ($data['customer_code'] != null) {
                        $person_id = Customer::where(['customer_code' => $data['customer_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                        $customer = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name', 'phone'])->first();
                        $data['customer_name'] = $customer->first_name . ' ' . $customer->last_name;
                        $data['customer_phone'] = $customer->phone;
                    }
                    else {
                        $data['customer_name'] = 'لا يوجد';
                    }

                    $data['tax_value'] = $data['total_before_discount'] * $data['tax_percent'] / 100;
                    $data['total_after_tax'] = $data['total_before_discount'] + $data['tax_value'];
                    $data['discount_percent'] = round($data['discount_value'] / $data['total_after_tax'], 3);
                    $data['total_cost'] = OriginalReturnInvoice::where('invoice_order_id', $data['id'])->value('total_cost');


                    $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();
                    $sales_invoices_details = InvoiceOrderDetail::where('invoice_order_id', $id)->get();
                    if (!empty($sales_invoices_details)) {
                        foreach ($sales_invoices_details as $detail) {
                            $detail['item_name'] = InvItemCard::where('item_code', $detail['item_code'])->value('name');
                            $detail['store_name'] = Store::where('id', $detail['store_id'])->value('name');
                            $detail['unit_name'] = InvUnit::where(['id' => $detail['unit_id'], 'com_code' => $com_code])->value('name');
                            $total_price = $detail['unit_price'] * $detail['rejected_quantity'];
                            $total_price += $total_price * $data['tax_percent'] / 100;
                            $total_price -= $total_price * $data['discount_percent'];
                            $detail['total_price'] = round($total_price,2);
                        }
                    }
                }

                if ($type == 'A4') {
                    return view('admin.sales_order_header_original_return.printA4', ['data' => $data, 'systemData' => $systemData, 'sales_invoices_details' => $sales_invoices_details]);
                }
                else if ($type == 'A6') {
                    return view('admin.sales_order_header_original_return.printA6', ['data' => $data, 'systemData' => $systemData, 'sales_invoices_details' => $sales_invoices_details]);
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
