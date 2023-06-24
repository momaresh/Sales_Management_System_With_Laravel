<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\InvUnit;
use App\Http\Requests\CreateUnitRequest;
use App\Models\InvItemCard;
use App\Models\InvoiceOrderDetail;
use App\Models\PermissionRoleWithMainMenu;
use App\Models\PermissionRoleWithSubMenu;
use App\Models\PermissionRoleWithSubMenuControl;
use App\Models\Role;
use App\Models\RoleMainMenu;
use App\Models\RoleSubMenu;
use App\Models\RoleSubMenuControl;
use Exception;

class RoleController extends Controller
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
            $data = Role::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'desc')->paginate(PAGINATION_COUNT);

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

            return view('admin.roles.index', ['data' => $data]);
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
        return view('admin.roles.create');
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
            'name' => 'required|unique:roles,name',
            'active' => 'required'
        ],
        [
            'name.required' => 'اسم الصلاحية مطلوب',
            'name.unique' => 'اسم الصلاحية مسجل من قبل',
            'active.required' => 'حالة الصلاحية مطلوب'
        ]
        );
        try {
            $inserted['name'] = $request->name;
            $inserted['active'] = $request->active;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            Role::create($inserted);

            return redirect()->route('admin.roles.index')->with('success', 'تم اضافة الوحدة بنجاح');
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
        $data = Role::find($id);
        if (empty($data)) {
            return redirect()->route('admin.roles.index')->with('error', 'لا يوجد بيانات كهذه');
        }

        return view('admin.roles.edit', ['data' => $data]);
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
            'name' => 'required|unique:roles,name,'.$id,
            'active' =>'required',
        ],
        [
            'name.required' => 'اسم الصلاحية مطلوب',
            'name.unique' => 'اسم الصلاحية مسجل من قبل',
            'active.required' => 'حالة الصلاحية مطلوب'
        ]
        );

        try {

            $updated['name'] = $request->name;
            $updated['active'] = $request->active;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['com_code'] = auth()->user()->com_code;

            Role::where('id', $id)->update($updated);

            return redirect()->route('admin.roles.index')->with('success', 'تم تعديل الوحدة بنجاح');

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

            $data_check = Role::where(['id' => $id, 'com_code' => auth()->user()->id])->first();

            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            Role::where(['id' => $id, 'com_code' => auth()->user()->id])->delete();

            return redirect()->back()->with('success', 'تم الحذف بنجاح');
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

            $data = Role::where("$filed1", "$operator1", "%$value1%")->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
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
            return view('admin.roles.ajax_search', ['data' => $data]);

        }
    }


    public function details($id)
    {
        //
        try {
            $com_code = auth()->user()->com_code;
            $data = Role::find($id);

            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يوجد صلاحية كهذه');
            }

            if ($data['added_by'] != null) {
                $data['added_by_name'] = Admin::where('id', $data['added_by'])->value('name');
            }

            if ($data['updated_by'] != null) {
                $data['updated_by_name'] = Admin::where('id', $data['updated_by'])->value('name');
            }

            $permission_main_menus = PermissionRoleWithMainMenu::where(['roles_id' => $id, 'com_code' => $com_code])->get();
            if (!empty($permission_main_menus)) {
                foreach ($permission_main_menus as $con) {
                    $con['main_menu_name'] = RoleMainMenu::where('id', $con['roles_main_menu_id'])->value('name');
                    $con['added_by_name'] = Admin::where('id', $con['added_by'])->value('name');
                }
            }

            $main_menus = RoleMainMenu::get();

            return view('admin.roles.details', ['data' => $data, 'permission_main_menus' => $permission_main_menus, 'main_menus' => $main_menus]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store_permission_main_menu(Request $request)
    {
        //
        try {
            if (empty($request->main_menu_id)) {
                return redirect()->back();
            }

            foreach ($request->main_menu_id as $main_menu) {
                $check_exists = PermissionRoleWithMainMenu::where(['roles_id' => $request->roles_id, 'roles_main_menu_id' => $main_menu])->count();
                if ($check_exists == 0) {
                    $inserted['roles_id'] = $request->roles_id;
                    $inserted['roles_main_menu_id'] = $main_menu;
                    $inserted['added_by'] = auth()->user()->id;
                    $inserted['created_at'] = date('Y-m-d H:i:s');
                    $inserted['com_code'] = auth()->user()->com_code;
                    PermissionRoleWithMainMenu::create($inserted);
                }
            }

            return redirect()->route('admin.roles.details', $request->roles_id)->with('success', 'تم اضافة القوائم بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete_permission_main_menu($roles_id, $main_id)
    {
        //
        try {

            $data_check = PermissionRoleWithMainMenu::where(['roles_id' => $roles_id, 'roles_main_menu_id' => $main_id, 'com_code' => auth()->user()->id])->first();

            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            PermissionRoleWithMainMenu::where(['roles_id' => $roles_id, 'roles_main_menu_id' => $main_id, 'com_code' => auth()->user()->id])->delete();

            return redirect()->back()->with('success', 'تم الحذف بنجاح');
        }
        catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function main_menu_details($role_id, $main_id)
    {
        //
        try {
            $com_code = auth()->user()->com_code;
            $data = PermissionRoleWithMainMenu::where(['roles_id' => $role_id, 'roles_main_menu_id' => $main_id, 'com_code' => $com_code])->get()->first();

            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يوجد صلاحية كهذه');
            }

            if ($data['added_by'] != null) {
                $data['added_by_name'] = Admin::where('id', $data['added_by'])->value('name');
            }

            $data['roles_name'] = Role::where('id', $data['roles_id'])->value('name');
            $data['roles_main_menu_name'] = RoleMainMenu::where('id', $data['roles_main_menu_id'])->value('name');


            $permission_sub_menus = PermissionRoleWithSubMenu::where(['roles_id' => $role_id, 'roles_main_menu_id' => $main_id, 'com_code' => $com_code])->get();
            if (!empty($permission_sub_menus)) {
                foreach ($permission_sub_menus as $con) {
                    $con['controls'] = PermissionRoleWithSubMenuControl::where(['roles_id' => $role_id, 'roles_main_menu_id' => $main_id, 'roles_sub_menu_id' => $con['roles_sub_menu_id']])->get();
                    if (!empty($con['controls'])) {
                        foreach ($con['controls'] as $c) {
                            $c['name'] = RoleSubMenuControl::where('id', $c['roles_sub_menu_control_id'])->value('name');
                        }
                    }
                    $con['sub_menu_name'] = RoleSubMenu::where('id', $con['roles_sub_menu_id'])->value('name');
                    $con['added_by_name'] = Admin::where('id', $con['added_by'])->value('name');
                }
            }


            $sub_menus = RoleSubMenu::where([ 'roles_main_menu_id' => $main_id])->get();

            return view('admin.roles.main_menu_details', ['data' => $data, 'permission_sub_menus' => $permission_sub_menus, 'sub_menus' => $sub_menus]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store_permission_sub_menu(Request $request)
    {
        //
        try {
            if (empty($request->sub_menu_id)) {
                return redirect()->back();
            }

            foreach ($request->sub_menu_id as $sub_menu) {
                $check_exists = PermissionRoleWithSubMenu::where(['roles_id' => $request->roles_id, 'roles_main_menu_id' => $request->roles_main_menu_id, 'roles_sub_menu_id' => $sub_menu])->count();
                if ($check_exists == 0) {
                    $inserted['roles_id'] = $request->roles_id;
                    $inserted['roles_main_menu_id'] = $request->roles_main_menu_id;
                    $inserted['roles_sub_menu_id'] = $sub_menu;
                    $inserted['added_by'] = auth()->user()->id;
                    $inserted['created_at'] = date('Y-m-d H:i:s');
                    $inserted['com_code'] = auth()->user()->com_code;
                    PermissionRoleWithSubMenu::create($inserted);
                }
            }

            return redirect()->route('admin.roles.main_menu_details', [$request->roles_id, $request->roles_main_menu_id])->with('success', 'تم اضافة القوائم بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }


    public function delete_permission_sub_menu($roles_id, $main_id, $sub_id)
    {
        //
        try {

            $data_check = PermissionRoleWithSubMenu::where(['roles_id' => $roles_id, 'roles_main_menu_id' => $main_id, 'roles_sub_menu_id' => $sub_id, 'com_code' => auth()->user()->id])->first();

            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            PermissionRoleWithSubMenu::where(['roles_id' => $roles_id, 'roles_main_menu_id' => $main_id, 'roles_sub_menu_id' => $sub_id, 'com_code' => auth()->user()->id])->delete();

            return redirect()->back()->with('success', 'تم الحذف بنجاح');
        }
        catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function load_control_modal(Request $request)
    {
        //
        try {
            $com_code = auth()->user()->com_code;
            $roles_id = $request->roles_id;
            $sub_id = $request->sub_id;
            $main_id = $request->main_id;

            $control_menus = RoleSubMenuControl::where(['roles_sub_menu_id' => $sub_id])->get();

            return view('admin.roles.load_control_modal', ['control_menus' => $control_menus, 'roles_id' => $roles_id, 'roles_main_menu_id' => $main_id, 'roles_sub_menu_id' => $sub_id]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store_permission_sub_menu_control(Request $request)
    {
        //
        try {
            if (empty($request->control_menu_id)) {
                return redirect()->back();
            }

            foreach ($request->control_menu_id as $control_menu) {
                $check_exists = PermissionRoleWithSubMenuControl::where(['roles_id' => $request->roles_id, 'roles_main_menu_id' => $request->roles_main_menu_id, 'roles_sub_menu_id' => $request->roles_sub_menu_id, 'roles_sub_menu_control_id' => $control_menu])->count();
                if ($check_exists == 0) {

                    $inserted['roles_id'] = $request->roles_id;
                    $inserted['roles_main_menu_id'] = $request->roles_main_menu_id;
                    $inserted['roles_sub_menu_id'] = $request->roles_sub_menu_id;
                    $inserted['roles_sub_menu_control_id'] = $control_menu;
                    $inserted['added_by'] = auth()->user()->id;
                    $inserted['created_at'] = date('Y-m-d H:i:s');
                    $inserted['com_code'] = auth()->user()->com_code;
                    PermissionRoleWithSubMenuControl::create($inserted);
                }
            }

            return redirect()->route('admin.roles.main_menu_details', [$request->roles_id, $request->roles_main_menu_id])->with('success', 'تم الاضافة  بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete_permission_sub_menu_control($roles_id, $main_id, $sub_id, $control_id)
    {
        //
        try {

            $data_check = PermissionRoleWithSubMenuControl::where(['roles_id' => $roles_id, 'roles_main_menu_id' => $main_id, 'roles_sub_menu_id' => $sub_id, 'roles_sub_menu_control_id' => $control_id, 'com_code' => auth()->user()->id])->first();

            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            PermissionRoleWithSubMenuControl::where(['roles_id' => $roles_id, 'roles_main_menu_id' => $main_id, 'roles_sub_menu_id' => $sub_id, 'roles_sub_menu_control_id' => $control_id, 'com_code' => auth()->user()->id])->delete();

            return redirect()->back()->with('success', 'تم الحذف بنجاح');
        }
        catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }


}
