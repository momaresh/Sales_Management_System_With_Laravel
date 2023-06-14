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
use App\Models\AdminShift;
use App\Models\InvItemCard;
use App\Models\InvUnit;
use App\Models\InvoiceOrderDetail;
use App\Models\Store;
use App\Models\Treasury;
use App\Models\TreasuryTransaction;
use App\Models\InvItemCardBatch;
use App\Models\InvItemCardMovement;
use Exception;


class PurchaseOrderHeaderGeneralReturnController extends Controller
{

    public function index()
    {
        //
        try {
            $data = InvoiceOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_type' => 1, 'order_type' => 3])->orderBy('id' , 'desc')->paginate(PAGINATION_COUNT);
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
                }
            }


            $com_code = auth()->user()->com_code;
            $suppliers = Person::where(['person_type' => 2, 'com_code' => $com_code])->get(['first_name', 'last_name', 'id']);
            foreach ($suppliers as $sup) {
                $sup['supplier_code'] = Supplier::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('supplier_code');
                $sup['supplier_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
            }

            $stores = Store::where(['com_code' => $com_code])->get(['name', 'id']);

            return view('admin.purchase_order_header_general_return.index', ['data' => $data, 'suppliers' => $suppliers, 'stores' => $stores]);
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create_pill(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                $com_code = auth()->user()->com_code;

                $suppliers = Person::where(['person_type' => 2, 'com_code' => $com_code])->get(['first_name', 'last_name', 'id']);
                foreach ($suppliers as $sup) {
                    $sup['supplier_code'] = Supplier::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('supplier_code');
                    $sup['supplier_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                }


                $stores = Store::where(['com_code' => $com_code])->get(['name', 'id']);
                $items_card = InvItemCard::where(['active' => 1, 'com_code' => $com_code])->get(['item_code', 'name', 'item_type', 'has_fixed_price']);

                $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->get(['treasuries_id', 'shift_code'])->first();
                if (empty($check_shift)) {
                    return Response()->json(['error' => ''], 404);
                }
                $check_shift['treasuries_name'] = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                $check_shift['treasuries_money'] = TreasuryTransaction::where(['shift_code' => $check_shift['shift_code'], 'com_code' => $com_code])->sum('money');


                return view('admin.purchase_order_header_general_return.create_pill', ['items_card' => $items_card, 'suppliers' => $suppliers, 'stores' => $stores, 'check_shift' => $check_shift]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                //set account number
                $max_invoice_id = InvoiceOrderHeader::max('id');

                if (!empty($max_invoice_id)) {
                    $inserted_invoice['id'] = $max_invoice_id + 1;
                } else {
                    $inserted_invoice['id'] = 1;
                }

                $max_pill_code = InvoiceOrderHeader::where(['com_code' => auth()->user()->com_code, 'order_type' => 3, 'invoice_type' => 1])->max('pill_code');
                if (!empty($max_pill_code)) {
                    $inserted_invoice['pill_code'] = $max_pill_code + 1;
                } else {
                    $inserted_invoice['pill_code'] = 1;
                }

                $inserted_invoice['order_type'] = 3;
                $inserted_invoice['invoice_type'] = 1;
                $inserted_invoice['order_date'] = $request->pill_date;
                $inserted_invoice['pill_type'] = $request->pill_type;
                $inserted_invoice['notes'] = $request->notes;
                $inserted_invoice['added_by'] = auth()->user()->id;
                $inserted_invoice['created_at'] = date("Y-m-d H:i:s");
                $inserted_invoice['com_code'] = auth()->user()->com_code;

                $flag = InvoiceOrderHeader::create($inserted_invoice);
                if ($flag) {
                    $inserted_purchase['invoice_id'] = $inserted_invoice['id'];

                    $max_auto_serial = PurchaseOrderHeader::max('auto_serial');
                    if (!empty($max_auto_serial)) {
                        $inserted_purchase['auto_serial'] = $max_auto_serial + 1;
                    }
                    else {
                        $inserted_purchase['auto_serial'] = 1;
                    }

                    $max_purchase_code = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code])->max('purchase_code');
                    if (!empty($max_purchase_code)) {
                        $inserted_purchase['purchase_code'] = $max_purchase_code + 1;
                    }
                    else {
                        $inserted_purchase['purchase_code'] = 1;
                    }
                    $inserted_purchase['store_id'] = $request->store_id;
                    $inserted_purchase['supplier_code'] = $request->supplier_code;
                    $inserted_purchase['store_id'] = $request->store_id;
                    $inserted_purchase['added_by'] = auth()->user()->id;
                    $inserted_purchase['created_at'] = date("Y-m-d H:i:s");
                    $inserted_purchase['com_code'] = auth()->user()->com_code;
                    PurchaseOrderHeader::create($inserted_purchase);

                    return $inserted_invoice['id'];
                }

            }
            catch (Exception $e) {
                echo($e->getMessage());
            }
        }
    }

