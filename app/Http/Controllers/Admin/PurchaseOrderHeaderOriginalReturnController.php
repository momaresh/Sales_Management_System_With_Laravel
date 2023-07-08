<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\InvoiceOrderHeader;
use App\Models\PurchaseOrderHeader;
use App\Models\Admin;
use App\Models\Person;
use App\Models\Supplier;
use App\Http\Requests\PurchaseOrderHeaderRequest;
use App\Models\AdminPanelSetting;
use App\Models\AdminShift;
use App\Models\InvItemCard;
use App\Models\InvUnit;
use App\Models\InvoiceOrderDetail;
use App\Models\Store;
use App\Models\Treasury;
use App\Models\TreasuryTransaction;
use App\Models\InvItemCardBatch;
use App\Models\InvItemCardMovement;
use App\Models\OriginalReturnInvoice;
use Exception;
use Illuminate\Support\Arr;

class PurchaseOrderHeaderOriginalReturnController extends Controller
{

    public function index()
    {
        //
        if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'عرض') == true) {
            try {
                $data = InvoiceOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_type' => 1, 'is_original_return' => 1])->orderBy('id' , 'desc')->paginate(PAGINATION_COUNT);
                if (!empty($data)) {
                    foreach ($data as $d) {
                        $d['supplier_code'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('supplier_code');
                        $d['purchase_code'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('purchase_code');
                        $d['auto_serial'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('auto_serial');
                        $d['store_id'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('store_id');
                        $d['store_name'] = Store::where('id', $d['store_id'])->value('name');
                        if ($d['supplier_code'] != null) {
                            $person_id = Supplier::where(['supplier_code' => $d['supplier_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                            $supplier = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                            $d['supplier_name'] = $supplier->first_name . ' ' . $supplier->last_name;
                        }

                        $d['total_cost'] = OriginalReturnInvoice::where('invoice_order_id', $d['id'])->value('total_cost');
                    }
                }


                $com_code = auth()->user()->com_code;
                $suppliers = Person::where(['person_type' => 2, 'com_code' => auth()->user()->com_code, 'active' => 1])->get(['id', 'first_name', 'last_name']);
                $stores = Store::where(['com_code' => $com_code, 'active' => 1])->get(['id', 'name']);
                if (!empty($suppliers)) {
                    foreach ($suppliers as $sup) {
                        $sup['supplier_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                        $sup['supplier_code'] = Supplier::where(['person_id' => $sup['id']])->value('supplier_code');
                    }
                }

                return view('admin.purchase_order_header_original_return.index', ['data' => $data, 'suppliers' => $suppliers, 'stores' => $stores]);
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
        if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'اضافة') == true) {
            try {
                $com_code = auth()->user()->com_code;

                $suppliers = Person::where(['person_type' => 2, 'com_code' => $com_code, 'active' => 1])->get(['first_name', 'last_name', 'id']);
                foreach ($suppliers as $sup) {
                    $sup['supplier_code'] = Supplier::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('supplier_code');
                    $sup['supplier_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                }

                $stores = Store::where(['com_code' => $com_code, 'active' => 1])->get(['name', 'id']);

                $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->get(['treasuries_id', 'id'])->first();
                if (empty($check_shift)) {
                    return Response()->json(['error' => 'انت لاتملك شفت حالي لعمل اضافة'], 404);
                }
                $check_shift['treasuries_name'] = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                $check_shift['treasuries_money'] = TreasuryTransaction::where(['shift_code' => $check_shift['id'], 'com_code' => $com_code])->sum('money');


                return view('admin.purchase_order_header_original_return.create', ['suppliers' => $suppliers, 'stores' => $stores, 'check_shift' => $check_shift]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }

    }

    public function get_supplier_pills(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $supplier_code = $request->supplier_code;

            $pills_in = PurchaseOrderHeader::where(['supplier_code' => $supplier_code, 'com_code' => $com_code])->get(['invoice_id']);
            $pills = InvoiceOrderHeader::whereIn('id', $pills_in)->where(['invoice_type' => 1, 'order_type' => 1])->get(['id', 'order_date', 'pill_code']);
            return view("admin.purchase_order_header_original_return.get_supplier_pills", ['pills' => $pills]);
        }
    }

    public function get_pill_details(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $pill_code = $request->pill_code;

            $pill = InvoiceOrderHeader::where(['pill_code' => $pill_code, 'invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->get()->first();
            $pill_details = array();
            $check_shift = array();
            if (!empty($pill)) {
                if ($pill['is_original_return'] == 0) {
                    if (!empty($pill)) {
                        $pill['store_id'] = PurchaseOrderHeader::where('invoice_id', $pill['id'])->value('store_id');
                        $pill['store_name'] = Store::where('id', $pill['store_id'])->value('name');
                        $pill['tax_value'] = $pill['total_before_discount'] * $pill['tax_percent'] / 100;
                        $pill['total_after_tax'] = $pill['total_before_discount'] + $pill['tax_value'];
                        $pill['discount_percent'] = $pill['discount_value'] / $pill['total_after_tax'];
                        $pill['supplier_code'] = PurchaseOrderHeader::where('invoice_id', $pill['id'])->value('supplier_code');
                        if ($pill['supplier_code'] != null) {
                            $person_id = Supplier::where(['supplier_code' => $pill['supplier_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                            $supplier = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                            $pill['supplier_name'] = $supplier->first_name . ' ' . $supplier->last_name;
                        }
                    }

                    $pill_details = InvoiceOrderDetail::where('invoice_order_id', $pill['id'])->get();
                    if (!empty($pill_details)) {
                        foreach ($pill_details as $s) {
                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                            $s['item_name'] = InvItemCard::where(['item_code' => $s['item_code'], 'com_code' => $com_code])->value('name');
                            $s['remain_quantity'] = $s['quantity'] - $s['rejected_quantity'];
                            $item_card_data = InvItemCard::where(['item_code' => $s['item_code'], 'com_code' => $com_code])->get(['unit_id', 'retail_unit_id', 'retail_uom_quntToParent'])->first();
                            $s['batch_quantity'] = InvItemCardBatch::where('id', $s['batch_id'])->value('quantity');

                            if ($s['unit_id'] == $item_card_data['retail_unit_id']) {
                                $quantity = $s['batch_quantity'] * $item_card_data['retail_uom_quntToParent'];
                                $quantity = round($quantity, 0);
                                $s['batch_quantity'] = $quantity;
                            }
                        }
                    }

                    $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->get(['treasuries_id', 'id'])->first();
                    $check_shift['treasuries_name'] = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                    $check_shift['treasuries_money'] = TreasuryTransaction::where(['shift_code' => $check_shift['id'], 'com_code' => $com_code])->sum('money');

                }
            }

            return view("admin.purchase_order_header_original_return.get_pill_details", ['pill' => $pill, 'pill_details' => $pill_details, 'check_shift' => $check_shift]);
        }
    }

    public function approve_pill(Request $request, $id)
    {
        if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'اعتماد') == true) {
            try {
                # code...
                $com_code = auth()->user()->com_code;
                $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->first();
                if (empty($check_shift)) {
                    return redirect()->back()->with('error', 'انت لا تمتلك شفت لعمل اعتماد')->withInput();
                }

                $data = InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code, 'order_type' => 1, 'invoice_type' => 1])->get()->first();
                $data['supplier_code'] = PurchaseOrderHeader::where(['invoice_id' => $id, 'com_code' => $com_code])->value('supplier_code');
                $data['store_id'] = PurchaseOrderHeader::where(['invoice_id' => $id, 'com_code' => $com_code])->value('store_id');
                $data['total_cost'] = $request->total_pill;

                if (empty($data)) {
                    return redirect()->back()->with('error', 'لا توجد بيانات كهذه');
                }
                if ($data['is_original_return'] == 1) {
                    return redirect()->back()->with('error', 'الفاتورة تم ارجاعها من قبل');
                }

                $updateInvoice['is_original_return'] = 1;
                $updateInvoice['updated_by'] = auth()->user()->id;
                $updateInvoice['updated_at'] = date('Y-m-d H:i:s');

                $flag = InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code, 'order_type' => 1, 'invoice_type' => 1])->update($updateInvoice);

                if ($flag) {
                    $insertReturnInvoice['invoice_order_id'] = $data['id'];
                    $insertReturnInvoice['pill_type'] = $request->pill_type;
                    $insertReturnInvoice['what_paid'] = $request->what_paid;
                    $insertReturnInvoice['what_remain'] = $request->what_remain;
                    $insertReturnInvoice['total_cost'] = $request->total_pill;
                    $insertReturnInvoice['money_for_account'] = $request->total_pill * (1);
                    $insertReturnInvoice['added_by'] = auth()->user()->id;
                    $insertReturnInvoice['created_at'] = date('Y-m-d H:i:s');
                    $insertReturnInvoice['return_date'] = date('Y-m-d');
                    $insertReturnInvoice['com_code'] = $com_code;

                    $flag = OriginalReturnInvoice::create($insertReturnInvoice);

                    if ($data['supplier_code'] != '' && $data['supplier_code'] != null) {
                        $person_id = Supplier::where(['supplier_code' => $data['supplier_code'], 'com_code' => $com_code])->value('person_id');
                        $data['account_number'] = Person::where(['id' => $person_id,'com_code' => $com_code])->value('account_number');
                        $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                        $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                        $data['supplier_name'] = $first_name . ' ' . $last_name;

                        // change the supplier current balance in accounts
                        $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                        $update_account['current_balance'] = $get_current + $request->total_pill;
                        Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);
                    }

                    if ($request->what_paid > 0) {
                        $max_transaction_code = TreasuryTransaction::where(['transaction_type' => 2,'com_code' => $com_code])->max('transaction_code');
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

                        $last_collection_arrive = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('last_collection_arrive');


                        if (empty($last_collection_arrive) && $last_collection_arrive != 0) {
                            return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                        }
                        else {
                            $insertTransaction['last_arrive'] = $last_collection_arrive + 1;
                        }

                        //تحصيل نظير مرتجع مشتريات من مورد
                        $insertTransaction['move_type'] = 10;
                        // Account number will be like the account number for the supplier in the purchaseHeader
                        $insertTransaction['account_number'] = $data['account_number'];
                        $insertTransaction['transaction_type'] = 2;
                        $insertTransaction['money'] = $request->what_paid * (1);
                        $insertTransaction['is_approved'] = 1;
                        $insertTransaction['invoice_id'] = $id;
                        $insertTransaction['treasuries_id'] = $check_shift['treasuries_id'];
                        $insertTransaction['move_date'] = date('Y-m-d');
                        $insertTransaction['byan'] = ' تحصيل نظير مرتجع مشتريات من مورد' . ' ' . $data['supplier_name'];
                        $insertTransaction['is_account'] = 1;
                        $insertTransaction['money_for_account'] = $request->what_paid * (-1);
                        $insertTransaction['added_by'] = auth()->user()->id;
                        $insertTransaction['com_code'] = $com_code;
                        $insertTransaction['created_at'] = date('Y-m-d H:i:s');

                        $flag = TreasuryTransaction::create($insertTransaction);

                        if($flag) {

                            $update_treasuries['last_collection_arrive'] = $last_collection_arrive + 1;
                            Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->update($update_treasuries);

                            // change the supplier current balance in accounts
                            $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                            $update_account['current_balance'] = $get_current - $request->what_paid;
                            Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);
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

                        $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->first();
                        if (empty($check_shift)) {
                            return redirect()->back()->with('error', 'تم اغلاق الشفت الحالي')->withInput();
                        }
                        else {
                            $insertTransaction['shift_code'] = $check_shift['id'];
                        }

                        $last_unpaid_arrive = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('last_unpaid_arrive');


                        if (empty($last_unpaid_arrive) && $last_unpaid_arrive != 0) {
                            return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                        }
                        else {
                            $insertTransaction['last_arrive'] = $last_unpaid_arrive + 1;
                        }

                        //تحصيل نظير مرتجع مشتريات من مورد
                        $insertTransaction['move_type'] = 10;
                        // Account number will be like the account number for the supplier in the purchaseHeader
                        $insertTransaction['account_number'] = $data['account_number'];
                        $insertTransaction['transaction_type'] = 3;
                        $insertTransaction['money'] = 0;
                        $insertTransaction['is_approved'] = 1;
                        $insertTransaction['invoice_id'] = $id;
                        $insertTransaction['treasuries_id'] = $check_shift['treasuries_id'];
                        $insertTransaction['move_date'] = date('Y-m-d');
                        $insertTransaction['byan'] = ' تحصيل نظير مرتجع مشتريات من مورد' . ' ' . $data['supplier_name'];
                        $insertTransaction['is_account'] = 1;
                        $insertTransaction['money_for_account'] = $request->what_remain * (1);
                        $insertTransaction['added_by'] = auth()->user()->id;
                        $insertTransaction['com_code'] = $com_code;
                        $insertTransaction['created_at'] = date('Y-m-d H:i:s');

                        $flag = TreasuryTransaction::create($insertTransaction);

                        if($flag) {
                            $update_treasuries['last_unpaid_arrive'] = $last_unpaid_arrive + 1;
                            Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->update($update_treasuries);
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
                                $quantity_in_batch_current_store_before = InvItemCardBatch::where(['item_code' => $detail_data['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');

                                // now we check if there is like this batch in the item batches
                                $batch_quantity = InvItemCardBatch::where(['id' => $detail_data['batch_id']])->value('quantity');

                                $updateOldBatch['quantity'] = $batch_quantity - $quantity;
                                $updateOldBatch['updated_by'] = auth()->user()->id;
                                $updateOldBatch['updated_at'] = date('Y-m-d H:i:s');
                                InvItemCardBatch::where(['id' => $detail_data['batch_id']])->update($updateOldBatch);


                                // get the quantity in all store and current store from the batch and we will get the name of the master unit
                                $quantity_in_batch_after = InvItemCardBatch::where(['item_code' => $detail_data['item_code'], 'com_code' => $com_code])->sum('quantity');
                                $quantity_in_batch_current_store_after = InvItemCardBatch::where(['item_code' => $detail_data['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');
                                $parent_unit_name = InvUnit::where('id', $item_card_data['unit_id'])->value('name');


                                // Then we will save this change with item card in the item card movements table
                                $insertItemMovement['inv_item_card_movements_categories_id'] = 1;
                                $insertItemMovement['item_code'] = $detail_data['item_code'];
                                $insertItemMovement['inv_item_card_movements_types_id'] = 2;
                                $insertItemMovement['order_header_id'] = $id;
                                $insertItemMovement['order_details_id'] = $detail_data['id'];
                                $insertItemMovement['store_id'] = $data['store_id'];
                                $insertItemMovement['batch_id'] = $detail_data['batch_id'];
                                $insertItemMovement['quantity_before_movement'] = $quantity_in_batch_before . ' ' . $parent_unit_name;
                                $insertItemMovement['quantity_after_movement'] = $quantity_in_batch_after . ' ' . $parent_unit_name;
                                $insertItemMovement['quantity_before_movement_in_current_store'] = $quantity_in_batch_current_store_before . ' ' . $parent_unit_name;
                                $insertItemMovement['quantity_after_movement_in_current_store'] = $quantity_in_batch_current_store_after . ' ' . $parent_unit_name;
                                $insertItemMovement['byan'] = 'مرتجع مشتريات بأصل الفاتورة للمورد ' . $data['supplier_name'] . ' فاتورة رقم ' . $id;
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

                    return redirect()->route('admin.purchase_order_header_original_return.index')->with('success', 'تم اعتماد واضافة الفاتورة بنجاح');
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
        if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'التفاصيل') == true) {
            try {
                $com_code = auth()->user()->com_code;
                $data = InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code])->first();
                $purchase_data = PurchaseOrderHeader::where(['invoice_id' => $id, 'com_code' => auth()->user()->com_code])->first();

                if (empty($data)) {
                    return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
                }

                $data['added_by_name'] = Admin::where('id', $data['added_by'])->value('name');
                $data['store_name'] = Store::where(['id' => $purchase_data['store_id'], 'com_code' => $com_code])->value('name');
                $data['purchase_code'] = $purchase_data['purchase_code'];
                if ($data['updated_by'] != null) {
                    $data['updated_by_name'] = Admin::where('id', $data['updated_by'])->value('name');
                }

                if ($purchase_data['supplier_code'] != null) {
                    $person_id = Supplier::where(['supplier_code' => $purchase_data['supplier_code'], 'com_code' => $com_code])->value('person_id');
                    $supplier = Person::where(['id' => $person_id, 'com_code' => $com_code])->select(['first_name', 'last_name'])->first();
                    $data['supplier_name'] = $supplier->first_name . ' ' . $supplier->last_name;
                }
                $data['total_cost'] = OriginalReturnInvoice::where('invoice_order_id', $data['id'])->value('total_cost');

                $details = InvoiceOrderDetail::where(['invoice_order_id' => $id, 'com_code' => $com_code])->where('rejected_quantity', '>', 0)->get();

                if (!empty($details)) {
                    foreach($details as $detail) {
                        $detail['item_card_name'] = InvItemCard::where('item_code', $detail['item_code'])->value('name');
                        $detail['added_by_name'] = Admin::where('id', $detail['added_by'])->value('name');
                        $detail['unit_name'] = InvUnit::where(['id' => $detail['unit_id'], 'com_code' => $com_code])->value('name');

                        if ($detail['updated_by'] != null) {
                            $detail['updated_by_name'] = Admin::where('id', $detail['updated_by'])->value('name');
                        }
                        $total_price = $detail['unit_price'] * $detail['rejected_quantity'];
                        $total_price += $total_price * $data['tax_percent'] / 100;
                        $total_price -= $total_price * $data['discount_percent'];
                        $detail['total_price'] = round($total_price,2);
                    }
                }

                return view('admin.purchase_order_header_original_return.details', ['data' => $data, 'details' => $details]);
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
        if ($request->ajax()) {

            $pill_code_search = $request->purchase_code_search;
            $supplier_code_search = $request->supplier_code_search;
            $store_id_search = $request->store_id_search;
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



            if ($supplier_code_search == 'all') {
                $filed2 = 'invoice_id';
                $operator2 = '>';
                $value2 = 0;
            }
            else {
                $filed2 = 'supplier_code';
                $operator2 = 'LIKE';
                $value2 = '%'. $supplier_code_search . '%';
            }

            if ($store_id_search == 'all') {
                $filed3 = 'invoice_id';
                $operator3 = '>';
                $value3 = 0;
            }
            else {
                $filed3 = 'store_id';
                $operator3 = '=';
                $value3 = $store_id_search;
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


            $data_in = PurchaseOrderHeader::where("$filed2", "$operator2", "$value2")->where("$filed3", "$operator3", "$value3")->get('invoice_id');
            $data = InvoiceOrderHeader::whereIn('id', $data_in)->where("$filed1", "$operator1", "$value1")->where("$filed4", "$operator4", "$value4")->where("$filed5", "$operator5", "$value5")->where(['com_code' => auth()->user()->com_code, 'invoice_type' => 1, 'order_type' => 1, 'is_original_return' => 1])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);

            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['supplier_code'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('supplier_code');
                    $d['purchase_code'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('purchase_code');
                    $d['auto_serial'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('auto_serial');
                    $d['store_id'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $d['id']])->value('store_id');
                    $d['store_name'] = Store::where('id', $d['store_id'])->value('name');
                    if ($d['supplier_code'] != null) {
                        $person_id = Supplier::where(['supplier_code' => $d['supplier_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                        $supplier = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                        $d['supplier_name'] = $supplier->first_name . ' ' . $supplier->last_name;
                    }
                    $d['total_cost'] = OriginalReturnInvoice::where('invoice_order_id', $d['id'])->value('total_cost');
                }
            }

            return view('admin.purchase_order_header_original_return.ajax_search', ['data' => $data]);
        }
    }

    public function printA4($id, $type) {
        if (check_control_menu_role('الحركات المخزنية', 'فواتير المرتجعات بالاصل' , 'طباعة') == true) {
            try {
                $com_code = auth()->user()->com_code;
                $data = InvoiceOrderHeader::where('id', $id)->get()->first();
                if (!empty($data)) {
                    $data['supplier_code'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $data['id']])->value('supplier_code');
                    $data['purchase_code'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $data['id']])->value('purchase_code');
                    $data['auto_serial'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $data['id']])->value('auto_serial');
                    $data['store_id'] = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_id' => $data['id']])->value('store_id');
                    $data['store_name'] = Store::where('id', $data['store_id'])->value('name');
                    if ($data['supplier_code'] != null) {
                        $person_id = Supplier::where(['supplier_code' => $data['supplier_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                        $supplier = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name', 'phone'])->first();
                        $data['supplier_name'] = $supplier->first_name . ' ' . $supplier->last_name;
                        $data['supplier_phone'] = $supplier->phone;
                    }
                    else {
                        $data['supplier_name'] = 'لا يوجد';
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
                            $detail['unit_name'] = InvUnit::where(['id' => $detail['unit_id'], 'com_code' => $com_code])->value('name');
                            $total_price = $detail['unit_price'] * $detail['rejected_quantity'];
                            $total_price += $total_price * $data['tax_percent'] / 100;
                            $total_price -= $total_price * $data['discount_percent'];
                            $detail['total_price'] = round($total_price,2);
                        }
                    }
                }

                if ($type == 'A4') {
                    return view('admin.purchase_order_header_original_return.printA4', ['data' => $data, 'systemData' => $systemData, 'sales_invoices_details' => $sales_invoices_details]);
                }
                else if ($type == 'A6') {
                    return view('admin.purchase_order_header_original_return.printA6', ['data' => $data, 'systemData' => $systemData, 'sales_invoices_details' => $sales_invoices_details]);
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
