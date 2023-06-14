<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesMatrialType;
use App\Models\Admin;
use App\Http\Requests\SalesMatrialTypeRequest;

use Exception;


class SalesMatrialTypeController extends Controller
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
            $data = SalesMatrialType::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'asc')->paginate(PAGINATION_COUNT);

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

            return view('admin.sales_matrial_type.index', ['data' => $data]);
        }
        catch(Exception $e) {

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
        return view('admin.sales_matrial_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalesMatrialTypeRequest $request)
    {
        //
        try {
            $inserted['name'] = $request->name;
            $inserted['active'] = $request->active;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            SalesMatrialType::create($inserted);

            return redirect()->route('admin.sales_matrial_type.index')->with('success', 'تم اضافة الفئة بنجاح');
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
        $data = SalesMatrialType::find($id);
        if (empty($data)) {
            return redirect()->route('admin.sales_matrial_type.index')->with('error', 'لا يوجد بيانات كهذه');
        }
        return view('admin.sales_matrial_type.edit', ['data' => $data]);
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
            'name' => 'required|unique:sales_matrial_type,name,'.$id,
            'active' =>'required'
        ]);

        try {
            $updated['name'] = $request->name;
            $updated['active'] = $request->active;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['com_code'] = auth()->user()->com_code;

            SalesMatrialType::where('id', $id)->update($updated);

            return redirect()->route('admin.sales_matrial_type.index')->with('success', 'تم تعديل الفئة بنجاح');

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

            $data_check = SalesMatrialType::where(['id' => $id, 'com_code' => auth()->user()->id])->first();


            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            $flag = SalesMatrialType::where(['id' => $id, 'com_code' => auth()->user()->id])->delete();

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
            $value = $request->search_value;
            $data = SalesMatrialType::where('name', 'like', "%$value%")->paginate(PAGINATION_COUNT);
            return view('admin.sales_matrial_type.ajax_search', ['data' => $data]);
        }
    }
}