    public function delete($id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code])->get('is_approved')->first();

            if (empty($data)) {
                return redirect()->back()->with('error', 'لا توجد بيانات كهذه');
            }

            if ($data['is_approved'] == 1) {
                return redirect()->back()->with('error', 'لا يمكن حذف الفاتورة المعتمدة');
            }

            $count = InvoiceOrderDetail::where(['invoice_order_id' => $id, 'com_code' => $com_code])->count();
            $flag = 1;
            if ($count > 0) {
                return redirect()->back()->with('error', 'لا يمكن حذف الفاتورة التي تحتوي على اصناف الا عند حذف الاصناف من شاشتهم');
            }

            if ($flag) {
                PurchaseOrderHeader::where(['invoice_id' => $id, 'com_code' => $com_code])->delete();
                InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code])->delete();
                return redirect()->back()->with('success', 'تم الحذف بنجاح');
            }
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function load_pill_adding_items_modal(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                $com_code = auth()->user()->com_code;

                $sales_data = InvoiceOrderHeader::where('id', $request->id)->first();
                $sales_data['store_id'] = PurchaseOrderHeader::where('invoice_id', $request->id)->value('store_id');
                $sales_data['store_name'] = Store::where('id', $sales_data['store_id'])->value('name');

                $items = InvoiceOrderDetail::where('invoice_order_id', $request->id)->get();
                if (!empty($items)) {
                    foreach($items as $i) {
                        $i['unit_name'] = InvUnit::where('id', $i['unit_id'])->value('name');
                        $i['item_name'] = InvItemCard::where('item_code', $i['item_code'])->value('name');
                        $i['store_name'] = $sales_data['store_name'];
                    }
                }

                if (!empty($sales_data)) {
                    $sales_data['supplier_code'] = PurchaseOrderHeader::where('invoice_id', $request->id)->value('supplier_code');
                    $person_id = Supplier::where(['supplier_code' => $sales_data['supplier_code'], 'com_code' => $com_code])->value('person_id');
                    $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                    $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                    $sales_data['supplier_name'] = $first_name . ' ' . $last_name;
                    $sales_data['all_items'] = InvoiceOrderDetail::where('invoice_order_id', $request->id)->count();
                    $sales_data['tax_value'] = $sales_data['total_before_discount'] * $sales_data['tax_percent'];
                    $sales_data['total_after_tax'] = $sales_data['total_before_discount'] + $sales_data['tax_value'];

                    if ($sales_data['discount_type'] == 1) {
                        $sales_data['total_cost'] = $sales_data['total_after_tax'] - ($sales_data['total_after_tax'] * $sales_data['discount_percent']);
                    }
                    else {
                        $sales_data['total_cost'] = $sales_data['total_after_tax'] - $sales_data['discount_value'];
                    }
                }

                $items_card = InvItemCard::where(['active' => 1, 'com_code' => $com_code])->get(['item_code', 'name', 'item_type', 'has_fixed_price']);
                $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->get(['treasuries_id', 'shift_code'])->first();
                if (empty($check_shift)) {
                    return Response()->json(['error' => ''], 404);
                }
                $check_shift['treasuries_name'] = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                $check_shift['treasuries_money'] = TreasuryTransaction::where(['shift_code' => $check_shift['shift_code'], 'com_code' => $com_code])->sum('money');


                return view('admin.purchase_order_header_general_return.pill_adding_items', ['items_card' => $items_card, 'check_shift' => $check_shift, 'sales_data' => $sales_data, 'items' => $items]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }

    public function get_item_unit(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $item_code = $request->item_code;

            $item_card_data = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->get(['does_has_retailunit', 'retail_unit_id', 'unit_id'])->first();
            if (!empty($item_card_data)) {

                if ($item_card_data['does_has_retailunit'] == 1) {
                    $item_card_data['parent_unit_name'] = InvUnit::where('id', $item_card_data['unit_id'])->value('name');
                    $item_card_data['retail_unit_name'] = InvUnit::where('id', $item_card_data['retail_unit_id'])->value('name');
                }
                else {
                    $item_card_data['parent_unit_name'] = InvUnit::where('id', $item_card_data['unit_id'])->value('name');
                }
            }

            return view("admin.purchase_order_header_general_return.get_item_unit", ['item_card_data' => $item_card_data]);
        }
    }

    public function get_item_batch(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $item_code = $request->item_code;
            $store_id = $request->store_id;
            $unit_id = $request->unit_id;

            $item_card_data = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->get(['unit_id', 'retail_unit_id', 'retail_uom_quntToParent', 'item_type'])->first();
            $unit_name = InvUnit::where(['id' => $unit_id, 'com_code' => $com_code])->value('name');

            $item_card_batches = array();
            if (!empty($item_card_data)) {
                if (empty($store_id)) {
                    $item_card_batches = InvItemCardBatch::where(['item_code' => $item_code, 'com_code' => $com_code])->orderBy('production_date', 'DESC')->get();
                }
                else {
                    $item_card_batches = InvItemCardBatch::where(['item_code' => $item_code, 'store_id' => $store_id, 'com_code' => $com_code])->orderBy('production_date', 'DESC')->get();
                }
                /////////////////////////////////////////////

                if ($unit_id == $item_card_data['unit_id'] || $unit_id == null) {
                    if ($item_card_data['item_type'] == 2) {
                        foreach ($item_card_batches as $batch) {
                            $batch['all_data'] = 'عدد' . ' (' . $batch['quantity'] . ') ' . $unit_name . ' ' . 'انتاج' . ' (' . $batch['production_date'] . ') ' . 'بسعر' . ' (' . $batch['unit_cost_price'] .')';
                        }
                    }
                    else {
                        foreach ($item_card_batches as $batch) {
                            $batch['all_data'] = 'عدد' . ' (' . $batch['quantity'] . ' )' . $unit_name . ' '. 'بسعر' . ' (' . $batch['unit_cost_price']  .')';
                        }
                    }
                }
                else {
                    if ($item_card_data['item_type'] == 2) {
                        foreach ($item_card_batches as $batch) {
                            $quantity = $batch['quantity'] * $item_card_data['retail_uom_quntToParent'];
                            $quantity = round($quantity, 0);
                            $batch['quantity'] = $quantity;
                            $price = $batch['unit_cost_price'] / $item_card_data['retail_uom_quntToParent'];
                            $price = round($price, 2);
                            $batch['all_data'] = 'عدد' . ' (' . $quantity . ') ' . $unit_name . ' ' . 'انتاج' . ' (' . $batch['production_date'] . ') ' . 'بسعر' . ' (' . $price . ')';
                        }
                    }
                    else {
                        foreach ($item_card_batches as $batch) {
                            $quantity = $batch['quantity'] * $item_card_data['retail_uom_quntToParent'];
                            $quantity = round($quantity, 0);
                            $batch['quantity'] = $quantity;
                            $price = $batch['unit_cost_price'] / $item_card_data['retail_uom_quntToParent'];
                            $price = round($price, 2);
                            $batch['all_data'] = 'عدد' . ' (' . $batch['quantity'] . ') ' . $unit_name . ' '. 'بسعر' . ' (' . $price . ')';
                        }
                    }
                }
            }

            return view("admin.purchase_order_header_general_return.get_item_batch", ['item_card_batches' => $item_card_batches]);
        }
    }

    public function get_item_price(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $item_code = $request->item_code;
            $sales_type = $request->sales_type;
            $unit_id = $request->unit_id;

            $item_card_data = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->get()->first();

            $unit_price = 0;
            if (!empty($item_card_data)) {
                if ($unit_id == $item_card_data['unit_id'] || $unit_id == null) {
                    $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_one_in_master_unit');
                }
                else if ($unit_id == $item_card_data['retail_unit_id']) {
                    $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_one_in_retail_unit');
                }
            }

            return $unit_price;
        }
    }

    public function add_new_item_row(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                $max = InvoiceOrderDetail::max('id');
                $data['id'] = $max + 1;
                $data['quantity'] = $request->quantity;
                $data['unit_price'] = $request->unit_price;
                $data['total_price'] = $request->total_price;
                $data['store_name'] = $request->store_name;
                $data['item_name'] = $request->item_name;
                $data['unit_name'] = $request->unit_name;

                return view('admin.purchase_order_header_general_return.add_new_item_row', ['data' => $data]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }

    public function store_item(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                $dataInsert['invoice_order_id'] = $request->invoice_order_id;
                $dataInsert['item_code'] = $request->item_code;
                $dataInsert['unit_id'] = $request->unit_id;
                $dataInsert['quantity'] = $request->quantity;
                $dataInsert['unit_price'] = $request->unit_price;
                $dataInsert['total_price'] = $request->total_price;
                $dataInsert['batch_id'] = $request->batch_id;
                $dataInsert['store_id'] = $request->store_id;
                $dataInsert['production_date'] = $request->production_date;
                $dataInsert['expire_date'] = $request->expire_date;
                $dataInsert['added_by'] = auth()->user()->id;
                $dataInsert['created_at'] = date("Y-m-d H:i:s");
                $dataInsert['com_code'] = auth()->user()->com_code;


                InvoiceOrderDetail::create($dataInsert);
                $total_before_discount = InvoiceOrderDetail::where('invoice_order_id', $request->invoice_order_id)->sum('total_price');
                $update_invoice['total_before_discount'] = $total_before_discount;
                InvoiceOrderHeader::where('id', $request->invoice_order_id)->update($update_invoice);


                $data = InvoiceOrderDetail::where($dataInsert)->first();
                $data['store_name'] = $request->store_name;
                $data['item_name'] = $request->item_name;
                $data['unit_name'] = $request->unit_name;

                $com_code = auth()->user()->com_code;
                // get supplier name
                $data['supplier_code'] = PurchaseOrderHeader::where(['invoice_id' => $data['invoice_order_id'], 'com_code' => $com_code])->value('supplier_code');

                if (!empty($data['supplier_code'])) {
                    // get the name from the supplier_code
                    // 1- get the person id from the supplier model
                    $person_id = Supplier::where(['supplier_code' => $data['supplier_code'], 'com_code' => $com_code])->value('person_id');
                    $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                    $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                    $data['supplier_name'] = $first_name . ' ' . $last_name;
                }
                else {
                    $data['supplier_name'] = 'لا يوجد';
                }


                // Moving item from the batch
                $item_card_data = InvItemCard::where(['item_code' => $data['item_code'], 'com_code' => $com_code])->get(['unit_id', 'retail_unit_id', 'retail_uom_quntToParent', 'item_type', 'does_has_retailunit'])->first();
                //
                if (!empty($item_card_data)) {
                    // Now we will check if the unit is master or retail because we say that every item will be taken with master unit
                    // if master we make the quantity is the same quantity
                    $quantity = 0;
                    if ($data['unit_id'] == $item_card_data['unit_id']) {
                        $quantity = $data['quantity'];
                    }
                    else if ($data['unit_id'] == $item_card_data['retail_unit_id']) {
                        $quantity = $data['quantity'] / $item_card_data['retail_uom_quntToParent'];
                    }


                    // before i make insert or update i should get the quantity in all store and current store from the batch
                    $quantity_in_batch_before = InvItemCardBatch::where(['item_code' => $data['item_code'], 'com_code' => $com_code])->sum('quantity');
                    $quantity_in_batch_current_store_before = InvItemCardBatch::where(['item_code' => $data['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');

                    // now we check if there is like this batch in the item batches
                    $batch_quantity = InvItemCardBatch::where('id', $data['batch_id'])->value('quantity');

                    $updateBatch['quantity'] = $batch_quantity - $quantity;
                    $updateBatch['updated_by'] = auth()->user()->id;
                    $updateBatch['updated_at'] = date('Y-m-d H:i:s');

                    InvItemCardBatch::where(['id' => $data['batch_id']])->update($updateBatch);

                    // get the quantity in all store and current store from the batch and we will get the name of the master unit
                    $quantity_in_batch_after = InvItemCardBatch::where(['item_code' => $data['item_code'], 'com_code' => $com_code])->sum('quantity');
                    $quantity_in_batch_current_store_after = InvItemCardBatch::where(['item_code' => $data['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');
                    $parent_unit_name = InvUnit::where('id', $item_card_data['unit_id'])->value('name');


                    // Then we will save this change with item card in the item card movements table
                    $insertItemMovement['inv_item_card_movements_categories_id'] = 1;
                    $insertItemMovement['item_code'] = $data['item_code'];
                    $insertItemMovement['inv_item_card_movements_types_id'] = 3;
                    $insertItemMovement['order_header_id'] = $data['invoice_order_id'];
                    $insertItemMovement['order_details_id'] = InvoiceOrderDetail::max('id');
                    $insertItemMovement['store_id'] = $data['store_id'];
                    $insertItemMovement['batch_id'] = $data['batch_id'];
                    $insertItemMovement['quantity_before_movement'] = $quantity_in_batch_before . ' ' . $parent_unit_name;
                    $insertItemMovement['quantity_after_movement'] = $quantity_in_batch_after . ' ' . $parent_unit_name;
                    $insertItemMovement['quantity_before_movement_in_current_store'] = $quantity_in_batch_current_store_before . ' ' . $parent_unit_name;
                    $insertItemMovement['quantity_after_movement_in_current_store'] = $quantity_in_batch_current_store_after . ' ' . $parent_unit_name;
                    $insertItemMovement['byan'] = 'صرف نضير مرتجع مشتريات للمورد ' . $data['supplier_name'] . ' فاتورة رقم ' . $data['invoice_order_id'];
                    $insertItemMovement['created_at'] = date('Y-m-d H:i:s');
                    $insertItemMovement['date'] = date('Y-m-d');
                    $insertItemMovement['added_by'] = auth()->user()->id;
                    $insertItemMovement['com_code'] = $com_code;

                    InvItemCardMovement::create($insertItemMovement);


                    // update the quantity in item_card
                    $all_quantity = InvItemCardBatch::where(['id' => $data['batch_id']])->sum('quantity');
                    if ($item_card_data['does_has_retailunit'] == 1) {
                        $all_retail = $all_quantity * $item_card_data['retail_uom_quntToParent'];
                        $all_master = intdiv($all_retail, $item_card_data['retail_uom_quntToParent']);
                        $remain_retail = fmod($all_retail, $item_card_data['retail_uom_quntToParent']);

                        $update_item_card_quantity['all_quantity_with_master_unit'] = $all_master;
                        $update_item_card_quantity['all_quantity_with_retail_unit'] = round($all_retail, 0);
                        $update_item_card_quantity['remain_quantity_in_retail'] = round($remain_retail, 0);

                    }
                    else {
                        $update_item_card_quantity['all_quantity_with_master_unit'] = intval($all_quantity);
                    }

                    InvItemCard::where(['item_code' => $data['item_code'], 'com_code' => $com_code])->update($update_item_card_quantity);
                }

                return view('admin.purchase_order_header_general_return.add_new_item_row', ['data' => $data]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }


    public function ajax_search(Request $request) {
        if ($request->ajax()) {

            $purchase_code_search = $request->purchase_code_search;
            $supplier_code_search = $request->supplier_code_search;
            $store_id_search = $request->store_id_search;
            $from_date_search = $request->from_date_search;
            $to_date_search = $request->to_date_search;


            if ($purchase_code_search == '') {
                $filed1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            }
            else {
                $filed1 = 'pill_code';
                $operator1 = 'LIKE';
                $value1 = '%'. $purchase_code_search . '%';
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
            $data = InvoiceOrderHeader::whereIn('id', $data_in)->where("$filed1", "$operator1", "$value1")->where("$filed4", "$operator4", "$value4")->where("$filed5", "$operator5", "$value5")->where(['com_code' => auth()->user()->com_code, 'invoice_type' => 1, 'order_type' => 3])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);

            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['store_id'] = PurchaseOrderHeader::where('invoice_id', $d['id'])->value('store_id');
                    $d['purchase_code'] = PurchaseOrderHeader::where('invoice_id', $d['id'])->value('purchase_code');
                    $d['supplier_code'] = PurchaseOrderHeader::where('invoice_id', $d['id'])->value('supplier_code');
                    $d['store_name'] = Store::where('id', $d['store_id'])->value('name');

                    if ($d['supplier_code'] != null) {
                        $person_id = Supplier::where(['supplier_code' => $d['supplier_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                        $supplier = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                        $d['supplier_name'] = $supplier->first_name . ' ' . $supplier->last_name;
                    }
                }
            }
            return view('admin.purchase_order_header_general_return.ajax_search', ['data' => $data]);

        }
    }

    public function remove_item(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {

                $data = InvoiceOrderDetail::where(['id' => $request->id,'invoice_order_id' => $request->invoice_order_id])->first();
                $com_code = auth()->user()->com_code;


                // adding item to the batch
                $item_card_data = InvItemCard::where(['item_code' => $data['item_code'], 'com_code' => $com_code])->get(['unit_id', 'retail_unit_id', 'retail_uom_quntToParent', 'item_type', 'does_has_retailunit'])->first();
                //
                if (!empty($item_card_data)) {
                    // Now we will check if the unit is master or retail because we say that every item will be taken with master unit
                    // if master we make the quantity is the same quantity
                    $quantity = 0;
                    if ($data['unit_id'] == $item_card_data['unit_id']) {
                        $quantity = $data['quantity'];
                    }
                    else if ($data['unit_id'] == $item_card_data['retail_unit_id']) {
                        $quantity = $data['quantity'] / $item_card_data['retail_uom_quntToParent'];
                    }


                    $batch_quantity = InvItemCardBatch::where('id', $data['batch_id'])->value('quantity');

                    $updateBatch['quantity'] = $batch_quantity + $quantity;
                    $updateBatch['updated_by'] = auth()->user()->id;
                    $updateBatch['updated_at'] = date('Y-m-d H:i:s');

                    InvItemCardBatch::where(['id' => $data['batch_id']])->update($updateBatch);


                    InvItemCardMovement::where(['order_header_id' => $data['invoice_order_id'], 'order_details_id' => $data['id']])->delete();


                    // update the quantity in item_card
                    $all_quantity = InvItemCardBatch::where(['id' => $data['batch_id']])->sum('quantity');
                    if ($item_card_data['does_has_retailunit'] == 1) {
                        $all_retail = $all_quantity * $item_card_data['retail_uom_quntToParent'];
                        $all_master = intdiv($all_retail, $item_card_data['retail_uom_quntToParent']);
                        $remain_retail = fmod($all_retail, $item_card_data['retail_uom_quntToParent']);

                        $update_item_card_quantity['all_quantity_with_master_unit'] = $all_master;
                        $update_item_card_quantity['all_quantity_with_retail_unit'] = round($all_retail, 0);
                        $update_item_card_quantity['remain_quantity_in_retail'] = round($remain_retail, 0);

                    }
                    else {
                        $update_item_card_quantity['all_quantity_with_master_unit'] = intval($all_quantity);
                    }

                    InvItemCard::where(['item_code' => $data['item_code'], 'com_code' => $com_code])->update($update_item_card_quantity);

                    InvoiceOrderDetail::where(['id' => $request->id,'invoice_order_id' => $request->invoice_order_id])->delete();

                    $total_before_discount = InvoiceOrderDetail::where('invoice_order_id', $request->invoice_order_id)->sum('total_price');
                    $update_invoice['total_before_discount'] = $total_before_discount;
                    InvoiceOrderHeader::where('id', $request->invoice_order_id)->update($update_invoice);
                }
            }
            catch (Exception $e) {

            }

        }
    }

    public function check_shift_and_reload_money(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
               //Check if has shift
               $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => auth()->user()->com_code, 'is_finished' => 0])->get(['treasuries_id', 'shift_code'])->first();
               if (empty($check_shift)) {
                   return Response()->json(['error' => ''], 404);
               }
               $check_shift['treasuries_name'] = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => auth()->user()->com_code])->value('name');
               $check_shift['treasuries_money'] = TreasuryTransaction::where(['shift_code' => $check_shift['shift_code'], 'com_code' => auth()->user()->com_code])->sum('money');

               return view('admin.purchase_order_header.check_shift_and_reload_money', ['check_shift' => $check_shift]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }

    public function approve_pill(Request $request, $auto_serial)
    {
        try {

            # code...
            $com_code = auth()->user()->com_code;
            $data = InvoiceOrderHeader::where(['id' => $auto_serial, 'com_code' => $com_code, 'order_type' => 3, 'invoice_type' => 1])->get()->first();
            $data['supplier_code'] = PurchaseOrderHeader::where(['invoice_id' => $auto_serial, 'com_code' => $com_code])->value('supplier_code');

            $items = InvoiceOrderDetail::where(['invoice_order_id' => $auto_serial, 'com_code' => $com_code])->first();
            if (empty($items)) {
                return redirect()->back()->with('error', 'الفاتورة لا تحتوي على اصناف لاعتمادها');
            }

            if (empty($data)) {
                return redirect()->back()->with('error', 'لا توجد بيانات كهذه');
            }
            if ($data['is_approved'] == 1) {
                return redirect()->back()->with('error', 'الفاتورة معتمدة من قبل');
            }

            $updateInvoice['tax_percent'] = $request->tax_percent;
            $updateInvoice['total_cost'] = $request->total_cost;
            $updateInvoice['money_for_account'] = $request->total_cost * (1);
            $updateInvoice['discount_type'] = $request->discount_type;
            $updateInvoice['pill_type'] = $request->pill_type;

            if ($request->discount_type == 1) {
                $updateInvoice['discount_percent'] = $request->discount_percent;
            }
            else if ($request->discount_type == 2) {
                $updateInvoice['discount_value'] = $request->discount_value;
            }

            if ($request->pill_type == 1) {
                if ($request->what_paid != $request->total_cost) {
                    return redirect()->back()->with('error', 'لا بد ان يكون المبلغ المدفوع مساويا للمبلغ الكلي في حال كان نوع الفاتورة كاش');
                }
                if ($request->what_remain != 0) {
                    return redirect()->back()->with('error', 'لا بد ان يكون المبلغ المتبقي مساويا للصفر في حال كان نوع الفاتورة كاش');
                }
            }
            if ($request->pill_type == 2) {
                if ($request->what_paid == $request->total_cost) {
                    return redirect()->back()->with('error', 'لا بد ان يكون المبلغ المدفوع اقل من للمبلغ الكلي في حال كان نوع الفاتورة اجل');
                }
            }

            $updateInvoice['what_paid'] = $request->what_paid;
            $updateInvoice['what_remain'] = $request->what_remain;
            $updateInvoice['is_approved'] = 1;
            $updateInvoice['approved_by'] = auth()->user()->id;
            $updateInvoice['approved_at'] = date('Y-m-d H:i:s');

            $flag = InvoiceOrderHeader::where(['id' => $auto_serial, 'com_code' => $com_code, 'order_type' => 3, 'invoice_type' => 1])->update($updateInvoice);


            if ($flag) {
                // get the account number and name from the supplier_code
                // 1- get the person id from the supplier model

                if ($data['supplier_code'] != '' && $data['supplier_code'] != null) {

                    $person_id = Supplier::where(['supplier_code' => $data['supplier_code'], 'com_code' => $com_code])->value('person_id');
                    $data['account_number'] = Person::where(['id' => $person_id,'com_code' => $com_code])->value('account_number');
                    $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                    $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                    $data['supplier_name'] = $first_name . ' ' . $last_name;


                    // change the supplier current balance in accounts
                    $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                    $update_account['current_balance'] = $get_current + $data['total_cost'];
                    Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);
                }




                // there is many action to take
                // first if the what_paid > 0, we will make transaction action and will be in minus,
                // because we make exchange
                if ($request->what_paid > 0) {
                    $max_transaction_code = TreasuryTransaction::where('com_code', $com_code)->max('transaction_code');
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

                    $last_collection_arrive = Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->value('last_collection_arrive');


                    if (empty($last_collection_arrive)) {
                        return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                    }
                    else {
                        $insertTransaction['last_arrive'] = $last_collection_arrive + 1;
                    }

                    //تحصيل نظير مرتجع مشتريات من مورد
                    $insertTransaction['move_type'] = 10;
                    // Account number will be like the account number for the supplier in the purchaseHeader
                    $insertTransaction['account_number'] = $data['account_number'];
                    $insertTransaction['transaction_type'] = 1;
                    $insertTransaction['money'] = $updateInvoice['what_paid'] * (1);
                    $insertTransaction['is_approved'] = 1;
                    $insertTransaction['invoice_id'] = $auto_serial;
                    $insertTransaction['treasuries_id'] = $request->treasuries_id;
                    $insertTransaction['move_date'] = date('Y-m-d');


                    $insertTransaction['byan'] = ' تحصيل نظير مرتجع مشتريات من مورد' . ' ' . $data['supplier_name'];
                    $insertTransaction['is_account'] = 1;
                    $insertTransaction['money_for_account'] = $updateInvoice['what_paid'] * (-1);

                    $insertTransaction['added_by'] = auth()->user()->id;
                    $insertTransaction['com_code'] = $com_code;
                    $insertTransaction['created_at'] = date('Y-m-d H:i:s');

                    $flag = TreasuryTransaction::create($insertTransaction);

                    if($flag) {
                        $update_treasuries['last_exchange_arrive'] = $last_collection_arrive + 1;
                        Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->update($update_treasuries);

                        // change the supplier current balance in accounts
                        $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                        $update_account['current_balance'] = $get_current - $data['what_paid'];
                        Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);
                    }
                    return redirect()->back()->with('success', 'تم اعتماد واضافة الفاتورة بنجاح');
                }

            }

        }
        catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

}
