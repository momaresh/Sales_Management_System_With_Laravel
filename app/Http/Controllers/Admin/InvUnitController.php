<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\InvUnit;
use App\Http\Requests\CreateUnitRequest;
use App\Models\InvItemCard;
use App\Models\InvoiceOrderDetail;
use Exception;

class InvUnitController extends Controller
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
            $data = InvUnit::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'desc')->paginate(PAGINATION_COUNT);

            if (!empty($data)) {
                foreach ($data as $d) {
                    if ($d['added_by'] != null) {
                        $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
                    }

                    if ($d['updated_by'] != null) {
                        $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
                    }

                    // if ($d['com_code'] != null) {
                    //     $d['com_code_name'] = AdminPanelSetting::where('id', $d['com_code'])->value('system_name');
                    // }
                }
            }

            return view('admin.inv_units.index', ['data' => $data]);
        }
        catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
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
        return view('admin.inv_units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUnitRequest $request)
    {
        //
        try {
            $inserted['name'] = $request->name;
            $inserted['active'] = $request->active;
            $inserted['master'] = $request->master;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            InvUnit::create($inserted);

            return redirect()->route('admin.inv_units.index')->with('success', 'تم اضافة الوحدة بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $data = InvUnit::find($id);
        if (empty($data)) {
            return redirect()->route('admin.inv_units.index')->with('error', 'لا يوجد بيانات كهذه');
        }

        $used1 = InvItemCard::where(['unit_id' => $id])->orWhere(['retail_unit_id' => $id])->value('name');
        $used2 = InvoiceOrderDetail::where(['unit_id' => $id, 'com_code' => auth()->user()->com_code])->value('unit_id');
        if (!empty($used1) || !empty($used2)) {
            $data['unit_used'] = true;
        }
        else {
            $data['unit_used'] = false;
        }
        return view('admin.inv_units.edit', ['data' => $data]);
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
        $request->validate([
            'name' => 'required|unique:inv_units,name,'.$id,
            'master' =>'required',
            'active' =>'required',
        ]);

        try {

            $updated['name'] = $request->name;
            $updated['master'] = $request->master;
            $updated['active'] = $request->active;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['com_code'] = auth()->user()->com_code;

            InvUnit::where('id', $id)->update($updated);

            return redirect()->route('admin.inv_units.index')->with('success', 'تم تعديل الوحدة بنجاح');

        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
        try {

            $data_check = InvUnit::where(['id' => $id, 'com_code' => auth()->user()->id])->first();


            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            $flag = InvUnit::where(['id' => $id, 'com_code' => auth()->user()->id])->delete();

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


    public function ajax_search(Request $request) {
        if ($request->ajax()) {


            $search_by_name = $request->search_by_name;
            $search_by_type = $request->search_by_type;



            if ($search_by_name == '') {
                $filed1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            }
            else {
                $filed1 = 'name';
                $operator1 = 'LIKE';
                $value1 = $search_by_name;
            }

            if ($search_by_type == 'all') {
                $filed2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            }
            else {
                $filed2 = 'master';
                $operator2 = '=';
                $value2 = $search_by_type;
            }

            $data = InvUnit::where("$filed1", "$operator1", "%$value1%")->where("$filed2", "$operator2", "$value2")->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
            return view('admin.inv_units.ajax_search', ['data' => $data]);

        }
    }
}
