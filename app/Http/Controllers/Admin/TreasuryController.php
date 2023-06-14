<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Treasury;
use App\Models\AdminPanelSetting;
use App\Http\Requests\CreateTreasuriesRequest;
use App\Http\Requests\UpdateTreasuriesRequest;
use App\Models\TreasuryDelivery;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

class TreasuryController extends Controller
{
    /**
     * Display a listing of thea resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Treasury::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'asc')->paginate(PAGINATION_COUNT);

        if (!empty($data)) {
            foreach ($data as $d) {
                if ($d['added_by'] != null) {
                    $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
                }

                if ($d['updated_by'] != null) {
                    $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
                }

                if ($d['com_code'] != null) {
                    $d['com_code_name'] = AdminPanelSetting::where('id', $d['com_code'])->value('system_name');
                }
            }
        }

        return view('admin.treasuries.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.treasuries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTreasuriesRequest $request)
    {
        //
        $inserted['name'] = $request->name;
        $inserted['active'] = $request->active;
        $inserted['master'] = $request->master;
        $inserted['last_exchange_arrive'] = $request->last_exchange_arrive;
        $inserted['last_collection_arrive'] = $request->last_collection_arrive;
        $inserted['added_by'] = auth()->user()->id;
        $inserted['created_at'] = date('Y-m-d H:i:s');
        $inserted['com_code'] = auth()->user()->com_code;

        Treasury::create($inserted);

        return redirect()->route('admin.treasuries.index')->with('success', 'تم اضافة الخزنة بنجاح');
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
            $data = Treasury::find($id);

            if (empty($data)) {
                return redirect()->back()->with('error', 'لا يوجد خزينة كهذه');
            }

            // foreach ($data as $d) {
            //     $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
            //     $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
            // }


            $treasuries = TreasuryDelivery::where('treasuries_id', $id)->get();
            if (!empty($treasuries)) {
                foreach($treasuries as $tr) {
                    $tr['treasury_id'] = Treasury::where('id', $tr['treasuries_receive_from_id'])->value('id');
                    $tr['treasury_name'] = Treasury::where('id', $tr['treasuries_receive_from_id'])->value('name');
                    $tr['added_by_admin'] = Admin::where('id', $tr['added_by'])->value('name');
                }
            }

            return view('admin.treasuries.details', ['data' => $data, 'treasuries' => $treasuries]);
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
        $data = Treasury::find($id);
        if (empty($data)) {
            return redirect()->route('admin.treasuries.index')->with('error', 'لا يوجد بيانات كهذه');
        }
        return view('admin.treasuries.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTreasuriesRequest $request, $id)
    {
        //
        $updated['name'] = $request->name;
        $updated['active'] = $request->active;
        $updated['master'] = $request->master;
        $updated['last_exchange_arrive'] = $request->last_exchange_arrive;
        $updated['last_collection_arrive'] = $request->last_collection_arrive;
        $updated['updated_by'] = auth()->user()->id;
        $updated['updated_at'] = date('Y-m-d H:i:s');
        $updated['com_code'] = auth()->user()->com_code;

        Treasury::where('id', $id)->update($updated);

        return redirect()->route('admin.treasuries.index')->with('success', 'تم تعديل الخزنة بنجاح');
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

    public function ajax_search(Request $request) {
        if ($request->ajax()) {
            $value = $request->search_value;
            $data = Treasury::where('name', 'like', "%$value%")->paginate(PAGINATION_COUNT);
            return view('admin.treasuries.ajax_search', ['data' => $data]);
        }
    }

    public function create_delivery($id)
    {
        # code...
        try {
            $data_check = Treasury::find($id);
            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد خزينة كهذه');
            }

            $data = Treasury::all();

            return view('admin.treasuries.create_delivery', ['data' => $data, 'master_id' => $id]);
        }
        catch(Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ'.$e->getMessage());
        }
    }

    public function store_delivery(Request $request, $id)
    {
        # code...
        $request->validate([
            'receive_from_id' => 'required',
        ]);

        try {

            $data_check = TreasuryDelivery::where('treasuries_id', '=', $id)->where('treasuries_receive_from_id', '=', $request->receive_from_id)->get();


            if (!empty($data_check[0])) {
                return redirect()->back()->with('error', 'اسم الخزينة موجود مسبقا');
            }

            $inserted['treasuries_id'] = $id;
            $inserted['treasuries_receive_from_id'] = $request->receive_from_id;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            TreasuryDelivery::create($inserted);


            return redirect()->back()->with('success', 'تم الاضافة بنجاح');

        }
        catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_delivery($id, $id_from)
    {
        # code...
        try {

            $data_check = TreasuryDelivery::where('treasuries_id', '=', $id_from)->where('treasuries_receive_from_id', '=', $id)->first();


            if (empty($data_check)) {
                return redirect()->back()->with('error', 'لا يوجد بيانات كهذه');
            }


            $flag = TreasuryDelivery::where(['treasuries_id' => $id_from, 'treasuries_receive_from_id' => $id])->delete();

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
}
