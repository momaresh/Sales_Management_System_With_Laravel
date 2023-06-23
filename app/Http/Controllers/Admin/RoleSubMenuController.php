<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\InvUnit;
use App\Http\Requests\CreateUnitRequest;
use App\Models\RoleSubMenu;
use App\Models\InvoiceOrderDetail;
use App\Models\Role;
use App\Models\RoleMainMenu;
use App\Models\RoleSubMenuControl;
use Exception;

class RoleSubMenuController extends Controller
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
            $data = RoleSubMenu::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'desc')->paginate(PAGINATION_COUNT);

            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['main_menu_name'] = RoleMainMenu::where('id', $d['roles_main_menu_id'])->value('name');
                    if ($d['added_by'] != null) {
                        $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
                    }

                    if ($d['updated_by'] != null) {
                        $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
                    }
                }
            }

            $main_menus = RoleMainMenu::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'desc')->paginate(PAGINATION_COUNT);

            return view('admin.roles_sub_menu.index', ['data' => $data, 'main_menus' => $main_menus]);
        } catch (Exception $e) {
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
        $main_menus = RoleMainMenu::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'desc')->paginate(PAGINATION_COUNT);

        return view('admin.roles_sub_menu.create', ['main_menus' => $main_menus]);
    }

    public function store(Request $request)
    {
        //
        $request->validate(
            [
                'name' => 'required',
                'main_menu_id' => 'required',
                'active' => 'required'
            ],
            [
                'name.required' => 'اسم القائمة الفرعية مطلوب',
                'main_menu_id.required' => 'اسم القائمة الرئيسية مطلوب',
                'active.required' => 'حالة القائمة الفرعية مطلوب'
            ]
        );
        try {
            $check = RoleSubMenu::where(['name' => $request->name, 'roles_main_menu_id' => $request->main_menu_id])->value('id');
            if (!empty($check)) {
                return redirect()->back()->with('error', 'اسم القائمة الفرعية مسجل من قبل')->withInput();
            }
            $inserted['name'] = $request->name;
            $inserted['roles_main_menu_id'] = $request->main_menu_id;
            $inserted['active'] = $request->active;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            RoleSubMenu::create($inserted);

            return redirect()->route('admin.roles_sub_menu.index')->with('success', 'تم اضافة القائمة بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        //
        $data = RoleSubMenu::find($id);
        if (empty($data)) {
            return redirect()->route('admin.roles_sub_menu.index')->with('error', 'لا يوجد بيانات كهذه');
        }

        $main_menus = RoleMainMenu::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'desc')->paginate(PAGINATION_COUNT);

        return view('admin.roles_sub_menu.edit', ['data' => $data, 'main_menus' => $main_menus]);
    }

    public function update(Request $request, $id)
    {
        //
        $request->validate(
            [
                'name' => 'required',
                'main_menu_id' => 'required',
                'active' => 'required',
            ],
            [
                'name.required' => 'اسم القائمة الفرعية مطلوب',
                'main_menu_id.required' => 'اسم القائمة الرئيسية مطلوب',
                'active.required' => 'حالة القائمة الفرعية مطلوب'
            ]
        );

        try {
            $check = RoleSubMenu::where(['name' => $request->name, 'roles_main_menu_id' => $request->main_menu_id])->where('id', '!=', $request->id)->value('id');
            if (!empty($check)) {
                return redirect()->back()->with('error', 'اسم القائمة الفرعية مسجل من قبل')->withInput();
            }

            $updated['name'] = $request->name;
            $inserted['roles_main_menu_id'] = $request->main_menu_id;
            $updated['active'] = $request->active;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['com_code'] = auth()->user()->com_code;

            RoleSubMenu::where('id', $id)->update($updated);

            return redirect()->route('admin.roles_sub_menu.index')->with('success', 'تم تعديل القائمة بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        //
        try {

            $data_check = RoleSubMenu::where(['id' => $id, 'com_code' => auth()->user()->id])->first();


            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            $flag = RoleSubMenu::where(['id' => $id, 'com_code' => auth()->user()->id])->delete();

            if ($flag) {
                return redirect()->back()->with('success', 'تم الحذف بنجاح');
            } else {
                return redirect()->back()->with('error', 'غير قادر على الحذف ');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }


    public function ajax_search(Request $request)
    {
        if ($request->ajax()) {


            $search_by_name = $request->search_by_name;
            $search_by_main_menu = $request->search_by_main_menu;



            if ($search_by_name == '') {
                $filed1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            } else {
                $filed1 = 'name';
                $operator1 = 'LIKE';
                $value1 = $search_by_name;
            }

            if ($search_by_main_menu == 'all') {
                $filed2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            } else {
                $filed2 = 'roles_main_menu_id';
                $operator2 = '=';
                $value2 = $search_by_main_menu;
            }

            $data = RoleSubMenu::where("$filed1", "$operator1", "%$value1%")->where("$filed2", "$operator2", "$value2")->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['main_menu_name'] = RoleMainMenu::where('id', $d['roles_main_menu_id'])->value('name');
                    if ($d['added_by'] != null) {
                        $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
                    }

                    if ($d['updated_by'] != null) {
                        $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
                    }
                }
            }


            return view('admin.roles_sub_menu.ajax_search', ['data' => $data]);
        }
    }

    public function details($id)
    {
        //
        try {
            $data = RoleSubMenu::find($id);

            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يوجد قائمة كهذه');
            }

            $data['main_menu_name'] = RoleMainMenu::where('id', $data['roles_main_menu_id'])->value('name');
            if ($data['added_by'] != null) {
                $data['added_by_name'] = Admin::where('id', $data['added_by'])->value('name');
            }

            if ($data['updated_by'] != null) {
                $data['updated_by_name'] = Admin::where('id', $data['updated_by'])->value('name');
            }

            $controls = RoleSubMenuControl::where('roles_sub_menu_id', $id)->get();
            if (!empty($controls)) {
                foreach ($controls as $con) {
                    $con['sub_menu_name'] = RoleSubMenu::where('id', $con['roles_sub_menu_id'])->value('name');
                    $con['added_by_admin'] = Admin::where('id', $con['added_by'])->value('name');
                    $con['updated_by_admin'] = Admin::where('id', $con['updated_by'])->value('name');
                }
            }

            return view('admin.roles_sub_menu.details', ['data' => $data, 'controls' => $controls]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create_control($id)
    {
        //
        return view('admin.roles_sub_menu.create_control', ['id' => $id]);
    }

    public function store_control(Request $request, $id)
    {
        //
        $request->validate(
            [
                'name' => 'required',
                'active' => 'required'
            ],
            [
                'name.required' => 'اسم التحكم الفرعية مطلوب',
                'active.required' => 'حالة التحكم الفرعية مطلوب'
            ]
        );
        try {
            $check = RoleSubMenuControl::where(['name' => $request->name, 'roles_sub_menu_id' => $id])->value('id');
            if (!empty($check)) {
                return redirect()->back()->with('error', 'اسم التحكم مسجل من قبل')->withInput();
            }
            $inserted['name'] = $request->name;
            $inserted['roles_sub_menu_id'] = $id;
            $inserted['active'] = $request->active;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            RoleSubMenuControl::create($inserted);

            return redirect()->route('admin.roles_sub_menu.details', $id)->with('success', 'تم اضافة التحكم بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit_control($id)
    {
        //
        $data = RoleSubMenuControl::find($id);
        if (empty($data)) {
            return redirect()->route('admin.roles_sub_menu.index')->with('error', 'لا يوجد بيانات كهذه');
        }

        return view('admin.roles_sub_menu.edit_control', ['data' => $data]);
    }

    public function update_control(Request $request, $id)
    {
        //
        $request->validate(
            [
                'name' => 'required',
                'active' => 'required',
            ],
            [
                'name.required' => 'اسم التحكم مطلوب',
                'active.required' => 'حالة التحكم مطلوب'
            ]
        );

        try {
            $check = RoleSubMenu::where(['name' => $request->name, 'roles_main_menu_id' => $id])->where('id', '!=', $request->id)->value('id');
            if (!empty($check)) {
                return redirect()->back()->with('error', 'اسم التحكم مسجل من قبل')->withInput();
            }

            $updated['name'] = $request->name;
            $updated['active'] = $request->active;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['com_code'] = auth()->user()->com_code;

            $flag = RoleSubMenuControl::where(['id' => $request->id, 'roles_sub_menu_id' => $id])->update($updated);
            if ($flag) {
                return redirect()->route('admin.roles_sub_menu.details', $id)->with('success', 'تم تعديل التحكم بنجاح');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete_control($id)
    {
        //
        try {

            $data_check = RoleSubMenuControl::where(['id' => $id, 'com_code' => auth()->user()->id])->first();


            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            $flag = RoleSubMenuControl::where(['id' => $id, 'com_code' => auth()->user()->id])->delete();

            if ($flag) {
                return redirect()->back()->with('success', 'تم الحذف بنجاح');
            } else {
                return redirect()->back()->with('error', 'غير قادر على الحذف ');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

}
