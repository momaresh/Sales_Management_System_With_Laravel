<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminShift;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Treasury;
use App\Models\AdminTreasury;
use Exception;


class AdminShiftController extends Controller
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
            $data = AdminShift::where(['com_code' => auth()->user()->com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['admin_name'] = Admin::where(['id' => $d['admin_id']])->value('name');
                    $d['treasuries_name'] = Treasury::where(['id' => $d['treasuries_id']])->value('name');
                }
            }
            return view('admin.admin_shifts.index', ['data' => $data]);
        }
        catch (Exception $e) {

        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // we need to get all the treasuries that the admin has privilege to and no other user still work on it
        $treasuries = AdminTreasury::where(['admin_id' => auth()->user()->id, 'com_code' => auth()->user()->com_code, 'active' => 1])->get('treasuries_id');
        $not_available_treasuries_array = array();

        if (!empty($treasuries)) {
            $i = 0;
            foreach ($treasuries as $tr) {
                $id = AdminShift::where(['treasuries_id' => $tr['treasuries_id'], 'com_code' => auth()->user()->com_code, 'is_finished' => 0])->value('treasuries_id');
                if (!empty($id)) {
                    $not_available_treasuries_array[$i] = $tr['treasuries_id'];
                    $i++;
                }
            }
        }


        $available_treasuries = AdminTreasury::where(['admin_id' => auth()->user()->id, 'com_code' => auth()->user()->com_code, 'active' => 1])->whereNotIn('treasuries_id', $not_available_treasuries_array)->get('treasuries_id');

        if (!empty($available_treasuries)) {
            foreach ($available_treasuries as $tr) {
                $tr['treasuries_name'] = Treasury::where(['id' => $tr['treasuries_id'], 'com_code' => auth()->user()->com_code])->value('name');
            }
        }

        return view('admin.admin_shifts.create', ['treasuries' => $available_treasuries]);
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
        $request->validate([
            'treasuries_id' => 'required'
        ],
        [
            'treasuries_id.required' => 'اسم الخزنة مطلوب'
        ]);


        try {
            // we check that the user not has other shift work on and still not finish
            $has_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => auth()->user()->com_code, 'is_finished' => 0])->first();
            if (!empty($has_shift)) {
                return redirect()->back()->with('error', 'انت تملك شفت ما زال مستخدما')->withInput();
            }

            $max_code = AdminShift::where(['com_code' => auth()->user()->com_code])->max('shift_code');

            if (empty($max_code)) {
                $inserted['shift_code'] = 1;
            }
            else {
                $inserted['shift_code'] = $max_code + 1;
            }

            $inserted['admin_id'] = auth()->user()->id;
            $inserted['treasuries_id'] = $request->treasuries_id;
            $inserted['start_date'] = date('Y-m-d H:i:s');
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            AdminShift::create($inserted);
            return redirect()->route('admin.admin_shifts.index')->with('success', 'تم استلام الشفت بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

}
