<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvItemCard;
use App\Models\Admin;
use App\Models\InvUnit;
use App\Models\InvItemCategory;
use App\Http\Requests\CreateItemCardRequest;
use App\Models\InvItemCardMovement;
use App\Models\InvItemCardMovementCategory;
use App\Models\InvItemCardMovementType;
use App\Models\InvoiceOrderDetail;
use App\Models\Store;
use Exception;

class InvItemCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (check_control_menu_role('المخازن', 'الاصناف' , 'عرض') == true) {
            try {
                $inv_itemCard_categories = InvItemCategory::where('com_code', auth()->user()->com_code)->get(['id', 'name']);

                $data = InvItemCard::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'desc')->paginate(PAGINATION_COUNT);

                if (!empty($data)) {
                    foreach ($data as $d) {
                        if ($d['inv_itemcard_categories_id'] != null) {
                            $d['inv_itemcard_categories_name'] = InvItemCategory::where('id', $d['inv_itemcard_categories_id'])->value('name');
                        }

                        if ($d['parent_inv_itemcard_id'] != null) {
                            $d['parent_inv_itemcard_name'] = InvItemCard::where('id', $d['parent_inv_itemcard_id'])->value('name');
                        }
                        else {
                            $d['parent_inv_itemcard_name'] = 'لا يوجد';
                        }

                        if ($d['retail_unit_id'] != null) {
                            $d['retail_unit_name'] = InvUnit::where('id', $d['retail_unit_id'])->value('name');
                        }
                        else {
                            $d['retail_unit_name'] = 'لا يوجد';
                        }

                        if ($d['unit_id'] != null) {
                            $d['unit_name'] = InvUnit::where('id', $d['unit_id'])->value('name');
                        }
                    }
                }

