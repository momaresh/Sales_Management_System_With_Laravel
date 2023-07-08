<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Treasury;
use App\Models\AdminTreasury;
use App\Models\Role;
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

                    $d['roles_name'] = Role::where('id', $d['roles_id'])->value('name');
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
        $roles = Role::where(['com_code' => auth()->user()->com_code])->get();

        return view('admin.admins.create', ['roles' => $roles]);
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
        $request->validate(
            [
                'name' => 'required',
                'user_name' => 'required|unique:admin,user_name',
                'email' => 'required|unique:admin,email',
                'password' => 'required|min:4',
                'active' => 'required',
                'roles_id' => 'required'
            ],
            [
                'name.required' => 'الاسم مطلوب',
                'user_name.required' => 'اسم المستخدم مطلوب',
                'user_name.unique' => 'اسم المستخدم مسجل مسبقا',
                'email.required' => 'الايميل مطلوب',
                'email.unique' => 'الايميل مسجل مسبقا',
                'password.required' => 'كلمة السر مطلوبة',
                'password.min' => 'كلمة السر لا تقل عن اربع خانات',
                'active.required' => 'حالة التفعيل مطلوبة',
                'roles_id.required' => 'نوع الصلاحية مطلوب'
            ]

        );
        try {
            $inserted['name'] = $request->name;
            $inserted['user_name'] = $request->user_name;
            $inserted['email'] = $request->email;
            $inserted['password'] = bcrypt($request->password);
            $inserted['roles_id'] = $request->roles_id;
            $inserted['active'] = $request->active;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            Admin::create($inserted);

            return redirect()->route('admin.admins.index')->with('success', 'تم الاضافة بنجاح');

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
    public function details($id)
    {
        //
        try {
            $data = Admin::find($id);

            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يوجد خزينة كهذه');
            }

            $data['added_by_name'] = Admin::where('id', $data['added_by'])->value('name');
            $data['updated_by_name'] = Admin::where('id', $data['updated_by'])->value('name');

            $data['roles_name'] = Role::where('id', $data['roles_id'])->value('name');


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
        $data = Admin::find($id);
        if (empty($data)) {
            return redirect()->back();
        }
        $roles = Role::where(['com_code' => auth()->user()->com_code])->get();

        return view('admin.admins.edit', ['data' => $data, 'roles' => $roles]);
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
        $request->validate(
            [
                'name' => 'required',
                'user_name' => 'required|unique:admin,user_name,'.$id,
                'email' => 'required|unique:admin,email,'.$id,
                'password' => 'required|min:4',
                'active' => 'required',
                'roles_id' => 'required'
            ],
            [
                'name.required' => 'الاسم مطلوب',
                'user_name.required' => 'اسم المستخدم مطلوب',
                'user_name.unique' => 'اسم المستخدم مسجل مسبقا',
                'email.required' => 'الايميل مطلوب',
                'email.unique' => 'الايميل مسجل مسبقا',
                'password.required' => 'كلمة السر مطلوبة',
                'password.min' => 'كلمة السر لا تقل عن اربع خانات',
                'active.required' => 'حالة التفعيل مطلوبة',
                'roles_id.required' => 'نوع الصلاحية مطلوب'
            ]

        );
        try {
            $updated['name'] = $request->name;
            $updated['user_name'] = $request->user_name;
            $updated['email'] = $request->email;
            $updated['password'] = bcrypt($request->password);
            $updated['roles_id'] = $request->roles_id;
            $updated['active'] = $request->active;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');

            Admin::where('id', $id)->update($updated);

            return redirect()->route('admin.admins.index')->with('success', 'تم التعديل بنجاح');

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
    }

    public function create_treasuries($id)
    {
        # code...
        $check = Admin::find($id);
        if (empty($check)) {
            return redirect()->back()->with('error', 'لا يوجد مستخدم كهذا');
        }

        $data = Treasury::where(['com_code' => auth()->user()->com_code])->get();
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
