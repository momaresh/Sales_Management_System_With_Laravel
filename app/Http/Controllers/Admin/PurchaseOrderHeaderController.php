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
use Exception;


class PurchaseOrderHeaderController extends Controller
{

    public function index()
    {
        //
        try {
            $data = InvoiceOrderHeader::where(['com_code' => auth()->user()->com_code, 'invoice_type' => 1, 'order_type' => 1])->orderBy('id' , 'desc')->paginate(PAGINATION_COUNT);
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

            return view('admin.purchase_order_header.index', ['data' => $data, 'suppliers' => $suppliers, 'stores' => $stores]);
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        //
        $com_code = auth()->user()->com_code;

        $suppliers_code = Supplier::where(['com_code' => auth()->user()->com_code])->get(['supplier_code', 'person_id']);
        $stores = Store::get(['id', 'name']);
        if (!empty($suppliers_code)) {
            foreach ($suppliers_code as $sup) {
                $sup['first_name'] = Person::where('id', $sup['person_id'])->value('first_name');
                $sup['last_name'] = Person::where('id', $sup['person_id'])->value('last_name');
            }
        }

        return view('admin.purchase_order_header.create', ['suppliers_code' => $suppliers_code, 'stores' => $stores]);
    }

    public function store(PurchaseOrderHeaderRequest $request)
    {
        //

        try {
            //set account number
            $max_invoice_id = InvoiceOrderHeader::max('id');
            if (!empty($max_invoice_id)) {
                $inserted_invoice['id'] = $max_invoice_id + 1;
            } else {
                $inserted_invoice['id'] = 1;
            }

            $max_pill_code = InvoiceOrderHeader::where(['com_code' => auth()->user()->com_code, 'order_type' =>1, 'invoice_type' => 1])->max('pill_code');
            if (!empty($max_pill_code)) {
                $inserted_invoice['pill_code'] = $max_pill_code + 1;
            } else {
                $inserted_invoice['pill_code'] = 1;
            }

            $inserted_invoice['order_type'] = 1;
            $inserted_invoice['invoice_type'] = 1;
            $inserted_invoice['pill_type'] = $request->pill_type;
            $inserted_invoice['order_date'] = $request->order_date;
            $inserted_invoice['pill_number'] = $request->pill_number;
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
                } else {
                    $inserted_purchase['auto_serial'] = 1;
                }

                $max_purchase_code = PurchaseOrderHeader::where(['com_code' => auth()->user()->com_code])->max('purchase_code');
                if (!empty($max_purchase_code)) {
                    $inserted_purchase['purchase_code'] = $max_purchase_code + 1;
                } else {
                    $inserted_purchase['purchase_code'] = 1;
                }
                $inserted_purchase['store_id'] = $request->store_id;
                $inserted_purchase['supplier_code'] = $request->supplier_code;
                $inserted_purchase['added_by'] = auth()->user()->id;
                $inserted_purchase['created_at'] = date("Y-m-d H:i:s");
                $inserted_purchase['com_code'] = auth()->user()->com_code;
                PurchaseOrderHeader::create($inserted_purchase);
                return redirect()->route('admin.purchase_header.index')->with(['success' => 'لقد تم اضافة الفاتورة بنجاح']);
            }

        }
        catch (Exception $e) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $e->getMessage()])
                ->withInput();
        }
    }

    public function details($id)
    {
        //
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

        $details = InvoiceOrderDetail::where(['invoice_order_id' => $purchase_data['invoice_id'], 'com_code' => $com_code])->get();

            if (!empty($details)) {
                foreach($details as $d) {
                    $d['item_card_name'] = InvItemCard::where('item_code', $d['item_code'])->value('name');
                    $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
                    $d['unit_name'] = InvUnit::where(['id' => $d['unit_id'], 'com_code' => $com_code])->value('name');
                    $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');

                    if ($d['updated_by'] != null) {
                        $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
                    }
                }
            }

            if ($data['is_approved'] == 0) {
                $items_card = InvItemCard::where(['active' => 1, 'com_code' => $com_code])->get(['item_code', 'name', 'item_type']);
            }
            else {
                $items_card = '';
            }

            return view('admin.purchase_order_header.details', ['data' => $data, 'details' => $details, 'items_card' => $items_card]);
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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

            return view("admin.purchase_order_header.get_item_unit", ['item_card_data' => $item_card_data]);
        }
    }

    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $data = InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code, 'is_approved' => 0])->first();
        if (empty($data)) {
            return redirect()->back()->with('error', 'لا يوجد بيانات كهذه متاح');
        }

        $purchase_data = PurchaseOrderHeader::where(['invoice_id' => $id, 'com_code' => auth()->user()->com_code])->first();
        $data['store_id'] = $purchase_data['store_id'];
        $data['supplier_code'] = $purchase_data['supplier_code'];

        $suppliers_code = Supplier::where(['com_code' => $com_code])->get(['supplier_code', 'person_id']);
        $stores = Store::get(['id', 'name']);
        if (!empty($suppliers_code)) {
            foreach ($suppliers_code as $sup) {
                $sup['first_name'] = Person::where(['id' => $sup['person_id'], 'com_code' => auth()->user()->com_code])->value('first_name');
                $sup['last_name'] = Person::where(['id' => $sup['person_id'], 'com_code' => auth()->user()->com_code])->value('last_name');
            }
        }
        return view('admin.purchase_order_header.edit', ['data' => $data, 'suppliers_code' => $suppliers_code, 'stores' => $stores]);
    }

    public function update(PurchaseOrderHeaderRequest $request, $id)
    {
        try {

            $update_invoice['pill_type'] = $request->pill_type;
            $update_invoice['order_date'] = $request->order_date;
            $update_invoice['pill_number'] = $request->pill_number;
            $update_invoice['notes'] = $request->notes;
            $update_invoice['updated_by'] = auth()->user()->id;
            $update_invoice['updated_at'] = date("Y-m-d H:i:s");
            InvoiceOrderHeader::where(['id' => $id, 'com_code' => auth()->user()->com_code])->update($update_invoice);


            $update_purchase['supplier_code'] = $request->supplier_code;
            $update_purchase['store_id'] = $request->store_id;
            $update_purchase['updated_by'] = auth()->user()->id;
            $update_purchase['updated_at'] = date("Y-m-d H:i:s");
            PurchaseOrderHeader::where(['invoice_id' => $id, 'com_code' => auth()->user()->com_code])->update($update_purchase);

            return redirect()->route('admin.purchase_header.index')->with(['success' => 'لقد تم تعديل الفاتورة بنجاح']);
        }
        catch (Exception $e) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $e->getMessage()])
                ->withInput();
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

            PurchaseOrderHeader::where(['invoice_id' => $id, 'com_code' => $com_code])->delete();
            InvoiceOrderHeader::where(['id' => $id, 'com_code' => $com_code])->delete();
            return redirect()->back()->with('success', 'تم الحذف بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
            $data = InvoiceOrderHeader::whereIn('id', $data_in)->where("$filed1", "$operator1", "$value1")->where("$filed4", "$operator4", "$value4")->where("$filed5", "$operator5", "$value5")->where(['com_code' => auth()->user()->com_code, 'invoice_type' => 1, 'order_type' => 1])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);

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
            return view('admin.purchase_order_header.ajax_search', ['data' => $data]);

        }
    }

    public function add_new_item(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                $header_data = InvoiceOrderHeader::where('id', $request->purchase_auto_serial)->first();


                if (!empty($header_data)) {
                    if ($header_data['is_approved'] == 0) {
                        $inserted = new InvoiceOrderDetail;
                        $inserted->invoice_order_id = $request->purchase_auto_serial;
                        $inserted->item_code = $request->item_code;
                        $inserted->quantity = $request->quantity;
                        $inserted->unit_price = $request->unit_price;
                        $inserted->unit_id = $request->unit_id;
                        $inserted->total_price = $request->total_price;
                        if (!empty($request->production_date))
                            $inserted->production_date = $request->production_date;
                        if (!empty($request->expire_date))
                            $inserted->expire_date = $request->expire_date;
                        $inserted->added_by = auth()->user()->id;
                        $inserted->created_at = date("Y-m-d H:i:s");
                        $inserted->com_code = auth()->user()->com_code;
                        $total['total_before_discount'] = InvoiceOrderDetail::where('invoice_order_id', $request->purchase_auto_serial)->sum('total_price');
                        $total['total_before_discount'] = $total['total_before_discount'] + $request->total_price;
                        InvoiceOrderHeader::where('id', $header_data['id'])->update($total);
                        $inserted->save();
                    }
                }
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }

    public function reload_items(Request $request)
    {
        //
        try {
            $com_code = auth()->user()->com_code;
            $auto_serial = $request->purchase_auto_serial;
            $data = InvoiceOrderHeader::where(['id' => $auto_serial, 'com_code' => $com_code])->get(['is_approved', 'id'])->first();


            $details = InvoiceOrderDetail::where(['invoice_order_id' => $auto_serial, 'com_code' => $com_code])->get();
            if (!empty($details)) {
                foreach($details as $d) {
                    $d['item_card_name'] = InvItemCard::where(['item_code' => $d['item_code'], 'com_code' => $com_code])->value('name');
                    $d['added_by_name'] = Admin::where(['id' => $d['added_by'], 'com_code' => $com_code])->value('name');
                    if ($d['updated_by'] != null) {
                        $d['updated_by_name'] = Admin::where(['id' => $d['added_by'], 'com_code' => $com_code])->value('name');
                    }
                }
            }

            return view('admin.purchase_order_header.reload_items', ['details' => $details, 'data' => $data]);
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reload_total_price(Request $request)
    {
        //
        try {
            $auto_serial = $request->purchase_auto_serial;
            $total_price = InvoiceOrderDetail::where(['invoice_order_id' => $auto_serial, 'com_code' => auth()->user()->com_code])->sum('total_price');

            return $total_price;
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit_item(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                $invoice_id = $request->purchase_auto_serial;
                $detail_id = $request->purchase_order_detail_id;
                $com_code = auth()->user()->com_code;

                $detail = InvoiceOrderDetail::where(['invoice_order_id' => $invoice_id, 'id' => $detail_id, 'com_code' => $com_code])->first();
                $items_card = InvItemCard::where(['active' => 1, 'com_code' => $com_code])->get(['item_code', 'name', 'item_type']);

                return view('admin.purchase_order_header.edit_item', ['detail' => $detail, 'items_card' => $items_card]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }

    public function create_item(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                $items_card = InvItemCard::where(['active' => 1, 'com_code' => auth()->user()->com_code])->get(['item_code', 'name', 'item_type']);

                return view('admin.purchase_order_header.create_item', ['items_card' => $items_card]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }

    public function update_item(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                $header_data = InvoiceOrderHeader::where('id', $request->purchase_auto_serial)->get(['id', 'is_approved'])->first();


                if (!empty($header_data)) {
                    if ($header_data['is_approved'] == 0) {

                        $updated['item_code'] = $request->item_code;
                        $updated['quantity'] = $request->quantity;
                        $updated['unit_price'] = $request->unit_price;
                        $updated['unit_id'] = $request->unit_id;
                        $updated['total_price'] = $request->total_price;
                        if (!empty($request->production_date))
                            $updated['production_date'] = $request->production_date;
                        if (!empty($request->expire_date))
                            $updated['expire_date'] = $request->expire_date;
                        $updated['updated_by'] = auth()->user()->id;
                        $updated['updated_at'] = date("Y-m-d H:i:s");

                        if (InvoiceOrderDetail::where(['invoice_order_id' => $request->purchase_auto_serial, 'id' => $request->purchase_order_detail_id])->update($updated)) {
                            $total['total_before_discount'] = InvoiceOrderDetail::where('invoice_order_id', $request->purchase_auto_serial)->sum('total_price');
                            InvoiceOrderHeader::where('id', $header_data['id'])->update($total);
                        }


                    }

                }
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
    }

    public function delete_item($detail_id, $header_id)
    {
        # code...
        try {
            $header_data = InvoiceOrderHeader::where('id', $header_id)->get('is_approved')->first();
            $com_code = auth()->user()->com_code;

            if (!empty($header_data)) {
                if ($header_data['is_approved'] == 0) {
                    if (InvoiceOrderDetail::where(['invoice_order_id' => $header_id, 'id' => $detail_id, 'com_code' => $com_code])->delete()) {
                        $total['total_before_discount'] = InvoiceOrderDetail::where(['invoice_order_id' => $header_id, 'com_code' => $com_code])->sum('total_price');
                        InvoiceOrderHeader::where(['id' => $header_id, 'invoice_type' => 1, 'order_type' => 1, 'com_code' => $com_code])->update($total);
                        return redirect()->back()->with('success', 'تم الحذف بنجاح');
                    }
                }
            }

        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function load_modal_approved(Request $request)
    {
        # code...
        if ($request->ajax()) {
            try {
                $com_code = auth()->user()->com_code;
                $auto_serial = $request->auto_serial;
                $data = InvoiceOrderHeader::where(['com_code' => $com_code, 'id' => $auto_serial])->first();
                $data['all_items'] = InvoiceOrderDetail::where(['com_code' => $com_code, 'invoice_order_id' => $auto_serial])->count();

                //Check if has shift
                $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => $com_code, 'is_finished' => 0])->get(['treasuries_id', 'shift_code'])->first();
                if (empty($check_shift)) {
                    return Response()->json(['error' => ''], 404);
                }
                $check_shift['treasuries_name'] = Treasury::where(['id' => $check_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                $check_shift['treasuries_money'] = TreasuryTransaction::where(['shift_code' => $check_shift['shift_code'], 'com_code' => $com_code])->sum('money');

                return view('admin.purchase_order_header.load_modal_approved', ['data' => $data, 'check_shift' => $check_shift]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
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


    public function do_approve(Request $request, $auto_serial)
    {
        try {

            # code...
            $com_code = auth()->user()->com_code;
            $data = InvoiceOrderHeader::where(['id' => $auto_serial, 'com_code' => $com_code, 'order_type' => 1, 'invoice_type' => 1])->first();
            $data['supplier_code'] = PurchaseOrderHeader::where(['invoice_id' => $auto_serial, 'com_code' => $com_code])->value('supplier_code');
            $data['store_id'] = PurchaseOrderHeader::where(['invoice_id' => $auto_serial, 'com_code' => $com_code])->value('store_id');

            if (empty($data)) {
                return redirect()->back()->with('error', 'لا توجد بيانات كهذه');
            }
            if ($data['is_approved'] == 1) {
                return redirect()->back()->with('error', 'الفاتورة معتمدة من قبل');
            }

            $updateInvoice['tax_percent'] = $request->tax_percent;
            $updateInvoice['total_cost'] = $request->total_cost;
            // لانه انا بأخذ من المورد مبلغ وقدرة قيمة المنتجات بشكل كلي سواء كان الدفع كاش او آجل وهذا لا يقوم بعمل اي تحديث في حساب المورد الا عند انشاء الترانساكشن لهذه العملية وعندها نقوم بحساب كم المبلغ الذي تم دفعه للمورد سواء كان كل او جزئي ونضيفه للمورد بالسالب لانه تم اعطائه هذا المبلغ الفعلي ونقوم هنا بعمل التحديث لحساب المورد والفائدة من هذا هو عند حسبة المبلغ يتم اخذ ما تم الاخذ منه في الفاتورة وما تم تسليمه له فعليا عند عملية الصرف له وعمل مقارنة بينهما لحساب ما تبقى له
            $updateInvoice['money_for_account'] = $request->total_cost * (-1);
            $updateInvoice['discount_type'] = $request->discount_type;
            $updateInvoice['pill_type'] = $request->pill_type;

            if ($request->discount_type == 1) {
                $updateInvoice['discount_percent'] = $request->discount_percent;
                $updateInvoice['discount_value'] = $request->discount_val;
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

            if ($request->what_paid > $request->treasury_money) {
                return redirect()->back()->with('error', 'ليس لديك رصيد كافي في الخزنة');
            }

            $updateInvoice['what_paid'] = $request->what_paid;
            $updateInvoice['what_remain'] = $request->what_remain;
            $updateInvoice['is_approved'] = 1;
            $updateInvoice['approved_by'] = auth()->user()->id;
            $updateInvoice['approved_at'] = date('Y-m-d H:i:s');

            $flag = InvoiceOrderHeader::where(['id' => $auto_serial, 'com_code' => $com_code, 'order_type' => 1, 'invoice_type' => 1])->update($updateInvoice);


            if ($flag) {
                // get the account number and name from the supplier_code
                // 1- get the person id from the supplier model
                $person_id = Supplier::where(['supplier_code' => $data['supplier_code'], 'com_code' => $com_code])->value('person_id');
                $data['account_number'] = Person::where(['id' => $person_id,'com_code' => $com_code])->value('account_number');
                $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                $data['supplier_name'] = $first_name . ' ' . $last_name;


                // change the supplier current balance in accounts
                $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                $update_account['current_balance'] = $get_current - $request->total_cost;
                Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);

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


                    // Move type will number 9 صرف نضير مورد
                    $insertTransaction['move_type'] = 9;
                    // Account number will be like the account number for the supplier in the purchaseHeader
                    $insertTransaction['account_number'] = $data['account_number'];
                    $insertTransaction['transaction_type'] = 1;
                    $insertTransaction['is_account'] = 1;
                    $insertTransaction['is_approved'] = 1;
                    $insertTransaction['invoice_id'] = $auto_serial;
                    $insertTransaction['treasuries_id'] = $request->treasuries_id;
                    $insertTransaction['money'] = $updateInvoice['what_paid'] * (-1);
                    $insertTransaction['money_for_account'] = $updateInvoice['what_paid'];
                    $insertTransaction['move_date'] = date('Y-m-d');
                    $insertTransaction['byan'] = ' تسليم نضير فاتورة مشتريات للعميل' . $data['supplier_name'];
                    $insertTransaction['added_by'] = auth()->user()->id;
                    $insertTransaction['com_code'] = $com_code;
                    $insertTransaction['created_at'] = date('Y-m-d H:i:s');

                    $flag = TreasuryTransaction::create($insertTransaction);

                    if($flag) {
                        $update_treasuries['last_exchange_arrive'] = $last_exchange_arrive + 1;
                        Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->update($update_treasuries);

                        // change the supplier current balance in accounts
                        $get_current = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->value('current_balance');
                        $update_account['current_balance'] = $get_current + $data['what_paid'];
                        Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code])->update($update_account);
                    }
                }


                /////////////////////////////////////////////////////////////

                // Moving items to the batches
                // 1- if the items type is with production and expire date we will check
                // if there is a batch with the same price and production and expire date,
                // if there is we will updated else we will create new one,
                // and if the item type is invintory we will check only the price,
                // with all we also need to check unit_id is the same, if there is update in the item unit id
                // and also all the item will go into with the master unit id, and with the master price cost


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


                            // now we will enter the item into the batches and check some condition
                            // 1- if the item type = 2 that means with production and expire date we check unit price, production_date and
                            if ($item_card_data['item_type'] == 2) {
                                // we get the store id is the same as in purchase order header data
                                $insertBatch['store_id'] = $data['store_id'];
                                $insertBatch['item_code'] = $item['item_code'];
                                $insertBatch['inv_unit_id'] = $item_card_data['unit_id'];
                                $insertBatch['production_date'] = $item['production_date'];
                                $insertBatch['expire_date'] = $item['expire_date'];
                            }
                            // 1- if the item type = 1 that means invintory we check only unit price
                            else if ($item_card_data['item_type'] == 1) {
                                // we get the store id is the same as in purchase order header data
                                $insertBatch['store_id'] = $data['store_id'];
                                $insertBatch['item_code'] = $item['item_code'];
                                $insertBatch['inv_unit_id'] = $item_card_data['unit_id'];
                            }

                            $unit_price_in_batch = InvItemCardBatch::where($insertBatch)->value('unit_cost_price');
                            if ($unit_price <= $unit_price_in_batch + 3 && $unit_price >= $unit_price_in_batch - 3) {
                                $unit_price = $unit_price_in_batch;
                            }
                            $insertBatch['unit_cost_price'] = $unit_price;


                            // before i make insert or update i should get the quantity in all store and current store from the batch
                            $quantity_in_batch_before = InvItemCardBatch::where(['item_code' => $item['item_code'], 'com_code' => $com_code])->sum('quantity');
                            $quantity_in_batch_current_store_before = InvItemCardBatch::where(['item_code' => $item['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');

                            // now we check if there is like this batch in the item batches
                            $check_if_like_batch = InvItemCardBatch::where($insertBatch)->get(['id', 'quantity', 'total_cost_price'])->first();

                            $update_item_batch_id = array();
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


                            // get the quantity in all store and current store from the batch and we will get the name of the master unit
                            $quantity_in_batch_after = InvItemCardBatch::where(['item_code' => $item['item_code'], 'com_code' => $com_code])->sum('quantity');
                            $quantity_in_batch_current_store_after = InvItemCardBatch::where(['item_code' => $item['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');
                            $parent_unit_name = InvUnit::where('id', $item_card_data['unit_id'])->value('name');


                            // Then we will save this change with item card in the item card movements table
                            $insertItemMovement['inv_item_card_movements_categories_id'] = 1;
                            $insertItemMovement['item_code'] = $item['item_code'];
                            $insertItemMovement['inv_item_card_movements_types_id'] = 1;
                            $insertItemMovement['order_header_id'] = $data['id'];
                            $insertItemMovement['order_details_id'] = $item['id'];
                            $insertItemMovement['store_id'] = $data['store_id'];
                            $insertItemMovement['batch_id'] = $update_item_batch_id['batch_id'];
                            $insertItemMovement['quantity_before_movement'] = $quantity_in_batch_before . ' ' . $parent_unit_name;
                            $insertItemMovement['quantity_after_movement'] = $quantity_in_batch_after . ' ' . $parent_unit_name;
                            $insertItemMovement['quantity_before_movement_in_current_store'] = $quantity_in_batch_current_store_before . ' ' . $parent_unit_name;
                            $insertItemMovement['quantity_after_movement_in_current_store'] = $quantity_in_batch_current_store_after . ' ' . $parent_unit_name;
                            $insertItemMovement['byan'] = 'صرف نضير مشتريات للعميل ' . $data['supplier_name'] . ' فاتورة رقم ' . $auto_serial;
                            $insertItemMovement['created_at'] = date('Y-m-d H:i:s');
                            $insertItemMovement['date'] = date('Y-m-d');
                            $insertItemMovement['added_by'] = auth()->user()->id;
                            $insertItemMovement['com_code'] = $com_code;

                            InvItemCardMovement::create($insertItemMovement);


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
                return redirect()->back()->with('success', 'تم اعتماد الفاتورة بنجاح');
            }

        }
        catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function printA4($id, $type) {
        $com_code = auth()->user()->com_code;
        $data = InvoiceOrderHeader::where('id', $id)->get()->first();
        if (!empty($data)) {
            $data['tax_value'] = $data['total_before_discount'] * $data['tax_percent'] / 100;
            $data['store_id'] = PurchaseOrderHeader::where('invoice_id', $id)->value('store_id');
            $data['store_name'] = Store::where('id', $data['store_id'])->value('name');
            $data['supplier_code'] = PurchaseOrderHeader::where('invoice_id', $id)->value('supplier_code');
            if (!empty($data['supplier_code'])) {
                $person_id = Supplier::where(['supplier_code' => $data['supplier_code'], 'com_code' => $com_code])->value('person_id');
                $first_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('first_name');
                $last_name = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('last_name');
                $data['supplier_phone'] = Person::where(['id' => $person_id, 'com_code' => $com_code])->value('phone');
                $data['supplier_name'] = $first_name . ' ' . $last_name;
            }
            else {
                $data['supplier_name'] = 'لا يوجد';
            }
            $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();
            $sales_invoices_details = InvoiceOrderDetail::where('invoice_order_id', $id)->get();
            if (!empty($sales_invoices_details)) {
                foreach($sales_invoices_details as $s) {
                    $s['unit_name'] = InvUnit::where('id', $s['unit_id'])->value('name');
                    $s['item_name'] = InvItemCard::where('item_code', $s['item_code'])->value('name');
                }
            }
        }

        if ($type == 'A4') {
            return view('admin.purchase_order_header.printA4', ['data' => $data, 'systemData' => $systemData, 'sales_invoices_details' => $sales_invoices_details]);
        }
        else if ($type == 'A6') {
            return view('admin.purchase_order_header.printA6', ['data' => $data, 'systemData' => $systemData, 'sales_invoices_details' => $sales_invoices_details]);
        }
    }
}
