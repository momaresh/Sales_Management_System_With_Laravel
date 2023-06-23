<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\InvUnit;
use App\Http\Requests\CreateUnitRequest;
use App\Models\InvItemCard;
use App\Models\InvoiceOrderDetail;
use App\Models\Role;
use App\Models\RoleMainMenu;
use Exception;

class RoleMainMenuController extends Controller
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
            $data = RoleMainMenu::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'desc')->paginate(PAGINATION_COUNT);

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

            return view('admin.roles_main_menu.index', ['data' => $data]);
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
        return view('admin.roles_main_menu.create');
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
            'name' => 'required|unique:roles_main_menu,name',
            'active' => 'required'
        ],
        [
            'name.required' => 'اسم القائمة الرئيسية مطلوب',
            'name.unique' => 'اسم القائمة الرئيسية مسجل من قبل',
            'active.required' => 'حالة القائمة الرئيسية مطلوب'
        ]
        );
        try {
            $inserted['name'] = $request->name;
            $inserted['active'] = $request->active;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            RoleMainMenu::create($inserted);

            return redirect()->route('admin.roles_main_menu.index')->with('success', 'تم اضافة الوحدة بنجاح');
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
        $data = RoleMainMenu::find($id);
        if (empty($data)) {
            return redirect()->route('admin.roles_main_menu.index')->with('error', 'لا يوجد بيانات كهذه');
        }

        return view('admin.roles_main_menu.edit', ['data' => $data]);
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
            'name' => 'required|unique:roles_main_menu,name,'.$id,
            'active' =>'required',
        ],
        [
            'name.required' => 'اسم القائمة الرئيسية مطلوب',
            'name.unique' => 'اسم القائمة الرئيسية مسجل من قبل',
            'active.required' => 'حالة القائمة الرئيسية مطلوب'
        ]
        );

        try {

            $updated['name'] = $request->name;
            $updated['active'] = $request->active;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['com_code'] = auth()->user()->com_code;

            RoleMainMenu::where('id', $id)->update($updated);

            return redirect()->route('admin.roles_main_menu.index')->with('success', 'تم تعديل الوحدة بنجاح');

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
    public function destroy($id)
    {
        //
        try {

            $data_check = RoleMainMenu::where(['id' => $id, 'com_code' => auth()->user()->id])->first();


            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            $flag = RoleMainMenu::where(['id' => $id, 'com_code' => auth()->user()->id])->delete();

            if ($flag) {
                return redirect()->back()->with('success', 'تم الحذف بنجاح');
            }
            else {
                return redirect()->back()->with('error', 'غير قادر على الحذف ');
            }

        }
        catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
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

            $data = RoleMainMenu::where("$filed1", "$operator1", "%$value1%")->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
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



            return view('admin.roles_main_menu.ajax_search', ['data' => $data]);

        }
    }
}
