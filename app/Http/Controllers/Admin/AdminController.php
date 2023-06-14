<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Treasury;
use App\Models\AdminTreasury;
use Exception;

class AdminController extends Controller
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
            $data = Admin::where('com_code', auth()->user()->com_code)->paginate(PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $d) {
                    if ($d['added_by'] != null) {
                        $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
                    }

                    if ($d['updated_by'] != null) {
                        $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
                    }
                }
            }

            return view('admin.admins.index', ['data' => $data]);
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
        //
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
            $data = Admin::find($id);

            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يوجد خزينة كهذه');
            }

            // foreach ($data as $d) {
            //     $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
            //     $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
            // }


            $treasuries = AdminTreasury::where('admin_id', $id)->get();
            if (!empty($treasuries)) {
                foreach($treasuries as $tr) {
                    $tr['treasury_name'] = Treasury::where('id', $tr['treasuries_id'])->value('name');
                    $tr['added_by_name'] = Admin::where('id', $tr['added_by'])->value('name');
                    $tr['updated_by_name'] = Admin::where('id', $tr['updated_by'])->value('name');
                }
            }

            return view('admin.admins.details', ['data' => $data, 'treasuries' => $treasuries]);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function create_treasuries($id)
    {
        # code...
        $check = Admin::find($id);
        if (empty($check)) {
            return redirect()->back()->with('error', 'لا يوجد مستخدم كهذا');
        }

        $data = Treasury::get();
        return view('admin.admins.create_treasuries', ['data' => $data, 'admin_id' => $id]);
    }

    public function store_treasuries(Request $request, $id)
    {
        # code...
        $request->validate(
            [
                'treasuries_id' => 'required',
                'active' => 'required',
            ]
        );

        $check = AdminTreasury::where(['admin_id' => $id, 'treasuries_id' => $request->treasuries_id, 'com_code' => auth()->user()->com_code])->first();
        if (!empty($check)) {
            return redirect()->back()->with('error', 'اسم الخزنة لديه الصلاحية عيها مسبقا')->withInput();
        }

        $inserted['admin_id'] = $id;
        $inserted['treasuries_id'] = $request->treasuries_id;
        $inserted['active'] = $request->active;
        $inserted['added_by'] = auth()->user()->id;
        $inserted['created_at'] = date('Y-m-d H:i:s');
        $inserted['com_code'] = auth()->user()->com_code;

        AdminTreasury::create($inserted);

        return redirect()->route('admin.admins.details', $id)->with('success', 'تم اضافة الصلاحية بنجاح');
    }

    public function delete_treasuries($admin_id, $treasuries_id)
    {
        # code...
        $check = AdminTreasury::where(['admin_id' => $admin_id, 'treasuries_id' => $treasuries_id, 'com_code' => auth()->user()->com_code])->first();
        if (empty($check)) {
            return redirect()->back()->with('error', 'لا توجد بيانات كهذه')->withInput();
        }

        AdminTreasury::where(['admin_id' => $admin_id, 'treasuries_id' => $treasuries_id, 'com_code' => auth()->user()->com_code])->delete();

        return redirect()->route('admin.admins.details', $admin_id)->with('success', 'تم حذف الصلاحية بنجاح');
    }
}
