<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\InvoiceOrderHeader;
use App\Models\SalesOrderHeader;
use App\Models\Admin;
use App\Models\AdminPanelSetting;
use App\Models\Person;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Delegate;
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
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Arr;

class SalesOrderHeaderGeneralReturnController extends Controller
{

    public function index()
    {
        //
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات العام' , 'عرض') == true) {
            try {
                $data = InvoiceOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_type' => 2, 'order_type' => 3])->orderBy('id' , 'desc')->paginate(PAGINATION_COUNT);
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
                            $d['customer_name'] = 'بدون عميل';
                        }

                        if ($d['delegate_code'] != null) {
                            $person_id = Delegate::where(['delegate_code' => $d['delegate_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                            $delegate = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                            $d['delegate_name'] = $delegate->first_name . ' ' . $delegate->last_name;
                        }
                        else {
                            $d['delegate_name'] = 'بدون مندوب';
                        }
                    }
                }


                $com_code = auth()->user()->com_code;
                $customers = Person::where(['person_type' => 1, 'com_code' => $com_code])->get(['first_name', 'last_name', 'id']);
                foreach ($customers as $sup) {
                    $sup['customer_code'] = Customer::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('customer_code');
                    $sup['customer_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                }

                $delegates = Person::where(['person_type' => 3, 'com_code' => $com_code])->get(['first_name', 'last_name', 'id']);
                foreach ($delegates as $sup) {
                    $sup['delegate_code'] = Delegate::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('delegate_code');
                    $sup['delegate_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                }

                $stores = Store::where(['com_code' => $com_code])->get(['name', 'id']);
                $items_card = InvItemCard::where(['active' => 1, 'com_code' => $com_code])->get(['item_code', 'name', 'item_type']);

                return view('admin.sales_order_header_general_return.index', ['data' => $data, 'customers' => $customers, 'delegates' => $delegates, 'stores' => $stores, 'items_card' => $items_card]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات العام' , 'حذف') == true) {
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
                if ($count > 0) {
                    return redirect()->back()->with('error', 'لا يمكن حذف الفاتورة التي تحتوي على اصناف الا عند حذف الاصناف من شاشتهم');
                }

                SalesOrderHeader::where(['invoice_id' => $id, 'com_code' => $com_code])->delete();
                InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code])->delete();
                return redirect()->back()->with('success', 'تم الحذف بنجاح');
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }


    }

    public function create_pill(Request $request)
    {
        # code...
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات العام' , 'اضافة') == true) {
            if ($request->ajax()) {
                try {
                    $com_code = auth()->user()->com_code;

                    $customers = Person::where(['person_type' => 1, 'com_code' => $com_code])->get(['first_name', 'last_name', 'id']);
                    foreach ($customers as $sup) {
                        $sup['customer_code'] = Customer::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('customer_code');
                        $sup['customer_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                    }

                    $delegates = Person::where(['person_type' =>3, 'com_code' => $com_code])->get(['first_name', 'last_name', 'id']);
                    foreach ($delegates as $sup) {
                        $sup['delegate_code'] = Delegate::where(['person_id' => $sup['id'], 'com_code' => $com_code])->value('delegate_code');
                        $sup['delegate_name'] = $sup['first_name'] . ' ' . $sup['last_name'];
                    }

                    $stores = Store::where(['com_code' => $com_code])->get(['name', 'id']);
                    $items_card = InvItemCard::where(['active' => 1, 'com_code' => $com_code])->get(['item_code', 'name', 'item_type', 'has_fixed_price']);

                    $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->get(['treasuries_id', 'shift_code'])->first();
                    if (empty($check_shift)) {
                        return Response()->json(['error' => ''], 404);
                    }

                    return view('admin.sales_order_header_general_return.create_pill', ['items_card' => $items_card, 'customers' => $customers, 'delegates' => $delegates, 'stores' => $stores]);
                }
                catch (Exception $e) {
                    return redirect()->back()->with('error', $e->getMessage());
                }

            }
        }
        else {
            return redirect()->back();
        }


    }

    public function store(Request $request)
    {
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات العام' , 'اضافة') == true) {
            if ($request->ajax()) {
                try {
                    //set account number
                    $max_invoice_id = InvoiceOrderHeader::max('id');

                    if (!empty($max_invoice_id)) {
                        $inserted_invoice['id'] = $max_invoice_id + 1;
                    } else {
                        $inserted_invoice['id'] = 1;
                    }

                    $max_pill_code = InvoiceOrderHeader::where(['com_code' => auth()->user()->com_code, 'order_type' => 3, 'invoice_type' => 2])->max('pill_code');
                    if (!empty($max_pill_code)) {
                        $inserted_invoice['pill_code'] = $max_pill_code + 1;
                    } else {
                        $inserted_invoice['pill_code'] = 1;
                    }

                    $inserted_invoice['order_type'] = 3;
                    $inserted_invoice['invoice_type'] = 2;
                    $inserted_invoice['order_date'] = $request->pill_date;
                    $inserted_invoice['pill_type'] = $request->pill_type;
                    $inserted_invoice['notes'] = $request->notes;
                    $inserted_invoice['added_by'] = auth()->user()->id;
                    $inserted_invoice['created_at'] = date("Y-m-d H:i:s");
                    $inserted_invoice['com_code'] = auth()->user()->com_code;

                    $flag = InvoiceOrderHeader::create($inserted_invoice);
                    if ($flag) {
                        $inserted_sales['invoice_id'] = $inserted_invoice['id'];

                        $max_auto_serial = SalesOrderHeader::max('auto_serial');
                        if (!empty($max_auto_serial)) {
                            $inserted_sales['auto_serial'] = $max_auto_serial + 1;
                        } else {
                            $inserted_sales['auto_serial'] = 1;
                        }

                        $max_sales_code = SalesOrderHeader::where(['com_code' => auth()->user()->com_code])->max('sales_code');
                        if (!empty($max_sales_code)) {
                            $inserted_sales['sales_code'] = $max_sales_code + 1;
                        } else {
                            $inserted_sales['sales_code'] = 1;
                        }
                        $inserted_sales['customer_code'] = $request->customer_code;
                        $inserted_sales['delegate_code'] = $request->delegate_code;
                        $inserted_sales['sales_type'] = $request->sales_type;
                        $inserted_sales['added_by'] = auth()->user()->id;
                        $inserted_sales['created_at'] = date("Y-m-d H:i:s");
                        $inserted_sales['com_code'] = auth()->user()->com_code;
                        SalesOrderHeader::create($inserted_sales);

                        return $inserted_invoice['id'];
                    }

                }
                catch (Exception $e) {
                    echo($e->getMessage());
                }
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function load_pill_adding_items_modal(Request $request)
    {
        # code...
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات العام' , 'اضافة') == true) {
            if ($request->ajax()) {
                try {
                    $com_code = auth()->user()->com_code;

                    $sales_data = InvoiceOrderHeader::where('id', $request->id)->first();
                    $sales_data['sales_type'] = SalesOrderHeader::where('invoice_id', $request->id)->value('sales_type');
                    if ($sales_data['sales_type'] == 1) {
                        $sales_data['sales_type_name'] = 'جملة';
                    }
                    else if ($sales_data['sales_type'] == 2) {
                        $sales_data['sales_type_name'] = 'نص جملة';
                    }
                    else if ($sales_data['sales_type'] == 3) {
                        $sales_data['sales_type_name'] = 'تجزئة';
                    }

                    $items = InvoiceOrderDetail::where('invoice_order_id', $request->id)->get();
                    if (!empty($items)) {
                        foreach($items as $i) {
                            $i['store_name'] = Store::where('id', $i['store_id'])->value('name');
                            $i['unit_name'] = InvUnit::where('id', $i['unit_id'])->value('name');
                            $i['item_name'] = InvItemCard::where('item_code', $i['item_code'])->value('name');
                            $i['sales_type_name'] = $sales_data['sales_type_name'];
                        }
                    }

                    if (!empty($sales_data)) {
                        $sales_data['customer_code'] = SalesOrderHeader::where('invoice_id', $request->id)->value('customer_code');
                        $sales_data['delegate_code'] = SalesOrderHeader::where('invoice_id', $request->id)->value('delegate_code');
                        $sales_data['all_items'] = InvoiceOrderDetail::where('invoice_order_id', $request->id)->count();
                        $sales_data['tax_value'] = $sales_data['total_before_discount'] * ($sales_data['tax_percent'] / 100);
                        $sales_data['total_after_tax'] = $sales_data['total_before_discount'] + $sales_data['tax_value'];

                        if ($sales_data['discount_type'] == 1) {
                            $sales_data['total_cost'] = $sales_data['total_after_tax'] - ($sales_data['total_after_tax'] * ($sales_data['discount_percent'] / 100));
                        }
                        else {
                            $sales_data['total_cost'] = $sales_data['total_after_tax'] - $sales_data['discount_value'];
                        }

                        if (!empty($sales_data['customer_code'])) {
                            $person_id = Customer::where(['customer_code' => $sales_data['customer_code'], 'com_code' => $com_code])->value('person_id');
                            $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                            $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                            $sales_data['customer_name'] = $first_name . ' ' . $last_name;
                        }

                        if (!empty($sales_data['delegate_code'])) {
                            $person_id = Delegate::where(['delegate_code' => $sales_data['delegate_code'], 'com_code' => $com_code])->value('person_id');
                            $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                            $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                            $sales_data['delegate_name'] = $first_name . ' ' . $last_name;
                        }
                    }

                    $stores = Store::where(['active' => 1, 'com_code' => $com_code])->get(['name', 'id']);
                    $items_card = InvItemCard::where(['active' => 1, 'com_code' => $com_code])->get(['item_code', 'name', 'item_type', 'has_fixed_price']);
                    $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->get(['treasuries_id', 'shift_code'])->first();
                    if (empty($check_shift)) {
                        return Response()->json(['error' => ''], 404);
                    }
                    $check_shift['treasuries_name'] = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                    $check_shift['treasuries_money'] = TreasuryTransaction::where(['shift_code' => $check_shift['shift_code'], 'com_code' => $com_code])->sum('money');

                    return view('admin.sales_order_header_general_return.pill_adding_items', ['items_card' => $items_card, 'stores' => $stores, 'check_shift' => $check_shift, 'sales_data' => $sales_data, 'items' => $items]);
                }
                catch (Exception $e) {
                    return redirect()->back()->with('error', $e->getMessage());
                }

            }
        }
        else {
            return redirect()->back();
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

            return view("admin.sales_order_header_general_return.get_item_unit", ['item_card_data' => $item_card_data]);
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

            return view("admin.sales_order_header_general_return.get_item_batch", ['item_card_batches' => $item_card_batches]);
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
                    if ($sales_type == '1') {
                        $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_group_in_master_unit');
                    }
                    else if ($sales_type == '2') {
                        $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_half_group_in_master_unit');
                    }
                    else if ($sales_type == '3') {
                        $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_one_in_master_unit');
                    }
                    else {
                        $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_one_in_master_unit');
                    }
                }
                else if ($unit_id == $item_card_data['retail_unit_id']) {
                    if ($sales_type == 1) {
                        $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_group_in_retail_unit');
                    }
                    else if ($sales_type == 2) {
                        $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_half_group_in_retail_unit');
                    }
                    else if ($sales_type == 3) {
                        $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_one_in_retail_unit');
                    }
                    else {
                        $unit_price = InvItemCard::where(['item_code' => $item_code, 'com_code' => $com_code])->value('price_per_one_in_retail_unit');
                    }
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
                $data['store_id'] = $request->store_id;
                $data['sales_type'] = $request->sales_type;
                $data['item_code'] = $request->item_code;
                $data['unit_id'] = $request->unit_id;
                $data['batch_id'] = $request->batch_id;
                $data['quantity'] = $request->quantity;
                $data['unit_price'] = $request->unit_price;
                $data['total_price'] = $request->total_price;
                $data['store_name'] = $request->store_name;
                $data['sales_type_name'] = $request->sales_type_name;
                $data['item_name'] = $request->item_name;
                $data['unit_name'] = $request->unit_name;

                return view('admin.sales_order_header_general_return.add_new_item_row', ['data' => $data]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }

    public function store_item(Request $request)
    {
        # code...
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات العام' , 'اضافة') == true) {
            if ($request->ajax()) {
                try {
                    $dataInsert['invoice_order_id'] = $request->invoice_order_id;
                    $dataInsert['item_code'] = $request->item_code;
                    $dataInsert['unit_id'] = $request->unit_id;
                    $dataInsert['quantity'] = $request->quantity;
                    $dataInsert['unit_price'] = $request->unit_price;
                    $dataInsert['total_price'] = $request->total_price;
                    if ($request->batch_id != 'new') {
                        $dataInsert['batch_id'] = $request->batch_id;
                    }
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
                    $data['sales_type_name'] = $request->sales_type_name;
                    $data['item_name'] = $request->item_name;
                    $data['unit_name'] = $request->unit_name;

                    $com_code = auth()->user()->com_code;
                    // get customer name
                    $data['customer_code'] = SalesOrderHeader::where(['invoice_id' => $data['invoice_order_id'], 'com_code' => $com_code])->value('customer_code');

                    if (!empty($data['customer_code'])) {
                        // get the name from the customer_code
                        // 1- get the person id from the customer model
                        $person_id = Customer::where(['customer_code' => $data['customer_code'], 'com_code' => $com_code])->value('person_id');
                        $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                        $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                        $data['customer_name'] = $first_name . ' ' . $last_name;
                    }
                    else {
                        $data['customer_name'] = 'لا يوجد';
                    }

                    return view('admin.sales_order_header_general_return.add_new_item_row', ['data' => $data]);
                }
                catch (Exception $e) {
                    return redirect()->back()->with('error', $e->getMessage());
                }

            }
        }
        else {
            return redirect()->back();
        }


    }

    public function remove_item(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                InvoiceOrderDetail::where(['id' => $request->id,'invoice_order_id' => $request->invoice_order_id])->delete();

                $total_before_discount = InvoiceOrderDetail::where('invoice_order_id', $request->invoice_order_id)->sum('total_price');
                $update_invoice['total_before_discount'] = $total_before_discount;
                InvoiceOrderHeader::where('id', $request->invoice_order_id)->update($update_invoice);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }


    public function ajax_search(Request $request) {
        if ($request->ajax()) {

            $sales_code_search = $request->sales_code_search;
            $customer_code_search = $request->customer_code_search;
            $delegate_code_search = $request->delegate_code_search;
            $from_date_search = $request->from_date_search;
            $to_date_search = $request->to_date_search;


            if ($sales_code_search == '') {
                $filed1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            }
            else {
                $filed1 = 'pill_code';
                $operator1 = 'LIKE';
                $value1 = '%'. $sales_code_search . '%';
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
                $value3 = '%'. $delegate_code_search . '%';
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
            $data = InvoiceOrderHeader::whereIn('id', $data_in)->where("$filed1", "$operator1", "$value1")->where("$filed4", "$operator4", "$value4")->where("$filed5", "$operator5", "$value5")->where(['com_code' => auth()->user()->com_code, 'invoice_type' => 2, 'order_type' => 3])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);

            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['sales_code'] = SalesOrderHeader::where('invoice_id', $d['id'])->value('sales_code');
                    $d['customer_code'] = SalesOrderHeader::where('invoice_id', $d['id'])->value('customer_code');
                    $d['delegate_code'] = SalesOrderHeader::where('invoice_id', $d['id'])->value('delegate_code');

                    if ($d['customer_code'] != null) {
                        $person_id = Customer::where(['customer_code' => $d['customer_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                        $customer = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                        $d['customer_name'] = $customer->first_name . ' ' . $customer->last_name;
                    }
                    if ($d['delegate_code'] != null) {
                        $person_id = Delegate::where(['delegate_code' => $d['delegate_code'], 'com_code' => auth()->user()->com_code])->value('person_id');
                        $delegate = Person::where(['id' => $person_id, 'com_code' => auth()->user()->com_code])->select(['first_name', 'last_name'])->first();
                        $d['delegate_name'] = $delegate->first_name . ' ' . $delegate->last_name;
                    }
                }
            }
            return view('admin.sales_order_header_general_return.ajax_search', ['data' => $data]);

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

               return view('admin.sales_order_header_general_return.check_shift_and_reload_money', ['check_shift' => $check_shift]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }

    public function approve_pill(Request $request, $auto_serial)
    {
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات العام' , 'اعتماد') == true) {
            try {
                # code...
                $com_code = auth()->user()->com_code;
                $data = InvoiceOrderHeader::where(['id' => $auto_serial, 'com_code' => $com_code, 'order_type' => 3, 'invoice_type' => 2])->get()->first();
                $data['customer_code'] = SalesOrderHeader::where(['invoice_id' => $auto_serial, 'com_code' => $com_code])->value('customer_code');
                $data['delegate_code'] = SalesOrderHeader::where(['invoice_id' => $auto_serial, 'com_code' => $com_code])->value('delegate_code');
                $data['sales_type'] = SalesOrderHeader::where(['invoice_id' => $auto_serial, 'com_code' => $com_code])->value('sales_type');

                if (empty($data)) {
                    return redirect()->back()->with('error', 'لا توجد بيانات كهذه');
                }
                if ($data['is_approved'] == 1) {
                    return redirect()->back()->with('error', 'الفاتورة معتمدة من قبل');
                }

                $items = InvoiceOrderDetail::where(['invoice_order_id' => $auto_serial, 'com_code' => $com_code])->first();
                if (empty($items)) {
                    return redirect()->back()->with('error', 'الفاتورة لا تحتوي على اصناف لاعتمادها');
                }

                $updateSalesInvoice = array();
                if ($data['delegate_code'] != '' && $data['delegate_code'] != null) {
                    $delegate_data = Delegate::where(['delegate_code' => $data['delegate_code'], 'com_code' => $com_code])->get(['person_id', 'percent_type', 'percent_sales_commission_group', 'percent_sales_commission_half_group', 'percent_sales_commission_one'])->first();
                    $data['delegate_account_number'] = Person::where(['id' => $delegate_data['person_id'],'com_code' => $com_code])->value('account_number');
                    $first_name = Person::where(['id' => $delegate_data['person_id'], 'com_code' => $com_code])->value('first_name');
                    $last_name = Person::where(['id' => $delegate_data['person_id'], 'com_code' => $com_code])->value('last_name');
                    $data['delegate_name'] = $first_name . ' ' . $last_name;

                    $updateSalesInvoice['delegate_commission_type'] = $delegate_data['percent_type'];
                    if ($delegate_data['percent_type'] == 1) { // نسبة
                        if ($data['sales_type'] == 1) {
                            $updateSalesInvoice['delegate_commission'] = $delegate_data['percent_sales_commission_group'];
                            $updateSalesInvoice['money_for_delegate'] = $data['total_cost'] * ($delegate_data['percent_sales_commission_group'] / 100) * (1);
                        }
                        else if ($data['sales_type'] == 2) {
                            $updateSalesInvoice['delegate_commission'] = $delegate_data['percent_sales_commission_half_group'];
                            $updateSalesInvoice['money_for_delegate'] = $data['total_cost'] * ($delegate_data['percent_sales_commission_half_group'] / 100) * (1);
                        }
                        else if ($data['sales_type'] == 3) {
                            $updateSalesInvoice['delegate_commission'] = $delegate_data['percent_sales_commission_one'];
                            $updateSalesInvoice['money_for_delegate'] = $data['total_cost'] * ($delegate_data['percent_sales_commission_one'] / 100) * (1);
                        }
                    }
                    else if ($delegate_data['percent_type'] == 2) { // نسبة
                        if ($data['sales_type'] == 1) {
                            $updateSalesInvoice['delegate_commission'] = $delegate_data['percent_sales_commission_group'];
                            $updateSalesInvoice['money_for_delegate'] = $delegate_data['percent_sales_commission_group'] * (1);
                        }
                        else if ($data['sales_type'] == 2) {
                            $updateSalesInvoice['delegate_commission'] = $delegate_data['percent_sales_commission_half_group'];
                            $updateSalesInvoice['money_for_delegate'] = $delegate_data['percent_sales_commission_half_group'] * (1);
                        }
                        else if ($data['sales_type'] == 3) {
                            $updateSalesInvoice['delegate_commission'] = $delegate_data['percent_sales_commission_one'];
                            $updateSalesInvoice['money_for_delegate'] = $delegate_data['percent_sales_commission_one'] * (1);
                        }
                    }
                }

                $updateInvoice['tax_percent'] = $request->tax_percent;
                $updateInvoice['total_cost'] = $request->total_cost;
                $updateInvoice['money_for_account'] = $request->total_cost * (-1);
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

                $flag = InvoiceOrderHeader::where(['id' => $auto_serial, 'com_code' => $com_code, 'order_type' => 3, 'invoice_type' => 2])->update($updateInvoice);


                if ($flag) {

                    SalesOrderHeader::where(['invoice_id' => $auto_serial, 'com_code' => $com_code])->update($updateSalesInvoice);

                    // get the account number and name from the customer_code
                    // 1- get the person id from the customer model
                    if ($data['customer_code'] != '' && $data['customer_code'] != null) {
                        $person_id = Customer::where(['customer_code' => $data['customer_code'], 'com_code' => $com_code])->value('person_id');
                        $data['account_number'] = Person::where(['id' => $person_id,'com_code' => $com_code])->value('account_number');
                        $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                        $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                        $data['customer_name'] = $first_name . ' ' . $last_name;

                        // change the customer current balance in accounts
                        $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                        $update_account['current_balance'] = $get_current - $data['total_cost'];
                        Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);
                    }

                    if ($data['delegate_code'] != '' && $data['delegate_code'] != null) {
                        // change the delegate current balance in accounts
                        $get_current = Account::where(['account_number' => $data['delegate_account_number'], 'com_code' => $com_code])->value('current_balance');
                        $update_account['current_balance'] = $get_current + $updateSalesInvoice['money_for_delegate'];
                        Account::where(['account_number' => $data['delegate_account_number'], 'com_code' => $com_code])->update($update_account);
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

                        $last_exchange_arrive = Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->value('last_exchange_arrive');


                        if (empty($last_exchange_arrive)) {
                            return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                        }
                        else {
                            $insertTransaction['last_arrive'] = $last_exchange_arrive + 1;
                        }

                        // Move type will number 6 صرف نظير مرتجع مبيعات
                        $insertTransaction['move_type'] = 6;
                        // Account number will be like the account number for the customer in the purchaseHeader
                        $insertTransaction['account_number'] = $data['account_number'];
                        $insertTransaction['transaction_type'] = 1;
                        $insertTransaction['money'] = $updateInvoice['what_paid'] * (-1);
                        $insertTransaction['is_approved'] = 1;
                        $insertTransaction['invoice_id'] = $auto_serial;
                        $insertTransaction['treasuries_id'] = $request->treasuries_id;
                        $insertTransaction['move_date'] = date('Y-m-d');

                        if ($data['customer_code'] == '') {
                            $insertTransaction['byan'] = ' صرف نظير مرتجع مبيعات ';
                            $insertTransaction['is_account'] = 0;
                            $insertTransaction['money_for_account'] = null;
                        }
                        else {
                            $insertTransaction['byan'] = ' صرف نظير مرتجع مبيعات للعميل' . ' ' . $data['customer_name'];
                            $insertTransaction['is_account'] = 1;
                            $insertTransaction['money_for_account'] = $updateInvoice['what_paid'] * (1);
                        }

                        $insertTransaction['added_by'] = auth()->user()->id;
                        $insertTransaction['com_code'] = $com_code;
                        $insertTransaction['created_at'] = date('Y-m-d H:i:s');

                        $flag = TreasuryTransaction::create($insertTransaction);

                        if($flag) {
                            $update_treasuries['last_exchange_arrive'] = $last_exchange_arrive + 1;
                            Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->update($update_treasuries);

                            // change the customer current balance in accounts
                            $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                            $update_account['current_balance'] = $get_current + $data['what_paid'];
                            Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);
                        }
                    }

                    // get all items
                    $item_cards = InvoiceOrderDetail::where(['invoice_order_id' => $auto_serial, 'com_code' => $com_code])->get();
                    if (!empty($item_cards)) {
                        foreach ($item_cards as $item) {
                            $insertBatch = array();
                            $item_card_data = InvItemCard::where(['item_code' => $item['item_code'], 'com_code' => $com_code])->get(['unit_id', 'retail_unit_id', 'retail_uom_quntToParent', 'item_type', 'does_has_retailunit'])->first();
                            if (!empty($item_card_data)) {
                                // Now we will check if the unit is master or retail because we say that every item will get int with master unit
                                // if master we make the quantity is the same quantity
                                $quantity = 1;
                                $unit_price = 1;
                                if ($item['unit_id'] == $item_card_data['unit_id']) {
                                    $quantity = $item['quantity'];
                                    $unit_price = $item['unit_price'];
                                }
                                else if ($item['unit_id'] == $item_card_data['retail_unit_id']) {
                                    // we will change it to master unit
                                    // by divide the quantity with retail_uom_quntToParent
                                    $quantity = $item['quantity'] / $item_card_data['retail_uom_quntToParent'];
                                    // also change the unit price from price with retail to price with master
                                    // by multiple it with retail_uom_quntToParent
                                    $unit_price =  $item['unit_price'] * $item_card_data['retail_uom_quntToParent'];
                                }



                                $update_item_batch_id = array();
                                $update_item_batch_id['batch_id'] = $item['batch_id'];

                                // before i make insert or update i should get the quantity in all store and current store from the batch
                                $quantity_in_batch_before = InvItemCardBatch::where(['item_code' => $item['item_code'], 'com_code' => $com_code])->sum('quantity');
                                $quantity_in_batch_current_store_before = InvItemCardBatch::where(['item_code' => $item['item_code'], 'store_id' => $item['store_id'], 'com_code' => $com_code])->sum('quantity');

                                if ($item['batch_id'] != null) {
                                    $old_batch = InvItemCardBatch::where('id', $item['batch_id'])->get(['quantity', 'unit_cost_price', 'total_cost_price'])->first();
                                    $updateOldBatch['quantity'] = $old_batch['quantity'] + $quantity;
                                    // the unit cost price may be different from the pill price
                                    $updateOldBatch['total_cost_price'] = $updateOldBatch['quantity'] * $old_batch['unit_cost_price'];
                                    $updateOldBatch['updated_by'] = auth()->user()->id;
                                    $updateOldBatch['updated_at'] = date('Y-m-d H:i:s');

                                    InvItemCardBatch::where(['id' => $item['batch_id']])->update($updateOldBatch);
                                }
                                else {
                                    // now we will enter the item into the batches and check some condition
                                    // 1- if the item type = 2 that means with production and expire date we check unit price, production_date and
                                    if ($item_card_data['item_type'] == 2) {
                                        // we get the store id is the same as in purchase order header data
                                        $insertBatch['store_id'] = $item['store_id'];
                                        $insertBatch['item_code'] = $item['item_code'];
                                        $insertBatch['inv_unit_id'] = $item_card_data['unit_id'];
                                        $insertBatch['production_date'] = $item['production_date'];
                                        $insertBatch['expire_date'] = $item['expire_date'];
                                    }
                                    // 1- if the item type = 1 that means invintory we check only unit price
                                    else if ($item_card_data['item_type'] == 1) {
                                        // we get the store id is the same as in purchase order header data
                                        $insertBatch['store_id'] = $item['store_id'];
                                        $insertBatch['item_code'] = $item['item_code'];
                                        $insertBatch['inv_unit_id'] = $item_card_data['unit_id'];
                                    }

                                    $unit_price_in_batch = InvItemCardBatch::where($insertBatch)->value('unit_cost_price');
                                    if ($unit_price <= $unit_price_in_batch + 3 && $unit_price >= $unit_price_in_batch - 3) {
                                        $unit_price = $unit_price_in_batch;
                                    }
                                    $insertBatch['unit_cost_price'] = $unit_price;


                                    // now we check if there is like this batch in the item batches
                                    $check_if_like_batch = InvItemCardBatch::where($insertBatch)->get(['id', 'quantity', 'total_cost_price'])->first();

                                    // if there is we will only update the batch
                                    if (!empty($check_if_like_batch)) {
                                        $updateOldBatch['quantity'] = $check_if_like_batch['quantity'] + $quantity;
                                        // we don't need to update the unit_cost_price because they are the same
                                        $updateOldBatch['total_cost_price'] = $updateOldBatch['quantity'] * $unit_price;
                                        //$updateOldBatch['total_cost_price'] = $check_if_like_batch['total_cost_price'] + ($quantity * $unit_price);
                                        $updateOldBatch['updated_by'] = auth()->user()->id;
                                        $updateOldBatch['updated_at'] = date('Y-m-d H:i:s');

                                        InvItemCardBatch::where(['id' => $check_if_like_batch['id']])->update($updateOldBatch);

                                        // after we create the patch we will make the item batch id in this
                                        $update_item_batch_id['batch_id'] = $check_if_like_batch['id'];
                                    }
                                    //else we make new batch
                                    else {
                                        // we continue with insertBatch array
                                        $insertBatch['quantity'] = $quantity;
                                        $insertBatch['total_cost_price'] = $quantity * $unit_price;
                                        $insertBatch['added_by'] = auth()->user()->id;
                                        $insertBatch['created_at'] = date('Y-m-d H:i:s');
                                        $insertBatch['com_code'] = $com_code;

                                        $max_batch_code = InvItemCardBatch::where(['com_code' => $com_code])->max('batch_code');
                                        if (empty($max_batch_code)) {
                                            $insertBatch['batch_code'] = 1;
                                        }
                                        else {
                                            $insertBatch['batch_code'] = $max_batch_code + 1;
                                        }

                                        InvItemCardBatch::create($insertBatch);
                                        // after we create the patch we will make the item batch id in this
                                        $update_item_batch_id['batch_id'] = InvItemCardBatch::max('id');
                                    }
                                    // after we create the patch we will make the item batch id in this
                                    InvoiceOrderDetail::where(['invoice_order_id' => $auto_serial, 'id' => $item['id'], 'com_code' => $com_code])->update($update_item_batch_id);
                                }


                                // get the quantity in all store and current store from the batch and we will get the name of the master unit
                                $quantity_in_batch_after = InvItemCardBatch::where(['item_code' => $item['item_code'], 'com_code' => $com_code])->sum('quantity');
                                $quantity_in_batch_current_store_after = InvItemCardBatch::where(['item_code' => $item['item_code'], 'store_id' => $item['store_id'], 'com_code' => $com_code])->sum('quantity');
                                $parent_unit_name = InvUnit::where('id', $item_card_data['unit_id'])->value('name');


                                // Then we will save this change with item card in the item card movements table
                                $insertItemMovement['inv_item_card_movements_categories_id'] = 2;
                                $insertItemMovement['item_code'] = $item['item_code'];
                                $insertItemMovement['inv_item_card_movements_types_id'] = 5;
                                $insertItemMovement['order_header_id'] = $data['id'];
                                $insertItemMovement['order_details_id'] = $item['id'];
                                $insertItemMovement['store_id'] = $item['store_id'];
                                $insertItemMovement['batch_id'] = $update_item_batch_id['batch_id'];
                                $insertItemMovement['quantity_before_movement'] = $quantity_in_batch_before . ' ' . $parent_unit_name;
                                $insertItemMovement['quantity_after_movement'] = $quantity_in_batch_after . ' ' . $parent_unit_name;
                                $insertItemMovement['quantity_before_movement_in_current_store'] = $quantity_in_batch_current_store_before . ' ' . $parent_unit_name;
                                $insertItemMovement['quantity_after_movement_in_current_store'] = $quantity_in_batch_current_store_after . ' ' . $parent_unit_name;
                                $insertItemMovement['byan'] = 'صرف نضير مرتجع مبيعات للعميل ' . $data['customer_name'] . ' فاتورة رقم ' . $auto_serial;
                                $insertItemMovement['created_at'] = date('Y-m-d H:i:s');
                                $insertItemMovement['date'] = date('Y-m-d');
                                $insertItemMovement['added_by'] = auth()->user()->id;
                                $insertItemMovement['com_code'] = $com_code;

                                InvItemCardMovement::create($insertItemMovement);


                                if ($item['batch_id'] == null) {
                                    // update the cost price in the item card
                                    // if has retail unit we will update the cost_price_in_master and cost_price_in_retail
                                    if ($item['unit_id'] == $item_card_data['unit_id']) {
                                        $update_item_card_price_quantity['cost_price_in_master'] = $item['unit_price'];
                                        if ($item_card_data['does_has_retailunit'] == 1) {
                                            $update_item_card_price_quantity['cost_price_in_retail'] = $item['unit_price'] / $item_card_data['retail_uom_quntToParent'];
                                            $update_item_card_price_quantity['price_per_one_in_retail_unit'] = $update_item_card_price_quantity['cost_price_in_retail'] + ($update_item_card_price_quantity['cost_price_in_retail'] * 0.1);
                                            $update_item_card_price_quantity['price_per_half_group_in_retail_unit'] = $update_item_card_price_quantity['cost_price_in_retail'] + ($update_item_card_price_quantity['cost_price_in_retail'] * 0.07);
                                            $update_item_card_price_quantity['price_per_group_in_retail_unit'] = $update_item_card_price_quantity['cost_price_in_retail'] + ($update_item_card_price_quantity['cost_price_in_retail'] * 0.05);
                                        }
                                    }
                                    else if ($item['unit_id'] == $item_card_data['retail_unit_id']) {
                                        $update_item_card_price_quantity['cost_price_in_retail'] = $item['unit_price'];
                                        $update_item_card_price_quantity['cost_price_in_master'] = $item['unit_price'] * $item_card_data['retail_uom_quntToParent'];
                                        $update_item_card_price_quantity['price_per_one_in_retail_unit'] = $update_item_card_price_quantity['cost_price_in_retail'] + ($update_item_card_price_quantity['cost_price_in_retail'] * 0.1);
                                        $update_item_card_price_quantity['price_per_half_group_in_retail_unit'] = $update_item_card_price_quantity['cost_price_in_retail'] + ($update_item_card_price_quantity['cost_price_in_retail'] * 0.07);
                                        $update_item_card_price_quantity['price_per_group_in_retail_unit'] = $update_item_card_price_quantity['cost_price_in_retail'] + ($update_item_card_price_quantity['cost_price_in_retail'] * 0.05);
                                    }

                                    // updates on prices that can be change if you don't like in the item card model it self
                                    $update_item_card_price_quantity['price_per_one_in_master_unit'] = $update_item_card_price_quantity['cost_price_in_master'] + ($update_item_card_price_quantity['cost_price_in_master'] * 0.1);
                                    $update_item_card_price_quantity['price_per_half_group_in_master_unit'] = $update_item_card_price_quantity['cost_price_in_master'] + ($update_item_card_price_quantity['cost_price_in_master'] * 0.07);
                                    $update_item_card_price_quantity['price_per_group_in_master_unit'] = $update_item_card_price_quantity['cost_price_in_master'] + ($update_item_card_price_quantity['cost_price_in_master'] * 0.05);
                                }

                                // update the quantity in item_card
                                $all_quantity = InvItemCardBatch::where(['item_code' => $item['item_code'], 'com_code' => $com_code])->sum('quantity');
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

                                InvItemCard::where(['item_code' => $item['item_code'], 'com_code' => $com_code])->update($update_item_card_price_quantity);
                            }
                        }
                    }

                    return redirect()->back()->with('success', 'تم اعتماد واضافة الفاتورة بنجاح');
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

    public function printA4($id, $type) {
        if (check_control_menu_role('المبيعات', 'فواتير المرتجعات العام' , 'طباعة') == true) {
            try {
                $com_code = auth()->user()->com_code;
                $data = InvoiceOrderHeader::where('id', $id)->get()->first();
                if (!empty($data)) {
                    $data['tax_value'] = $data['total_before_discount'] * $data['tax_percent'] / 100;

                    $data['customer_code'] = SalesOrderHeader::where('invoice_id', $id)->value('customer_code');
                    if (!empty($data['customer_code'])) {
                        $person_id = Customer::where(['customer_code' => $data['customer_code'], 'com_code' => $com_code])->value('person_id');
                        $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                        $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                        $data['customer_phone'] = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('phone');
                        $data['customer_name'] = $first_name . ' ' . $last_name;
                    }
                    else {
                        $data['customer_name'] = 'لا يوجد';
                    }
                    $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();
                    $sales_invoices_details = InvoiceOrderDetail::where('invoice_order_id', $id)->get();
                    if (!empty($sales_invoices_details)) {
                        foreach($sales_invoices_details as $s) {
                            $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                            $s['store_name'] = Store::where('id', $s['store_id'])->value('name');
                            $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                        }
                    }
                }

                if ($type == 'A4') {
                    return view('admin.sales_order_header_general_return.printA4', ['data' => $data, 'systemData' => $systemData, 'sales_invoices_details' => $sales_invoices_details]);
                }
                else if ($type == 'A6') {
                    return view('admin.sales_order_header_general_return.printA6', ['data' => $data, 'systemData' => $systemData, 'sales_invoices_details' => $sales_invoices_details]);
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
