<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPanelSetting;
use App\Models\InvItemCard;
use App\Models\InvItemCardBatch;
use App\Models\InvItemCardMovement;
use App\Models\InvStoreInventoryDetail;
use App\Models\InvStoreInventoryHeader;
use App\Models\InvUnit;
use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Expr;

class InvStoreInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            $com_code = auth()->user()->com_code;
            $data = InvStoreInventoryHeader::where('com_code', $com_code)->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['store_name'] = Store::where('id', $d['store_id'])->value('name');
                }
            }

            $stores = Store::where('com_code', $com_code)->get(['id', 'name']);

            return view('admin.inv_stores_inventory.index', ['data' => $data, 'stores' => $stores]);
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $stores = Store::where('com_code', auth()->user()->com_code)->get(['id', 'name']);

        return view('admin.inv_stores_inventory.create', ['stores' => $stores]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate(
            [
                'inventory_date' => 'required',
                'inventory_type' => 'required',
                'store_id' => 'required',
            ],
            [
                'inventory_date.required' => 'تاريخ الجرد مطلوب',
                'inventory_type.required' => 'نوع الجرد مطلوب',
                'store_id.required' => 'مخزن الجرد مطلوب',
            ]
        );

        try {
            $check = InvStoreInventoryHeader::where(['is_closed' => 0, 'store_id' => $request->store_id, 'com_code' => auth()->user()->com_code])->value('id');
            if (!empty($check)) {
                return redirect()->route('admin.inv_stores_inventory.index')->with('error', 'يوجد جرد لا يزال مفتوحاً');
            }

            $inserted['store_id'] = $request->store_id;
            $inserted['inventory_date'] = $request->inventory_date;
            $inserted['inventory_type'] = $request->inventory_type;
            $inserted['notes'] = $request->notes;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['date'] = date('Y-m-d');
            $inserted['added_by'] = auth()->user()->id;
            $inserted['com_code'] = auth()->user()->com_code;

            InvStoreInventoryHeader::create($inserted);

            return redirect()->route('admin.inv_stores_inventory.index')->with('success', 'تم الاضافة بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function details($id)
    {
        //
        try {
            $com_code = auth()->user()->com_code;
            $data = InvStoreInventoryHeader::where('id', $id)->get()->first();
            if (!empty($data)) {
                $data['store_name'] = Store::where('id', $data['store_id'])->value('name');
                $data['added_by_name'] = Admin::where('id', $data['added_by'])->value('name');
                $data['updated_by_name'] = Admin::where('id', $data['updated_by'])->value('name');
            }

            $items_card = array();
            if ($data['is_closed'] == 0) {
                $items_card = InvItemCardBatch::where(['store_id' => $data['store_id'], 'com_code' => $com_code])->distinct()->get('item_code');
                if (!empty($items_card)) {
                    foreach ($items_card as $item) {
                        $item['name'] = InvItemCard::where(['item_code' => $item['item_code'], 'com_code' => $com_code])->value('name');
                    }
                }
            }

            $details = InvStoreInventoryDetail::where('inv_stores_inventory_header_id', $id)->get();
            if (!empty($details)) {
                foreach ($details as $detail) {
                    $batch = InvItemCardBatch::where(['id' => $detail['batch_id'], 'com_code' => $com_code])->get()->first();
                    $detail['item_name'] = InvItemCard::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->value('name');
                    $detail['unit_cost'] = $batch['unit_cost_price'];
                    $detail['production_date'] = $batch['production_date'];
                    $detail['expire_date'] = $batch['expire_date'];
                }
            }

            return view('admin.inv_stores_inventory.details', ['data' => $data, 'details' => $details, 'items_card' => $items_card]);
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $check_items = InvStoreInventoryDetail::where('inv_stores_inventory_header_id', $id)->count();
        if ($check_items > 0) {
            return redirect()->back()->with('error', 'لا يمكن تعديل الجرد لاحتوائه على بتشات جرد');
        }

        $data = InvStoreInventoryHeader::where('id', $id)->get()->first();
        $stores = Store::where('com_code', auth()->user()->com_code)->get(['id', 'name']);

        return view('admin.inv_stores_inventory.edit', ['data' => $data, 'stores' => $stores]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate(
            [
                'inventory_date' => 'required',
                'inventory_type' => 'required',
                'store_id' => 'required',
            ],
            [
                'inventory_date.required' => 'تاريخ الجرد مطلوب',
                'inventory_type.required' => 'نوع الجرد مطلوب',
                'store_id.required' => 'مخزن الجرد مطلوب',
            ]
        );

        try {
            $check = InvStoreInventoryHeader::where(['is_closed' => 0, 'store_id' => $request->store_id, 'com_code' => auth()->user()->com_code])->where('store_id', '!=', $request->old_store_id)->value('id');
            if (!empty($check)) {
                return redirect()->route('admin.inv_stores_inventory.index')->with('error', 'يوجد جرد لا يزال مفتوحاً');
            }

            $updated['store_id'] = $request->store_id;
            $updated['inventory_date'] = $request->inventory_date;
            $updated['inventory_type'] = $request->inventory_type;
            $updated['notes'] = $request->notes;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['date'] = date('Y-m-d');
            $updated['updated_by'] = auth()->user()->id;
            $updated['com_code'] = auth()->user()->com_code;

            InvStoreInventoryHeader::where('id', $id)->update($updated);

            return redirect()->route('admin.inv_stores_inventory.index')->with('success', 'تم الاضافة بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $check_items = InvStoreInventoryDetail::where('inv_stores_inventory_header_id', $id)->count();
        if ($check_items > 0) {
            return redirect()->back()->with('error', 'لا يمكن حذف الجرد لاحتوائه على بتشات جرد');
        }

        InvStoreInventoryHeader::where('id', $id)->delete();

        return redirect()->route('admin.inv_stores_inventory.index')->with('success', 'تم الحذف بنجاح');
    }

    public function create_detail(Request $request, $id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = InvStoreInventoryHeader::where('id', $id)->get()->first();
            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى البيانات المطلوبة');
            }
            if ($data['is_closed'] == 1) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى الجرد الذي قد تم اغلاقه وترحيله');
            }

            if ($request->all_items == 0) {
                $items_card = InvItemCardBatch::where(['item_code' => $request->item_code, 'store_id' => $data['store_id'], 'com_code' => $com_code])->distinct()->get('item_code');
            }
            else {
                $items_card = InvItemCardBatch::where(['store_id' => $data['store_id'], 'com_code' => $com_code])->distinct()->get('item_code');
            }

            if (!empty($items_card)) {
                foreach ($items_card as $item) {
                    if ($request->empty_batches == 0) {
                        $batches = InvItemCardBatch::where(['item_code' => $item['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->where('quantity', '>', 0)->get();
                    }
                    else {
                        $batches = InvItemCardBatch::where(['item_code' => $item['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->get();
                    }

                    foreach ($batches as $batch) {
                        $check_batch = InvStoreInventoryDetail::where(['inv_stores_inventory_header_id' => $data['id'], 'batch_id' => $batch['id']])->get('id')->first();
                        if (empty($check_batch)) {
                            $inserted['inv_stores_inventory_header_id'] = $data['id'];
                            $inserted['item_code'] = $item['item_code'];
                            $inserted['batch_id'] = $batch['id'];
                            $inserted['old_quantity'] = $batch['quantity'];
                            $inserted['new_quantity'] = $batch['quantity'];
                            $inserted['created_at'] = date('Y-m-d H:i:s');
                            $inserted['added_by'] = auth()->user()->id;
                            $inserted['com_code'] = auth()->user()->com_code;

                            InvStoreInventoryDetail::create($inserted);
                        }
                    }
                }
            }

            return redirect()->route('admin.inv_stores_inventory.details', $data['id'])->with('success', 'تم الاضافة بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function load_modal_update_batch(Request $request) {
        try {
            if($request->ajax()) {
                $detail = InvStoreInventoryDetail::where('id', $request->id)->get()->first();
                return view('admin.inv_stores_inventory.load_modal_update_batch', ['detail' => $detail]);
            }
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit_detail(Request $request) {
        try {
            $com_code = auth()->user()->com_code;
            $data = InvStoreInventoryHeader::where('id', $request->inventory_id)->get('id', 'is_closed')->first();
            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى البيانات المطلوبة');
            }
            if ($data['is_closed'] == 1) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى الجرد الذي قد تم اغلاقه وترحيله');
            }

            $detail = InvStoreInventoryDetail::where('id', $request->detail_id)->get('is_closed')->first();
            if ($detail['is_closed'] == 1) {
                return redirect()->back()->with('error', 'لا يمكن تحديث الباتش الذي قد تم اغلاقه وترحيله');
            }


            $updated['new_quantity'] = $request->new_quantity;
            $updated['different_quantity'] = $request->new_quantity - $request->old_quantity;
            $updated['notes'] = $request->notes;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['updated_by'] = auth()->user()->id;

            InvStoreInventoryDetail::where(['id' => $request->detail_id, 'inv_stores_inventory_header_id' => $request->inventory_id])->update($updated);

            return redirect()->route('admin.inv_stores_inventory.details', $data['id'])->with('success', 'تم التعديل بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_detail($detail_id, $header_id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = InvStoreInventoryHeader::where('id', $header_id)->get('id', 'is_closed')->first();
            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى البيانات المطلوبة');
            }
            if ($data['is_closed'] == 1) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى الجرد الذي قد تم اغلاقه وترحيله');
            }

            $detail = InvStoreInventoryDetail::where('id', $detail_id)->get('is_closed')->first();
            if ($detail['is_closed'] == 1) {
                return redirect()->back()->with('error', 'لا يمكن تحديث الباتش الذي قد تم اغلاقه وترحيله');
            }

            InvStoreInventoryDetail::where(['id' => $detail_id, 'inv_stores_inventory_header_id' => $header_id])->delete();

            return redirect()->route('admin.inv_stores_inventory.details', $data['id'])->with('success', 'تم الحذف بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function close_detail($detail_id, $header_id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = InvStoreInventoryHeader::where('id', $header_id)->get(['id', 'is_closed', 'store_id'])->first();
            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى البيانات المطلوبة');
            }
            if ($data['is_closed'] == 1) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى الجرد الذي قد تم اغلاقه وترحيله');
            }

            $detail = InvStoreInventoryDetail::where(['inv_stores_inventory_header_id' => $header_id, 'id' => $detail_id])->get(['id', 'is_closed', 'batch_id', 'item_code', 'new_quantity'])->first();
            if ($detail['is_closed'] == 1) {
                return redirect()->back()->with('error', 'لا يمكن تحديث الباتش الذي قد تم اغلاقه وترحيله');
            }

            // before i make insert or update i should get the quantity in all store and current store from the batch
            $quantity_in_batch_before = InvItemCardBatch::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->sum('quantity');
            $quantity_in_batch_current_store_before = InvItemCardBatch::where(['item_code' => $detail['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');


            $batch = InvItemCardBatch::where('id', $detail['batch_id'])->get(['unit_cost_price', 'inv_unit_id'])->first();
            $update_batch['quantity'] = $detail->new_quantity;
            $update_batch['total_cost_price'] = $detail->new_quantity * $batch->unit_cost_price;
            $flag = InvItemCardBatch::where('id', $detail['batch_id'])->update($update_batch);

            if ($flag) {
                $update_detail['is_closed'] = 1;
                $update_detail['closed_at'] = date('Y-m-d H:i:s');
                $update_detail['closed_by'] = auth()->user()->id;
                $flag = InvStoreInventoryDetail::where(['id' => $detail_id, 'inv_stores_inventory_header_id' => $header_id])->update($update_detail);

                if ($flag) {

                    // get the quantity in all store and current store from the batch and we will get the name of the master unit
                    $quantity_in_batch_after = InvItemCardBatch::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->sum('quantity');
                    $quantity_in_batch_current_store_after = InvItemCardBatch::where(['item_code' => $detail['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');
                    $parent_unit_name = InvUnit::where('id', $batch['unit_id'])->value('name');


                    // Then we will save this change with item card in the item card movements table
                    $insertItemMovement['inv_item_card_movements_categories_id'] = 3;
                    $insertItemMovement['item_code'] = $detail['item_code'];
                    $insertItemMovement['inv_item_card_movements_types_id'] = 15;
                    $insertItemMovement['store_id'] = $data['store_id'];
                    $insertItemMovement['batch_id'] = $detail['batch_id'];
                    $insertItemMovement['quantity_before_movement'] = $quantity_in_batch_before . ' ' . $parent_unit_name;
                    $insertItemMovement['quantity_after_movement'] = $quantity_in_batch_after . ' ' . $parent_unit_name;
                    $insertItemMovement['quantity_before_movement_in_current_store'] = $quantity_in_batch_current_store_before . ' ' . $parent_unit_name;
                    $insertItemMovement['quantity_after_movement_in_current_store'] = $quantity_in_batch_current_store_after . ' ' . $parent_unit_name;
                    $insertItemMovement['byan'] = 'جرد مخازن رقم الجرد ' . $data['id'] . '  رقم الباتش في اصناف الجرد ' . $detail['id'];
                    $insertItemMovement['created_at'] = date('Y-m-d H:i:s');
                    $insertItemMovement['date'] = date('Y-m-d');
                    $insertItemMovement['added_by'] = auth()->user()->id;
                    $insertItemMovement['com_code'] = $com_code;

                    InvItemCardMovement::create($insertItemMovement);


                    // update the quantity in item_card
                    $all_quantity = InvItemCardBatch::where(['id' => $detail['batch_id']])->sum('quantity');
                    $item_card_data = InvItemCard::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->get(['does_has_retailunit', 'retail_uom_quntToParent'])->first();
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
                    InvItemCard::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->update($update_item_card_quantity);
                }
            }

            return redirect()->route('admin.inv_stores_inventory.details', $data['id'])->with('success', 'تم الترحيل بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function close_header($header_id) {
        try {
            $com_code = auth()->user()->com_code;
            $data = InvStoreInventoryHeader::where('id', $header_id)->get(['id', 'is_closed', 'store_id'])->first();
            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى البيانات المطلوبة');
            }
            if ($data['is_closed'] == 1) {
                return redirect()->back()->with('error', 'لا يمكن الوصول الى الجرد الذي قد تم اغلاقه وترحيله');
            }

            $details = InvStoreInventoryDetail::where(['inv_stores_inventory_header_id' => $header_id, 'is_closed' => 0])->get(['id', 'batch_id', 'item_code', 'new_quantity']);

            if (!empty($details)) {
                foreach ($details as $detail) {
                    // before i make insert or update i should get the quantity in all store and current store from the batch
                    $quantity_in_batch_before = InvItemCardBatch::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->sum('quantity');
                    $quantity_in_batch_current_store_before = InvItemCardBatch::where(['item_code' => $detail['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');

                    $batch = InvItemCardBatch::where('id', $detail['batch_id'])->get(['unit_cost_price', 'inv_unit_id'])->first();
                    $update_batch['quantity'] = $detail->new_quantity;
                    $update_batch['total_cost_price'] = $detail->new_quantity * $batch->unit_cost_price;
                    $flag = InvItemCardBatch::where('id', $detail['batch_id'])->update($update_batch);

                    if ($flag) {
                        $update_detail['is_closed'] = 1;
                        $update_detail['closed_at'] = date('Y-m-d H:i:s');
                        $update_detail['closed_by'] = auth()->user()->id;
                        $flag = InvStoreInventoryDetail::where(['id' => $detail['id'], 'inv_stores_inventory_header_id' => $header_id])->update($update_detail);

                        if ($flag) {

                            // get the quantity in all store and current store from the batch and we will get the name of the master unit
                            $quantity_in_batch_after = InvItemCardBatch::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->sum('quantity');
                            $quantity_in_batch_current_store_after = InvItemCardBatch::where(['item_code' => $detail['item_code'], 'store_id' => $data['store_id'], 'com_code' => $com_code])->sum('quantity');
                            $parent_unit_name = InvUnit::where('id', $batch['unit_id'])->value('name');


                            // Then we will save this change with item card in the item card movements table
                            $insertItemMovement['inv_item_card_movements_categories_id'] = 3;
                            $insertItemMovement['item_code'] = $detail['item_code'];
                            $insertItemMovement['inv_item_card_movements_types_id'] = 15;
                            $insertItemMovement['store_id'] = $data['store_id'];
                            $insertItemMovement['batch_id'] = $detail['batch_id'];
                            $insertItemMovement['quantity_before_movement'] = $quantity_in_batch_before . ' ' . $parent_unit_name;
                            $insertItemMovement['quantity_after_movement'] = $quantity_in_batch_after . ' ' . $parent_unit_name;
                            $insertItemMovement['quantity_before_movement_in_current_store'] = $quantity_in_batch_current_store_before . ' ' . $parent_unit_name;
                            $insertItemMovement['quantity_after_movement_in_current_store'] = $quantity_in_batch_current_store_after . ' ' . $parent_unit_name;
                            $insertItemMovement['byan'] = 'جرد مخازن رقم الجرد ' . $data['id'] . '  رقم الباتش في اصناف الجرد ' . $detail['id'];
                            $insertItemMovement['created_at'] = date('Y-m-d H:i:s');
                            $insertItemMovement['date'] = date('Y-m-d');
                            $insertItemMovement['added_by'] = auth()->user()->id;
                            $insertItemMovement['com_code'] = $com_code;

                            InvItemCardMovement::create($insertItemMovement);


                            // update the quantity in item_card
                            $all_quantity = InvItemCardBatch::where(['id' => $detail['batch_id']])->sum('quantity');
                            $item_card_data = InvItemCard::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->get(['does_has_retailunit', 'retail_uom_quntToParent'])->first();
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
                            InvItemCard::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->update($update_item_card_quantity);
                        }
                    }
                }

                $update_header['is_closed'] = 1;
                $update_header['closed_at'] = date('Y-m-d H:i:s');
                $update_header['closed_by'] = auth()->user()->id;
                InvStoreInventoryHeader::where(['id' => $header_id])->update($update_header);
            }

            return redirect()->route('admin.inv_stores_inventory.index')->with('success', 'تم الترحيل بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function printA4($id)
    {
        //
        try {
            $com_code = auth()->user()->com_code;
            $data = InvStoreInventoryHeader::where('id', $id)->get()->first();
            if (!empty($data)) {
                $data['store_name'] = Store::where('id', $data['store_id'])->value('name');
            }

            $details = InvStoreInventoryDetail::where('inv_stores_inventory_header_id', $id)->get();
            if (!empty($details)) {
                foreach ($details as $detail) {
                    $batch = InvItemCardBatch::where(['id' => $detail['batch_id'], 'com_code' => $com_code])->get()->first();
                    $detail['item_name'] = InvItemCard::where(['item_code' => $detail['item_code'], 'com_code' => $com_code])->value('name');
                    $detail['unit_price'] = $batch['unit_cost_price'];
                }
            }

            $systemData = AdminPanelSetting::where('com_code', $com_code)->get()->first();

            return view('admin.inv_stores_inventory.printA4', ['data' => $data, 'details' => $details, 'systemData' => $systemData]);
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ajax_search(Request $request) {
        if ($request->ajax()) {

            $store_id = $request->store_id;
            $inventory_type = $request->inventory_type;
            $is_closed = $request->is_closed;
            $from_date = $request->from_date;
            $to_date = $request->to_date;


            if ($store_id == 'all') {
                $filed1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            }
            else {
                $filed1 = 'store_id';
                $operator1 = '=';
                $value1 = $store_id;
            }

            if ($inventory_type == 'all') {
                $filed2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            }
            else {
                $filed2 = 'inventory_type';
                $operator2 = '=';
                $value2 = $inventory_type;
            }

            if ($is_closed == 'all') {
                $filed3 = 'id';
                $operator3 = '>';
                $value3 = 0;
            }
            else {
                $filed3 = 'is_closed';
                $operator3 = '=';
                $value3 = $is_closed;
            }

            if ($from_date == '') {
                $filed4 = 'id';
                $operator4 = '>';
                $value4 = 0;
            }
            else {
                $filed4 = 'inventory_date';
                $operator4 = '>=';
                $value4 = $from_date;
            }

            if ($to_date == '') {
                $filed5 = 'id';
                $operator5 = '>';
                $value5 = 0;
            }
            else {
                $filed5 = 'inventory_date';
                $operator5 = '<=';
                $value5 = $to_date;
            }


            $data = InvStoreInventoryHeader::where("$filed1", "$operator1", "$value1")->where("$filed2", "$operator2", "$value2")->where("$filed3", "$operator3", "$value3")->where("$filed4", "$operator4", "$value4")->where("$filed5", "$operator5", "$value5")->where(['com_code' => auth()->user()->com_code])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);

            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['store_name'] = Store::where('id', $d['store_id'])->value('name');
                }
            }
            return view('admin.inv_stores_inventory.ajax_search', ['data' => $data]);

        }
    }
}