                return view('admin.inv_item_card.index', ['data' => $data, 'inv_itemCard_categories' => $inv_itemCard_categories]);
            }
            catch(Exception $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
        }
        else {
            return redirect()->back();
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
        if (check_control_menu_role('المخازن', 'الاصناف' , 'اضافة') == true) {
            $inv_itemCard_categories = InvItemCategory::select(['id', 'name'])->where(['com_code' => auth()->user()->com_code, 'active' => 1])->get();
            $item_card_data = InvItemCard::select(['id', 'name'])->where(['com_code' => auth()->user()->com_code, 'active' => 1])->get();
            $inv_unit_parent = InvUnit::select(['id', 'name'])->where(['com_code' => auth()->user()->com_code, 'active' => 1, 'master' => 1])->get();
            $inv_unit_child = InvUnit::select(['id', 'name'])->where(['com_code' => auth()->user()->com_code, 'active' => 1, 'master' => 0])->get();

            return view('admin.inv_item_card.create', ['inv_itemCard_categories' => $inv_itemCard_categories,
                                                    'item_card_data' => $item_card_data,
                                                    'inv_unit_parent' => $inv_unit_parent,
                                                    'inv_unit_child' => $inv_unit_child]);
        }
        else {
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (check_control_menu_role('المخازن', 'الاصناف' , 'اضافة') == true) {
            try {
                $item_code = InvItemCard::where('com_code', auth()->user()->com_code)->max('item_code');
                if (!empty($item_code)) {
                    $inserted['item_code'] = $item_code + 1;
                }
                else {
                    $inserted['item_code'] = 1;
                }

                // If the barcode already exists with the same company
                if ($request->barcode != '') {
                    $check_barcode = InvItemCard::where(['barcode' => $request->barcode, 'com_code' => auth()->user()->com_code])->first();
                    if (!empty($check_barcode)) {
                        return redirect()->back()->with(['error' => 'اسم الباركود موجود مسبقا'])->withInput();
                    }
                    else {
                        $inserted['barcode'] = $request->barcode;
                    }
                }
                else {
                    $inserted['barcode'] = 'item' . $inserted['item_code'];
                }

                // If the name already exists with the same company
                if ($request->name != '') {
                    $check_name = InvItemCard::where(['name' => $request->name, 'com_code' => auth()->user()->com_code])->first();
                    if (!empty($check_name)) {
                        return redirect()->back()->with(['error' => 'اسم الصنف موجود مسبقا'])->withInput();
                    }
                    else {
                        $inserted['name'] = $request->name;
                    }
                }

                $inserted['item_type'] = $request->item_type;
                $inserted['inv_itemcard_categories_id'] = $request->inv_itemcard_categories_id;
                if (!empty($request->parent_inv_itemcard_id)) {
                    $inserted['parent_inv_itemcard_id'] = $request->parent_inv_itemcard_id;
                }
                $inserted['does_has_retailunit'] = $request->does_has_retailunit;
                $inserted['unit_id'] = $request->unit_id;
                $inserted['retail_unit_id'] = $request->retail_unit_id;
                $inserted['retail_uom_quntToParent'] = $request->retail_uom_quntToParent;
                $inserted['has_fixed_price'] = $request->has_fixed_price;
                $inserted['active'] = $request->active;
                $inserted['added_by'] = auth()->user()->id;
                $inserted['created_at'] = date('Y-m-d H:i:s');
                $inserted['date'] = date('Y-m-d');
                $inserted['com_code'] = auth()->user()->com_code;

                if (!empty($request->item_img)) {
                    $image = $request->item_img;
                    $extension = strtolower($image->extension());
                    $file_name = time() . rand(1, 1000) . '.' . $extension;
                    $image->move('assets\admin\uploads\item_card_images\\', $file_name);
                    $inserted['item_img'] = $file_name;
                }

                InvItemCard::create($inserted);

                return redirect()->route('admin.inv_item_card.index')->with('success', 'تم اضافة الصنف بنجاح');
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
        }
        else {
            return redirect()->back();
        }


    }

    public function edit($id)
    {
        //
        if (check_control_menu_role('المخازن', 'الاصناف' , 'تعديل') == true) {
            $data = InvItemCard::find($id);
            $data['is_used'] = InvoiceOrderDetail::where(['item_code' => $data['item_code'],'com_code' => auth()->user()->com_code])->value('id');
            $inv_itemCard_categories = InvItemCategory::select(['id', 'name'])->where(['com_code' => auth()->user()->com_code, 'active' => 1])->get();
            $item_card_data = InvItemCard::select(['id', 'name'])->where(['com_code' => auth()->user()->com_code, 'active' => 1])->get();
            $inv_unit_parent = InvUnit::select(['id', 'name'])->where(['com_code' => auth()->user()->com_code, 'active' => 1, 'master' => 1])->get();
            $inv_unit_child = InvUnit::select(['id', 'name'])->where(['com_code' => auth()->user()->com_code, 'active' => 1, 'master' => 0])->get();

            return view('admin.inv_item_card.edit', [
                                                    'data' => $data,
                                                    'inv_itemCard_categories' => $inv_itemCard_categories,
                                                    'item_card_data' => $item_card_data,
                                                    'inv_unit_parent' => $inv_unit_parent,
                                                    'inv_unit_child' => $inv_unit_child
                                                    ]);
        }
        else {
            return redirect()->back();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateItemCardRequest $request, $id)
    {
        if (check_control_menu_role('المخازن', 'الاصناف' , 'تعديل') == true) {
            try {
                // If the name already exists with the same company
                if ($request->name != '') {

                    $check_name = InvItemCard::where(['name' => $request->name, 'com_code' => auth()->user()->com_code])->where('id', '!=', $id)->first();
                    if (!empty($check_name)) {
                        return redirect()->back()->with(['error' => 'اسم الصنف موجود مسبقا'])->withInput();
                    }
                    else {
                        $updated['name'] = $request->name;
                    }
                }


                $updated['item_type'] = $request->item_type;
                $updated['inv_itemcard_categories_id'] = $request->inv_itemcard_categories_id;
                if (!empty($request->parent_inv_itemcard_id)) {
                    $updated['parent_inv_itemcard_id'] = $request->parent_inv_itemcard_id;
                }
                $updated['unit_id'] = $request->unit_id;
                $updated['has_fixed_price'] = $request->has_fixed_price;
                $updated['active'] = $request->active;
                $updated['updated_by'] = auth()->user()->id;
                $updated['updated_at'] = date('Y-m-d H:i:s');
                $updated['date'] = date('Y-m-d');
                $updated['com_code'] = auth()->user()->com_code;

                $updated['does_has_retailunit'] = $request->does_has_retailunit;

                if ($request->does_has_retailunit != 0 || $request->does_has_retailunit != '') {
                    $updated['retail_unit_id'] = $request->retail_unit_id;
                    $updated['retail_uom_quntToParent'] = $request->retail_uom_quntToParent;
                }

                if (!empty($request->item_img)) {
                    $old_image = InvItemCard::where('id', $id)->value('item_img');
                    $image = $request->item_img;
                    $extension = strtolower($image->extension());
                    $file_name = time() . rand(1, 1000) . '.' . $extension;
                    $image->move('assets\admin\uploads\item_card_images\\', $file_name);
                    $updated['item_img'] = $file_name;

                    if (!empty($old_image)) {
                        // deleting the old image from the folder
                        if(file_exists("assets/admin/uploads/item_card_images/".$old_image)) {
                            unlink("assets/admin/uploads/item_card_images/".$old_image);
                        }
                    }
                }

                InvItemCard::where('id', $id)->update($updated);

                return redirect()->route('admin.inv_item_card.index')->with('success', 'تم تعديل الصنف بنجاح');
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
        }
        else {
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     */


    public function delete($id)
    {
        # code...
        if (check_control_menu_role('المخازن', 'الاصناف' , 'حذف') == true) {
            try {
                $data_check = InvItemCard::where(['id' => $id, 'com_code' => auth()->user()->id])->first();

                if (empty($data_check)) {
                    return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
                }

                $flag = InvItemCard::where(['id' => $id, 'com_code' => auth()->user()->id])->delete();

                if ($flag) {
                    return redirect()->back()->with('success', 'تم الحذف بنجاح');
                }
                else {
                    return redirect()->back()->with('error', 'غير قادر على الحذف ');
                }

            }
            catch(Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }

    }


    public function details($id)
    {
        //
        if (check_control_menu_role('المخازن', 'الاصناف' , 'التفاصيل') == true) {
            try {
                $com_code = auth()->user()->com_code;
                $data = InvItemCard::where('id', $id)->first();

                if (empty($data)) {
                    return redirect()->back()->with('error', 'لا يوجد صنف كهذا');
                }

                $data['inv_itemcard_categories_name'] = InvItemCategory::where('id', $data['inv_itemcard_categories_id'])->value('name');
                $data['parent_inv_itemcard_name'] = InvItemCard::where('id', $data['parent_inv_itemcard_id'])->value('name');
                $data['unit_name'] = InvUnit::where('id', $data['unit_id'])->value('name');
                $data['retail_unit_name'] = InvUnit::where('id', $data['retail_unit_id'])->value('name');
                $data['added_by_name'] = Admin::where('id', $data['added_by'])->value('name');
                $data['updated_by_name'] = Admin::where('id', $data['updated_by'])->value('name');


                $categories = InvItemCardMovementCategory::get();
                $types = InvItemCardMovementType::get();
                $stores = Store::where('com_code', $com_code)->get();
                $moves = InvItemCardMovement::where(['item_code' => $data['item_code'], 'com_code' => $com_code])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);
                if (!empty($moves)) {
                    foreach ($moves as $move) {
                        $move['store_name'] = Store::where(['id' => $move['store_id'], 'com_code' => $com_code])->value('name');
                        $move['category_name'] = InvItemCardMovementCategory::where(['id' => $move['inv_item_card_movements_categories_id']])->value('name');
                        $move['type_name'] = InvItemCardMovementType::where(['id' => $move['inv_item_card_movements_types_id']])->value('type');
                    }
                }

                return view('admin.inv_item_card.details', ['data' => $data, 'moves' => $moves, 'categories' => $categories, 'types' => $types, 'stores' => $stores]);
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
            $search_by_name = $request->search_by_name;
            $search_by_type = $request->search_by_type;
            $search_by_category = $request->search_by_category;
            $search_by_radio = $request->search_by_radio;

            if ($search_by_radio == 'barcode') {
                if ($search_by_name == '') {
                    $filed1 = 'id';
                    $operator1 = '>';
                    $value1 = 0;
                }
                else {
                    $filed1 = 'barcode';
                    $operator1 = 'LIKE';
                    $value1 = '%'. $search_by_name . '%';
                }
            }
            else if ($search_by_radio == 'item_code') {
                if ($search_by_name == '') {
                    $filed1 = 'id';
                    $operator1 = '>';
                    $value1 = 0;
                }
                else {
                    $filed1 = 'item_code';
                    $operator1 = '=';
                    $value1 = $search_by_name;
                }
            }
            else if ($search_by_radio == 'name') {
                if ($search_by_name == '') {
                    $filed1 = 'id';
                    $operator1 = '>';
                    $value1 = 0;
                }
                else {
                    $filed1 = 'name';
                    $operator1 = 'LIKE';
                    $value1 = '%'.$search_by_name.'%';
                }
            }


            if ($search_by_type == 'all') {
                $filed2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            }
            else {
                $filed2 = 'item_type';
                $operator2 = '=';
                $value2 = $search_by_type;
            }

            if ($search_by_category == 'all') {
                $filed3 = 'id';
                $operator3 = '>';
                $value3 = 0;
            }
            else {
                $filed3 = 'inv_itemcard_categories_id';
                $operator3 = '=';
                $value3 = $search_by_category;
            }

            $data = InvItemCard::where("$filed1", "$operator1", "$value1")->where("$filed2", "$operator2", "$value2")->where("$filed3", "$operator3", "$value3")->where('com_code', auth()->user()->com_code)->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $d) {
                    if ($d['inv_itemcard_categories_id'] != null) {
                        $d['inv_itemcard_categories_name'] = InvItemCategory::where('id', $d['inv_itemcard_categories_id'])->value('name');
                    }

                    if ($d['parent_inv_itemcard_id'] != null) {
                        $d['parent_inv_itemcard_name'] = InvItemCard::where('id', $d['parent_inv_itemcard_id'])->value('name');
                    }
                    else {
                        $d['parent_inv_itemcard_name'] = 'لا يوجد';
                    }

                    if ($d['retail_unit_id'] != null) {
                        $d['retail_unit_name'] = InvUnit::where('id', $d['retail_unit_id'])->value('name');
                    }
                    else {
                        $d['retail_unit_name'] = 'لا يوجد';
                    }

                    if ($d['unit_id'] != null) {
                        $d['unit_name'] = InvUnit::where('id', $d['unit_id'])->value('name');
                    }
                }
            }
            return view('admin.inv_item_card.ajax_search', ['data' => $data]);

        }
    }

    public function moves_ajax_search(Request $request) {
        if ($request->ajax()) {
            $store_search = $request->store_search;
            $category_search = $request->category_search;
            $type_search = $request->type_search;
            $from_date_search = $request->from_date_search;
            $to_date_search = $request->to_date_search;
            $order_search = $request->order_search;
            $item_id_search = $request->item_id_search;

            if ($store_search == 'all') {
                $filed1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            }
            else {
                $filed1 = 'store_id';
                $operator1 = '=';
                $value1 = $store_search;
            }

            if ($category_search == 'all') {
                $filed2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            }
            else {
                $filed2 = 'inv_item_card_movements_categories_id';
                $operator2 = '=';
                $value2 = $category_search;
            }

            if ($type_search == 'all') {
                $filed3 = 'id';
                $operator3 = '>';
                $value3 = 0;
            }
            else {
                $filed3 = 'inv_item_card_movements_types_id';
                $operator3 = '=';
                $value3 = $type_search;
            }

            if ($from_date_search == '') {
                $filed4 = 'id';
                $operator4 = '>';
                $value4 = 0;
            }
            else {
                $filed4 = 'date';
                $operator4 = '>=';
                $value4 = $from_date_search;
            }

            if ($to_date_search == '') {
                $filed5 = 'id';
                $operator5 = '>';
                $value5 = 0;
            }
            else {
                $filed5 = 'date';
                $operator5 = '<=';
                $value5 = $to_date_search;
            }

            if ($item_id_search == '') {
                $filed6 = 'id';
                $operator6 = '>';
                $value6 = 0;
            }
            else {
                $filed6 = 'item_code';
                $operator6 = '=';
                $value6 = $item_id_search;
            }

            if ($order_search == 'all') {
                $filed7 = 'id';
                $value7 = 'ASC';
            }
            else if ($order_search == 'asc') {
                $filed7 = 'id';
                $value7 = 'ASC';
            }
            else if ($order_search == 'desc') {
                $filed7 = 'id';
                $value7 = 'DESC';
            }


            $moves = InvItemCardMovement::where($filed1, $operator1, $value1)->where($filed2, $operator2, $value2)->where($filed3, $operator3, $value3)->where($filed4, $operator4, $value4)->where($filed5, $operator5, $value5)->where($filed6, $operator6, $value6)->where(['com_code' => auth()->user()->com_code])->orderBy($filed7, $value7)->paginate(PAGINATION_COUNT);
            if (!empty($moves)) {
                foreach ($moves as $move) {
                    $move['store_name'] = Store::where(['id' => $move['store_id'], 'com_code' => auth()->user()->com_code])->value('name');
                    $move['category_name'] = InvItemCardMovementCategory::where(['id' => $move['inv_item_card_movements_categories_id']])->value('name');
                    $move['type_name'] = InvItemCardMovementType::where(['id' => $move['inv_item_card_movements_types_id']])->value('type');
                }
            }

            return view('admin.inv_item_card.moves_ajax_search', ['moves' => $moves]);
        }
    }
}
