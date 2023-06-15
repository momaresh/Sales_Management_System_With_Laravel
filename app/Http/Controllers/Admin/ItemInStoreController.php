<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvItemCard;
use App\Models\InvItemCardBatch;
use App\Models\Store;
use App\Models\Admin;
use App\Models\InvUnit;
use Exception;

class ItemInStoreController extends Controller
{

    public function index()
    {
        //
        try {
            $com_code = auth()->user()->com_code;
            $data = InvItemCardBatch::where(['com_code' => $com_code])->paginate(PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['store_name'] = Store::where('id', $d['store_id'])->value('name');
                    $d['item_name'] = InvItemCard::where(['item_code' => $d['item_code'], 'com_code' => $com_code])->value('name');
                    $d['unit_name'] = InvUnit::where(['id' => $d['inv_unit_id'], 'com_code' => $com_code])->value('name');
                }
            }

            $items = InvItemCard::where(['com_code' => $com_code])->get(['item_code', 'name']);
            $units = InvUnit::where(['com_code' => $com_code, 'master' => 1])->get(['id', 'name']);
            $stores = Store::where(['com_code' => $com_code])->get(['id', 'name']);
            return view('admin.items_in_stores.index', ['data' => $data, 'items' => $items, 'units' => $units, 'stores' => $stores]);
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ajax_search(Request $request) {
        if ($request->ajax()) {

            $item_code_search = $request->item_code_search;
            $unit_id_search = $request->unit_id_search;
            $store_id_search = $request->store_id_search;
            $production_date_search = $request->production_date_search;
            $expire_date_search = $request->expire_date_search;


            if ($item_code_search == 'all') {
                $filed1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            }
            else {
                $filed1 = 'item_code';
                $operator1 = 'LIKE';
                $value1 = '%'. $item_code_search . '%';
            }


            if ($unit_id_search == 'all') {
                $filed2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            }
            else {
                $filed2 = 'inv_unit_id';
                $operator2 = '=';
                $value2 = $unit_id_search;
            }

            if ($store_id_search == 'all') {
                $filed3 = 'id';
                $operator3 = '>';
                $value3 = 0;
            }
            else {
                $filed3 = 'store_id';
                $operator3 = '=';
                $value3 = $store_id_search;
            }

            if ($production_date_search == '') {
                $filed4 = 'id';
                $operator4 = '>';
                $value4 = 0;
            }
            else {
                $filed4 = 'production_date';
                $operator4 = '=';
                $value4 = $production_date_search;
            }

            if ($expire_date_search == '') {
                $filed5 = 'id';
                $operator5 = '>';
                $value5 = 0;
            }
            else {
                $filed5 = 'expire_date';
                $operator5 = '=';
                $value5 = $expire_date_search;
            }

            $com_code = auth()->user()->com_code;
            $data = InvItemCardBatch::where($filed1, $operator1, $value1)->where($filed2, $operator2, $value2)->where($filed3, $operator3, $value3)->where($filed4, $operator4, $value4)->where($filed5, $operator5, $value5)->where(['com_code' => $com_code])->paginate(PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['store_name'] = Store::where('id', $d['store_id'])->value('name');
                    $d['item_name'] = InvItemCard::where(['item_code' => $d['item_code'], 'com_code' => $com_code])->value('name');
                    $d['unit_name'] = InvUnit::where(['id' => $d['inv_unit_id'], 'com_code' => $com_code])->value('name');
                }
            }

            return view('admin.items_in_stores.ajax_search', ['data' => $data]);

        }
    }


}
