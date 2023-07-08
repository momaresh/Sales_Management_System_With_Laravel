<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Store;
use App\Http\Requests\CreateStoreRequest;
use Exception;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (check_control_menu_role('المخازن', 'المخازن' , 'عرض') == true) {
            try {
                $data = Store::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);

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

                return view('admin.stores.index', ['data' => $data]);
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
        if (check_control_menu_role('المخازن', 'المخازن' , 'اضافة') == true) {
            return view('admin.stores.create');
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
    public function store(CreateStoreRequest $request)
    {
        //
        if (check_control_menu_role('المخازن', 'المخازن' , 'اضافة') == true) {
            try {
                $com_code = auth()->user()->com_code;

                $check = Store::where(['name' => $request->name , 'com_code' => $com_code])->count();
                if ($check > 0) {
                    return redirect()->back()->with('error', 'اسم المخزن مسجل مسبقاً')->withInput();
                }

                $store_code = Store::where(['com_code' => $com_code])->max('store_code');
                if (empty($store_code)) {
                    $inserted['store_code'] = 1;
                }
                else {
                    $inserted['store_code'] = $store_code + 1;
                }

                $inserted['name'] = $request->name;
                $inserted['active'] = $request->active;
                $inserted['phone'] = $request->phone;
                $inserted['address'] = $request->address;
                $inserted['added_by'] = auth()->user()->id;
                $inserted['created_at'] = date('Y-m-d H:i:s');
                $inserted['com_code'] = $com_code;

                Store::create($inserted);

                return redirect()->route('admin.stores.index')->with('success', 'تم اضافة المخزن بنجاح');
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
        if (check_control_menu_role('المخازن', 'المخازن' , 'تعديل') == true) {
            $data = Store::find($id);
            if (empty($data)) {
                return redirect()->route('admin.stores.index')->with('error', 'لا يوجد بيانات كهذه');
            }
            return view('admin.stores.edit', ['data' => $data]);
        }
        else {
            return redirect()->back();
        }

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
            'name' => 'required',
            'active' =>'required',
            'phone' =>'required',
            'address' =>'required'
        ]);

        if (check_control_menu_role('المخازن', 'المخازن' , 'تعديل') == true) {
            try {
                $com_code = auth()->user()->com_code;
                $check = Store::where(['name' => $request->name, 'com_code' => $com_code])->where('id', '!=', $id)->count();
                if ($check > 0) {
                    return redirect()->back()->with('error', 'اسم المخزن مسجل مسبقاً')->withInput();
                }

                $updated['name'] = $request->name;
                $updated['phone'] = $request->phone;
                $updated['address'] = $request->address;
                $updated['active'] = $request->active;
                $updated['updated_by'] = auth()->user()->id;
                $updated['updated_at'] = date('Y-m-d H:i:s');
                $updated['com_code'] = $com_code;

                Store::where('id', $id)->update($updated);

                return redirect()->route('admin.stores.index')->with('success', 'تم تعديل المخزن بنجاح');

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
        if (check_control_menu_role('المخازن', 'المخازن' , 'حذف') == true) {
            try {

                $data_check = Store::where(['id' => $id, 'com_code' => auth()->user()->id])->first();


                if (empty($data_check)) {
                    return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
                }


                $flag = Store::where(['id' => $id, 'com_code' => auth()->user()->id])->delete();

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
            $value = $request->search_value;
            $data = Store::where('name', 'like', "%$value%")->where(['com_code' => auth()->user()->com_code])->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
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
            return view('admin.stores.ajax_search', ['data' => $data]);
        }
    }
}
