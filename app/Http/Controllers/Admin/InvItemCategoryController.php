<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvItemCategory;
use App\Models\Admin;
use Exception;
use App\Http\Requests\InvItemCategoryRequest;

class InvItemCategoryController extends Controller
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
            $data = InvItemCategory::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);

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

            return view('admin.inv_item_categories.index', ['data' => $data]);
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
        return view('admin.inv_item_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvItemCategoryRequest $request)
    {
        //
        try {
            $inserted['name'] = $request->name;
            $inserted['active'] = $request->active;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            InvItemCategory::create($inserted);

            return redirect()->route('inv_item_categories.index')->with('success', 'تم اضافة الصنف بنجاح');
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
        $data = InvItemCategory::find($id);
        if (empty($data)) {
            return redirect()->route('inv_item_categories.index')->with('error', 'لا يوجد بيانات كهذه');
        }
        return view('admin.inv_item_categories.edit', ['data' => $data]);
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
            'name' => 'required|unique:inv_item_categories,name,'.$id,
            'active' =>'required'
        ]);

        try {
            $updated['name'] = $request->name;
            $updated['active'] = $request->active;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['com_code'] = auth()->user()->com_code;

            InvItemCategory::where('id', $id)->update($updated);

            return redirect()->route('inv_item_categories.index')->with('success', 'تم تعديل الصنف بنجاح');

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
     *
     */


    public function delete($id)
    {
        # code...
        try {

            $data_check = InvItemCategory::where(['id' => $id, 'com_code' => auth()->user()->id])->first();


            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            $flag = InvItemCategory::where(['id' => $id, 'com_code' => auth()->user()->id])->delete();

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


    public function destroy($id)
    {
        //

    }


    public function ajax_search(Request $request) {
        if ($request->ajax()) {
            $value = $request->search_value;
            $data = InvItemCategory::where('name', 'like', "%$value%")->paginate(PAGINATION_COUNT);
            return view('admin.inv_item_categories.ajax_search', ['data' => $data]);
        }
    }
}
