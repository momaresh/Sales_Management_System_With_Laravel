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
        if (check_control_menu_role('المخازن', 'الوحدات' , 'عرض') == true) {
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
                    }
                }

                return view('admin.inv_units.index', ['data' => $data]);
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
        if (check_control_menu_role('المخازن', 'الوحدات' , 'اضافة') == true) {
            return view('admin.inv_units.create');
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
    public function store(CreateUnitRequest $request)
    {
        //
        if (check_control_menu_role('المخازن', 'الوحدات' , 'اضافة') == true) {
            try {
                $com_code = auth()->user()->com_code;

                $check = InvUnit::where(['name' => $request->name , 'com_code' => $com_code])->count();
                if ($check > 0) {
                    return redirect()->back()->with('error', 'اسم الوحدة مسجل مسبقاً')->withInput();
                }

                $unit_code = InvUnit::where(['com_code' => $com_code])->max('unit_code');
                if (empty($unit_code)) {
                    $inserted['unit_code'] = 1;
                }
                else {
                    $inserted['unit_code'] = $unit_code + 1;
                }

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
        else {
            return redirect()->back();
        }

    }

    public function edit($id)
    {
        //
        if (check_control_menu_role('المخازن', 'الوحدات' , 'تعديل') == true) {
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
        else {
            return redirect()->back();
        }

    }


    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'required',
            'master' =>'required',
            'active' =>'required',
        ]);

        if (check_control_menu_role('المخازن', 'الوحدات' , 'تعديل') == true) {
            try {
                $com_code = auth()->user()->com_code;

                $check = InvUnit::where(['name' => $request->name , 'com_code' => $com_code])->where('id', '!=', $id)->count();
                if ($check > 0) {
                    return redirect()->back()->with('error', 'اسم الوحدة مسجل مسبقاً')->withInput();
                }

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
        else {
            return redirect()->back();
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
        if (check_control_menu_role('المخازن', 'الوحدات' , 'حذف') == true) {
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
        else {
            return redirect()->back();
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

            $data = InvUnit::where("$filed1", "$operator1", "%$value1%")->where("$filed2", "$operator2", "$value2")->where(['com_code' => auth()->user()->com_code])->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
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

            return view('admin.inv_units.ajax_search', ['data' => $data]);

        }
    }
}
