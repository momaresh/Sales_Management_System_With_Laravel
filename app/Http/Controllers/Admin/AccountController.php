<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\Admin;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Person;
use App\Models\Treasury;
use Exception;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (check_control_menu_role('الحسابات', 'الحسابات' , 'عرض') == true) {
            try {
                $data = Account::where(['com_code' => auth()->user()->com_code, 'active' => 1])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
                $account_types = AccountType::get();
                if (!empty($data)) {
                    foreach ($data as $d) {
                        $d['account_type_name'] = AccountType::where(['id' => $d['account_type']])->value('name');

                        if ($d['account_type'] ==  14) {
                            $d['account_name'] =  Treasury::where(['account_number' => $d['account_number']])->value('name');
                        }
                        else if ($d['account_type'] == 2 || $d['account_type'] == 3 || $d['account_type'] == 4 || $d['account_type'] == 5) {
                            $person = Person::where(['account_number' => $d['account_number']])->get(['first_name', 'last_name'])->first();
                            if ($person != null) {
                                $d['account_name'] = $person->first_name . ' ' . $person->last_name;
                            }
                        }
                        else {
                            $d['account_name'] = $d['notes'];
                        }

                        if (empty($d['parent_account_number'])) {
                            $d['parent_account_name'] = "هو اب";
                        }
                        else {
                            $d['parent_account_name'] = Account::where(['account_number' => $d['parent_account_number']])->value('notes');
                        }

                    }
                }
                return view('admin.accounts.index', ['data' => $data, 'account_types' => $account_types]);
            }
            catch (Exception $e) {

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
        if (check_control_menu_role('الحسابات', 'الحسابات' , 'اضافة') == true) {
            $com_code = auth()->user()->com_code;
            $account_types = AccountType::where(["active" => 1, 'related_internal_accounts' => 0])->get(['id', 'name']);
            $parent_accounts = Account::where(["active" => 1, "com_code" => $com_code, 'is_parent' => 1])->get(['account_number', 'notes']);

            return view('admin.accounts.create', ['account_types' => $account_types, 'parent_accounts' => $parent_accounts]);
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
    public function store(CreateAccountRequest $request)
    {
        //
        if (check_control_menu_role('الحسابات', 'الحسابات' , 'اضافة') == true) {
            try {
                //set account number
                // get the max account that is not parent account
                $check = Account::where(['com_code' => auth()->user()->com_code, 'notes' => $request->notes])->count();
                if ($check > 0) {
                    return redirect()->back()->with('error', 'الوصف للحساب موجود مسبقاً')->withInput();
                }

                $max = Account::max('account_number');
                if (!empty($max)) {
                    $inserted['account_number'] = $max + 1;
                } else {
                    $inserted['account_number'] = 1;
                }

                $inserted['account_type'] = $request->account_type;
                $inserted['is_parent'] = $request->is_parent;

                if ($request->is_parent == 0) {
                    $inserted['parent_account_number'] = $request->parent_account_number;
                }

                $inserted['start_balance_status'] = $request->start_balance_status;

                if ($inserted['start_balance_status'] == 1) {
                    //credit
                    $inserted['start_balance'] = $request->start_balance * (-1);
                }
                else if ($inserted['start_balance_status'] == 2) {
                    //debit
                    $inserted['start_balance'] = $request->start_balance;

                    if ($inserted['start_balance'] < 0) {
                        $inserted['start_balance'] = $inserted['start_balance'] * (-1);
                    }
                }
                elseif ($inserted['start_balance_status'] == 3) {
                    //balanced
                    $inserted['start_balance'] = 0;
                }
                else {
                    $inserted['start_balance_status'] = 3;
                    $inserted['start_balance'] = 0;
                }

                $inserted['notes'] = $request->notes;
                $inserted['active'] = $request->active;
                $inserted['added_by'] = auth()->user()->id;
                $inserted['created_at'] = date("Y-m-d H:i:s");
                $inserted['date'] = date("Y-m-d");
                $inserted['com_code'] = auth()->user()->com_code;

                Account::create($inserted);
                return redirect()->route('admin.accounts.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
            }
            catch (Exception $e) {
                return redirect()->back()
                    ->with(['error' => 'عفوا حدث خطأ ما' . $e->getMessage()])
                    ->withInput();
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
        if (check_control_menu_role('الحسابات', 'الحسابات' , 'تعديل') == true) {
            $com_code = auth()->user()->com_code;
            $data = Account::find($id);
            if (empty($data)) {
                return redirect()->route('admin.accounts.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }

            $related_internal_accounts = AccountType::where('id', $data['account_type'])->value('related_internal_accounts');

            if ($related_internal_accounts == 1) {
                return redirect()->route('admin.accounts.index')->with(['error' => 'عفوا لايمكن تحديث هذا الحساب الي من شاشته الخاصه حسب نوعه']);
            }

            $account_types = AccountType::where(["active" => 1])->get(['id', 'name', 'related_internal_accounts']);

            $parent_accounts = Account::where(["active" => 1, "com_code" => $com_code, 'parent_account_number' => null])->get(['account_number', 'notes']);
            $person_account = Account::where(["id" => $id, "com_code" => $com_code])->value('account_number');
            $person_name = Person::where(["com_code" => $com_code, 'account_number' => $person_account])->get(['id', 'first_name', 'last_name'])->first();

            return view('admin.accounts.edit', ['account_types' => $account_types, 'parent_accounts' => $parent_accounts, 'data' => $data, 'person_name' => $person_name]);
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
    public function update(UpdateAccountRequest $request, $id)
    {
        //
        if (check_control_menu_role('الحسابات', 'الحسابات' , 'تعديل') == true) {
            try {
                $check = Account::where(['com_code' => auth()->user()->com_code, 'notes' => $request->notes])->count();
                if ($check > 0) {
                    return redirect()->back()->with('error', 'الوصف للحساب موجود مسبقاً')->withInput();
                }

                $related_internal_accounts = AccountType::where('id', $request->account_type)->value('related_internal_accounts');

                if ($related_internal_accounts == 1) {
                    return redirect()->route('admin.accounts.index')->with(['error' => 'عفوا لايمكن تحديث هذا الحساب الي من شاشته الخاصه حسب نوعه']);
                }

                $updated['account_type'] = $request->account_type;
                $updated['is_parent'] = $request->is_parent;

                if ($updated['is_parent'] == 0) {
                    $updated['parent_account_number'] = $request->parent_account_number;
                }

                $updated['start_balance_status'] = $request->start_balance_status;

                if ($updated['start_balance_status'] == 1) {
                    //credit
                    $updated['start_balance'] = $request->start_balance * (-1);
                }
                else if ($updated['start_balance_status'] == 2) {
                    //debit
                    $updated['start_balance'] = $request->start_balance;

                    if ($updated['start_balance'] < 0) {
                        $updated['start_balance'] = $updated['start_balance'] * (-1);
                    }
                }
                else {
                    $updated['start_balance_status'] = 3;
                    $updated['start_balance'] = 0;
                }

                $updated['active'] = $request->active;
                $updated['updated_by'] = auth()->user()->id;
                $updated['updated_at'] = date("Y-m-d H:i:s");

                Account::where('id', $id)->update($updated);
                return redirect()->route('admin.accounts.index')->with(['success' => 'لقد تم تحديث البيانات بنجاح']);

            }
            catch (Exception $e) {
                return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $e->getMessage()])
                ->withInput();
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
    public function delete($id)
    {
        if (check_control_menu_role('الحسابات', 'الحسابات' , 'اضافة') == true) {
            try {
                $item_row = Account::find($id);

                if (!empty($item_row)) {
                    $flag = Account::where('id', $id)->delete();
                    if ($flag) {
                        return redirect()->back()->with(['success' => 'تم حذف البيانات بنجاح']);
                    }
                    else {
                        return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما']);
                    }
                }
                else {
                    return redirect()->back()->with(['error' => 'عفوا غير قادر الي الوصول للبيانات المطلوبة']);
                }
            }
            catch (Exception $e) {
                return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما' . $e->getMessage()]);
            }
        }
        else {
            return redirect()->back();
        }


    }

    public function ajax_search(Request $request)
    {
        if ($request->ajax()) {
            $search_by_text = $request->search_by_text;
            $is_parent = $request->is_parent;
            $account_type = $request->account_type;
            $active_search = $request->active_search;

            if ($is_parent == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "is_parent";
                $operator1 = "=";
                $value1 = $is_parent;
            }

            if ($account_type == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "account_type";
                $operator2 = "=";
                $value2 = $account_type;
            }

            if (empty($search_by_text)) {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            }
            else {
                //true
                $field3 = "account_number";
                $operator3 = "LIKE";
                $value3 = '%'.$search_by_text.'%';
            }

            if ($active_search == 'all') {
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
            }
            else {
                $field4 = "active";
                $operator4 = "=";
                $value4 = $active_search;
            }

            $data = Account::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where('com_code', auth()->user()->com_code)->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);

            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['account_type_name'] = AccountType::where(['id' => $d['account_type']])->value('name');
                    if ($d['account_type'] ==  14) {
                        $d['account_name'] =  Treasury::where(['account_number' => $d['account_number']])->value('name');
                    }
                    else if ($d['account_type'] == 2 || $d['account_type'] == 3 || $d['account_type'] == 4 || $d['account_type'] == 5) {
                        $person = Person::where(['account_number' => $d['account_number']])->get(['first_name', 'last_name'])->first();
                        if ($person != null) {
                            $d['account_name'] = $person->first_name . ' ' . $person->last_name;
                        }
                    }
                    else {
                        $d['account_name'] = $d['notes'];
                    }

                    if (empty($d['parent_account_number'])) {
                        $d['parent_account_name'] = "هو اب";
                    }
                    else {
                        $d['parent_account_name'] = Account::where(['account_number' => $d['parent_account_number']])->value('notes');
                    }

                }
            }

            return view('admin.accounts.ajax_search', ['data' => $data]);

        }
    }
}
