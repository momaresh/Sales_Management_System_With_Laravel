<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Person;
use App\Models\Account;
use App\Models\Customer;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\AdminPanelSetting;
use Exception;

class CustomerController extends Controller
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
            $com_code = auth()->user()->com_code;
            $data = Person::where(['person_type' => 1, 'com_code' => $com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);

            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['customer_code'] = Customer::where(['person_id' => $d['id'], 'com_code' => $com_code ])->value('customer_code');
                    $d['current_balance'] = Account::where(['account_number' => $d['account_number'], 'com_code' => $com_code, 'active' => 1])->value('current_balance');
                    $d['start_balance'] = Account::where(['account_number' => $d['account_number'], 'com_code' => $com_code, 'active' => 1])->value('start_balance');
                }
            }

            return view('admin.customers.index', ['data' => $data]);
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
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomerRequest $request)
    {
        try {
            //
            if (!empty(Person::first())) {
                $person_id = Person::max('id');
            }
            else {
                $person_id = 1;
            }

            if (!empty(Customer::where('com_code', auth()->user()->com_code)->first())) {
                $customer_code = Customer::where('com_code', auth()->user()->com_code)->max('customer_code');
            }
            else {
                $customer_code = AdminPanelSetting::where('com_code', auth()->user()->com_code)->value('customer_first_code');
            }

            if (!empty(Account::first())) {
                $account_number = Account::max('account_number');
            }
            else {
                $account_number = 12345;
            }

            $account_insert['account_number'] = $account_number + 1;
            $account_insert['account_type'] = 3;
            $account_insert['is_parent'] = 0;
            $account_insert['parent_account_number'] = AdminPanelSetting::where('com_code', auth()->user()->com_code)->value('customer_parent_account');
            $account_insert['start_balance_status'] = $request->start_balance_status;
            $account_insert['start_balance'] = $request->start_balance;
            $account_insert['active'] = 1;
            $account_insert['notes'] = $request->notes;
            $account_insert['added_by'] = auth()->user()->id;
            $account_insert['created_at'] = date('Y-m-d H:i:s');
            $account_insert['com_code'] = auth()->user()->com_code;
            $account_insert['date'] = date('Y-m-d');

            Account::create($account_insert);


            $person_insert['id'] = $person_id + 1;
            $person_insert['first_name'] = $request->first_name;
            $person_insert['last_name'] = $request->last_name;
            $person_insert['account_number'] = $account_number + 1;
            $person_insert['address'] = $request->address;
            $person_insert['phone'] = $request->phone;
            $person_insert['active'] = $request->active;
            $person_insert['person_type'] = 1;
            $person_insert['added_by'] = auth()->user()->id;
            $person_insert['created_at'] = date('Y-m-d H:i:s');
            $person_insert['com_code'] = auth()->user()->com_code;

            if (Person::create($person_insert)) {
                $customer_insert['person_id'] = $person_id + 1;
                $customer_insert['customer_code'] = $customer_code + 1;
                $customer_insert['created_at'] = date('Y-m-d H:i:s');
                $customer_insert['com_code'] = auth()->user()->com_code;
                Customer::create($customer_insert);
                return redirect()->route('admin.customers.index')->with('success', 'تم اضافة العميل بنجاح');
            }

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
        $data = Person::where('id', $id)->first();
        return view('admin.customers.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        //
        try {
            $person_updated['first_name'] = $request->first_name;
            $person_updated['last_name'] = $request->last_name;
            $person_updated['address'] = $request->address;
            $person_updated['phone'] = $request->phone;
            $person_updated['active'] = $request->active;
            $person_updated['updated_by'] = auth()->user()->id;
            $person_updated['updated_at'] = date('Y-m-d H:i:s');

            if (Person::where(['id' => $id, 'com_code' => auth()->user()->com_code])->update($person_updated)) {
                $customer_updated['updated_at'] = date('Y-m-d H:i:s');
                Customer::where(['person_id' => $id, 'com_code' => auth()->user()->com_code])->update($customer_updated);
            }

            return redirect()->route('admin.customers.index')->with('success', 'تم تعديل العميل بنجاح');
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

    public function delete($id)
    {
        # code...

        try {
            Customer::where(['person_id' => $id, 'com_code' => auth()->user()->com_code])->delete();
            $account_number = Person::where(['id' => $id, 'com_code' => auth()->user()->com_code])->value('account_number');
            Person::where(['id' => $id, 'com_code' => auth()->user()->com_code])->delete();
            Account::where(['account_number' => $account_number, 'com_code' => auth()->user()->com_code])->delete();

            return redirect()->back()->with('success', 'تم حذف العميل بنجاح');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function ajax_search(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $search_by_name = $request->search_by_name;
            $search_by_radio = $request->search_by_radio;

            if ($search_by_radio == 'name') {
                if ($search_by_name == '') {
                    $data = Person::where(['person_type' => 1, 'com_code' => $com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
                }
                else {
                    $data = Person::where('last_name', 'LIKE', "%$search_by_name%")->where(['person_type' => 1, 'com_code' => $com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
                }
            }
            else if ($search_by_radio == 'acc_number') {
                if ($search_by_name == '') {
                    $data = Person::where(['person_type' => 1, 'com_code' => $com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
                }
                else {
                    $data = Person::where('account_number', 'LIKE', "%$search_by_name%")->where(['person_type' => 1,'com_code' => $com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
                }
            }
            else if ($search_by_radio == 'cus_code') {
                if ($search_by_name == '') {
                    $data = Person::where(['person_type' => 1, 'com_code' => $com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
                }
                else {
                    $person_id = Customer::where('customer_code', 'LIKE' ,"%$search_by_name%")->where(['com_code' => auth()->user()->com_code])->get('person_id');
                    $data = Person::whereIn('id', $person_id)->where('com_code', $com_code)->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
                }
            }
            else {
                $data = Person::where(['person_type' => 1, 'com_code' => $com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
            }
            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['customer_code'] = Customer::where(['person_id' => $d['id'], 'com_code' => $com_code ])->value('customer_code');
                    $d['current_balance'] = Account::where(['account_number' => $d['account_number'], 'com_code' => $com_code, 'active' => 1])->value('current_balance');
                    $d['start_balance'] = Account::where(['account_number' => $d['account_number'], 'com_code' => $com_code, 'active' => 1])->value('start_balance');
                }
            }
            
            return view('admin.customers.ajax_search', ['data' => $data]);

        }
    }


    public function details(Request $request)
    {
        //
        try {
            $id = $request->id;
            $com_code = auth()->user()->com_code;
            $data = Person::where(['id' => $id, 'person_type' => 1, 'com_code' => $com_code])->get()->first();
            $account_data = Account::where(['account_number' => $data['account_number'], 'com_code' => $com_code, 'active' => 1])->get()->first();
            $customer_data = Customer::where(['person_id' => $id, 'com_code' => $com_code])->get()->first();

            if (!empty($data)) {
                $data['customer_code'] = $customer_data['customer_code'];
                $data['current_balance'] = $account_data['current_balance'];
                $data['parent_account'] = $account_data['parent_account_number'];
                $data['parent_account_name'] = Account::where(['account_number' => $account_data['parent_account_number'], 'com_code' => $com_code, 'active' => 1])->value('notes');
                $data['added_by_name'] = Admin::where(['id' => $data['added_by'], 'com_code' => $com_code])->value('name');
                $data['updated_by_name'] = Admin::where(['id' => $data['updated_by'], 'com_code' => $com_code])->value('name');
                $data['start_balance'] = $account_data['start_balance'];
            }
            return view('admin.customers.details', ['data' => $data]);
        }
        catch(Exception $e) {

        }
    }

}
