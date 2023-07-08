<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Treasury;
use App\Models\AdminPanelSetting;
use App\Http\Requests\CreateTreasuriesRequest;
use App\Http\Requests\UpdateTreasuriesRequest;
use App\Models\Account;
use App\Models\TreasuryDelivery;
use Exception;
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
        if (check_control_menu_role('الحسابات', 'الخزن' , 'عرض') == true) {
            $data = Treasury::where('com_code', auth()->user()->com_code)->select('*')->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
            $com_code = auth()->user()->com_code;

            if (!empty($data)) {
                foreach ($data as $d) {
                    if ($d['added_by'] != null) {
                        $d['added_by_name'] = Admin::where('id', $d['added_by'])->value('name');
                    }

                    if ($d['updated_by'] != null) {
                        $d['updated_by_name'] = Admin::where('id', $d['updated_by'])->value('name');
                    }

                    if (!empty($d['account_number'])) {
                        $d['current_balance'] = Account::where(['account_number' => $d['account_number'], 'com_code' => $com_code, 'active' => 1])->value('current_balance');
                    }
                }
            }
            return view('admin.treasuries.index', ['data' => $data]);
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
        if (check_control_menu_role('الحسابات', 'الخزن' , 'اضافة') == true) {
            return view('admin.treasuries.create');
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
    public function store(CreateTreasuriesRequest $request)
    {
        //
        if (check_control_menu_role('الحسابات', 'الخزن' , 'اضافة') == true) {
            if (!empty(Account::first())) {
                $account_number = Account::max('account_number');
            }
            else {
                $account_number = 12345;
            }
            $account_insert['account_number'] = $account_number + 1;
            $account_insert['account_type'] = 14;
            $account_insert['is_parent'] = 0;
            $account_insert['parent_account_number'] = AdminPanelSetting::where('com_code', auth()->user()->com_code)->value('treasury_parent_account');
            $account_insert['start_balance_status'] = $request->start_balance_status;
            $account_insert['start_balance'] = $request->start_balance;
            $account_insert['active'] = 1;
            $account_insert['notes'] = $request->notes;
            $account_insert['added_by'] = auth()->user()->id;
            $account_insert['created_at'] = date('Y-m-d H:i:s');
            $account_insert['com_code'] = auth()->user()->com_code;
            $account_insert['date'] = date('Y-m-d');
            Account::create($account_insert);

            $com_code = auth()->user()->com_code;

            $check = Treasury::where(['name' => $request->name , 'com_code' => $com_code])->count();
            if ($check > 0) {
                return redirect()->back()->with('error', 'اسم الخزنة مسجل مسبقاً')->withInput();
            }

            $treasury_code = Treasury::where(['com_code' => $com_code])->max('treasury_code');
            if (empty($treasury_code)) {
                $inserted['treasury_code'] = 1;
            }
            else {
                $inserted['treasury_code'] = $treasury_code + 1;
            }

            $inserted['name'] = $request->name;
            $inserted['account_number'] = $account_number + 1;
            $inserted['active'] = $request->active;
            $inserted['master'] = $request->master;
            $inserted['last_exchange_arrive'] = $request->last_exchange_arrive;
            $inserted['last_collection_arrive'] = $request->last_collection_arrive;
            $inserted['last_unpaid_arrive'] = $request->last_unpaid_arrive;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['created_at'] = date('Y-m-d H:i:s');
            $inserted['com_code'] = auth()->user()->com_code;

            Treasury::create($inserted);

            return redirect()->route('admin.treasuries.index')->with('success', 'تم اضافة الخزنة بنجاح');
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
    public function details($id)
    {
        //
        if (check_control_menu_role('الحسابات', 'الخزن' , 'التفاصيل') == true) {
            try {
                $data = Treasury::find($id);
                $com_code = auth()->user()->com_code;

                if (empty($data)) {
                    return redirect()->back()->with('error', 'لا يوجد خزينة كهذه');
                }

                $data['added_by_name'] = Admin::where('id', $data['added_by'])->value('name');
                $data['updated_by_name'] = Admin::where('id', $data['updated_by'])->value('name');
                $data['current_balance'] = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code, 'active' => 1])->value('current_balance');
                $data['start_balance'] = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code, 'active' => 1])->value('start_balance');


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
        else {
            return redirect()->back();
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
        if (check_control_menu_role('الحسابات', 'الخزن' , 'تعديل') == true) {
            $data = Treasury::find($id);
            if (empty($data)) {
                return redirect()->route('admin.treasuries.index')->with('error', 'لا يوجد بيانات كهذه');
            }

            $account = Account::where('account_number', $data['account_number'])->get(['start_balance_status', 'start_balance'])->first();
            $data['start_balance_status'] = $account->start_balance_status;
            $data['start_balance'] = $account->start_balance;

            return view('admin.treasuries.edit', ['data' => $data]);
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
    public function update(UpdateTreasuriesRequest $request, $id)
    {
        //
        if (check_control_menu_role('الحسابات', 'الخزن' , 'تعديل') == true) {
            $check = Treasury::where(['name' => $request->name , 'com_code' => auth()->user()->com_code])->where('id', '!=', $id)->count();
            if ($check > 0) {
                return redirect()->back()->with('error', 'اسم الخزنة مسجل مسبقاً')->withInput();
            }
            $updated['name'] = $request->name;
            $updated['active'] = $request->active;
            $updated['master'] = $request->master;
            $updated['last_exchange_arrive'] = $request->last_exchange_arrive;
            $updated['last_collection_arrive'] = $request->last_collection_arrive;
            $updated['last_unpaid_arrive'] = $request->last_unpaid_arrive;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');
            $updated['com_code'] = auth()->user()->com_code;
            Treasury::where('id', $id)->update($updated);

            $account_updated['start_balance_status'] = $request->start_balance_status;
            if ($account_updated['start_balance_status'] == 1) {
                //credit
                $account_updated['start_balance'] = $request->start_balance * (-1);
            }
            else if ($account_updated['start_balance_status'] == 2) {
                //debit
                $account_updated['start_balance'] = $request->start_balance;

                if ($account_updated['start_balance'] < 0) {
                    $account_updated['start_balance'] = $account_updated['start_balance'] * (-1);
                }
            }
            else {
                $account_updated['start_balance_status'] = 3;
                $account_updated['start_balance'] = 0;
            }
            $account_updated['updated_by'] = auth()->user()->id;
            $account_updated['updated_at'] = date("Y-m-d H:i:s");

            Account::where(['account_number' => $request->account_number, 'com_code' => auth()->user()->com_code])->update($account_updated);

            return redirect()->route('admin.treasuries.index')->with('success', 'تم تعديل الخزنة بنجاح');
        }
        else {
            return redirect()->back();
        }
    }

    public function ajax_search(Request $request) {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $search_by_name = $request->search_by_name;
            $current_status_search = $request->current_status_search;
            $start_status_search = $request->start_status_search;
            $search_by_radio = $request->search_by_radio;

            if ($search_by_radio == 'name') {
                if ($search_by_name == '') {
                    $field1 = "id";
                    $operator1 = ">";
                    $value1 = 0;
                }
                else {
                    $field1 = "name";
                    $operator1 = "LIKE";
                    $value1 = '%'.$search_by_name.'%';
                }
            }
            else if ($search_by_radio == 'acc_number') {
                if ($search_by_name == '') {
                    $field1 = "id";
                    $operator1 = ">";
                    $value1 = 0;
                }
                else {
                    $field1 = "account_number";
                    $operator1 = "LIKE";
                    $value1 = '%'.$search_by_name.'%';
                }
            }

            if ($current_status_search == 'all') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            }
            else if ($current_status_search == '0') {
                $field3 = "current_balance";
                $operator3 = "<";
                $value3 = 0;
            }
            else if ($current_status_search == '1') {
                $field3 = "current_balance";
                $operator3 = "=";
                $value3 = 0;
            }
            else if ($current_status_search == '2') {
                $field3 = "current_balance";
                $operator3 = ">";
                $value3 = 0;
            }

            if ($start_status_search == 'all') {
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
            }
            else if ($start_status_search == '0') {
                $field4 = "start_balance";
                $operator4 = "<";
                $value4 = 0;
            }
            else if ($start_status_search == '1') {
                $field4 = "start_balance";
                $operator4 = "=";
                $value4 = 0;
            }
            else if ($start_status_search == '2') {
                $field4 = "start_balance";
                $operator4 = ">";
                $value4 = 0;
            }


            $account_in = Account::where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where(['account_type' => 14,'com_code' => $com_code])->orderBy('id', 'DESC')->get('account_number');

            $data = Treasury::whereIn('account_number', $account_in)->where($field1, $operator1, $value1)->where(['com_code' => $com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $d) {
                    if (!empty($d['account_number'])) {
                        $d['current_balance'] = Account::where(['account_number' => $d['account_number'], 'com_code' => auth()->user()->com_code, 'active' => 1])->value('current_balance');
                    }
                }
            }

            return view('admin.treasuries.ajax_search', ['data' => $data]);
        }
    }

    public function create_delivery($id)
    {
        # code...
        if (check_control_menu_role('الحسابات', 'الخزن' , 'اضافة خزنة استلام') == true) {
            try {
                $data_check = Treasury::find($id);
                if (empty($data_check)) {
                    return redirect()->back()->with('error', 'لا يوجد خزينة كهذه');
                }

                $data = Treasury::where(['com_code' => auth()->user()->com_code, 'active' => 1])->get(['name', 'id']);

                return view('admin.treasuries.create_delivery', ['data' => $data, 'master_id' => $id]);
            }
            catch(Exception $e) {
                return redirect()->back()->with('error', 'حدث خطأ'.$e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function store_delivery(Request $request, $id)
    {
        # code...
        $request->validate([
            'receive_from_id' => 'required',
        ]);

        if (check_control_menu_role('الحسابات', 'الخزن' , 'اضافة خزنة استلام') == true) {
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
        else {
            return redirect()->back();
        }
    }

    public function delete_delivery($id, $id_from)
    {
        # code...
        if (check_control_menu_role('الحسابات', 'الخزن' , 'حذف خزنة استلام') == true) {
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
        else {
            return redirect()->back();
        }
    }
}
