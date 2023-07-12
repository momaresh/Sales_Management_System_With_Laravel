<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TreasuryTransaction;
use App\Models\Person;
use App\Models\Treasury;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\Admin;
use App\Models\AdminShift;
use App\Models\MoveType;
use Exception;

class UnpaidTransactionController extends Controller
{
    //
    public function index()
    {
        # code...
        if (check_control_menu_role('الحسابات', 'شاشة الدفع الآجل' , 'عرض') == true || check_control_menu_role('الحسابات', 'شاشة صرف النقدية' , 'اضافة') == true){
            try {
                $com_code = auth()->user()->com_code;
                $data = TreasuryTransaction::where(['com_code' => $com_code, 'transaction_type' => 3])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);
                if (!empty($data)) {
                    foreach($data as $d) {
                        $d['move_type_name'] = MoveType::where('id', $d['move_type'])->value('name');
                        $admin_id = AdminShift::where(['id' => $d['shift_code'],'com_code' => $com_code])->value('admin_id');
                        $d['admin_name'] = Admin::where(['id' => $admin_id, 'com_code' => $com_code])->value('name');
                        $d['treasuries_name'] = Treasury::where(['id' => $d['treasuries_id'], 'com_code' => $com_code])->value('name');
                        $d['shift_code'] = AdminShift::where(['id' => $d['shift_code']])->value('shift_code');

                        $acc = Account::where(['account_number' => $d['account_number'],'com_code' => $com_code])->get(['account_type', 'notes'])->first();
                        $d['account_type'] = AccountType::where(['id' => $acc['account_type']])->value('name');

                        if ($acc['account_type'] == 14) {
                            $d['account_name'] = Treasury::where(['account_number' => $d['account_number']])->value('name');
                        }
                        else if (in_array($acc['account_type'], [2, 3, 4, 5])) {
                            $first_name = Person::where(['account_number' => $d['account_number'], 'com_code' => $com_code])->value('first_name');
                            $last_name = Person::where(['account_number' => $d['account_number'], 'com_code' => $com_code])->value('last_name');
                            $d['account_name'] = $first_name . ' ' . $last_name;
                        }
                        else {
                            $d['account_name'] = $acc['notes'];
                        }
                    }
                }

                $moves_types = MoveType::where(['active' => 1, 'in_screen' => 1, 'is_private_internal' => 0])->get(['id', 'name']);

                $accounts = Account::where(['active' => 1, 'com_code' => $com_code, 'is_parent' => 0])->get();
                if (!empty($accounts)) {
                    foreach($accounts as $ac) {
                        if (in_array($ac['account_type'], [2, 3, 4, 5]) ) {
                            $ac['first_name'] = Person::where(['com_code' => $com_code, 'account_number' => $ac['account_number']])->value('first_name');
                            $ac['last_name'] = Person::where(['com_code' => $com_code,  'account_number' => $ac['account_number']])->value('last_name');
                            $ac['name'] = $ac['first_name'] . ' ' . $ac['last_name'];
                        }
                        else {
                            $ac['name'] = $ac['notes'];
                        }
                        $ac['type'] = AccountType::where(['id' => $ac['account_type']])->value('name');
                    }
                }

                $treasuries = Treasury::where(['active' => 1, 'com_code' => $com_code])->get(['id', 'name']);
                $admins = Admin::where(['active' => 1, 'com_code' => $com_code])->get(['id', 'name']);


                return view('admin.unpaid_transactions.index', ['data' => $data,
                                                                'moves_types' => $moves_types,
                                                                'accounts' => $accounts,
                                                                'treasuries' => $treasuries,
                                                                'admins' => $admins
                                                            ]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function ajax_search(Request $request)
    {
        # code...
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $radio_search = $request->radio_search;
            $text_search = $request->text_search;
            $account_number_search = $request->account_number_search;
            $move_type_search = $request->move_type_search;
            $treasuries_search = $request->treasuries_search;
            $admin_search = $request->admin_search;

            if ($radio_search == 'code') {
                if ($text_search == '') {
                    $filed1 = 'id';
                    $operator1 = '>';
                    $value1 = 0;
                }
                else {
                    $filed1 = 'transaction_code';
                    $operator1 = '=';
                    $value1 = $text_search;
                }
            }
            else if ($radio_search == 'arrive') {
                if ($text_search == '') {
                    $filed1 = 'id';
                    $operator1 = '>';
                    $value1 = 0;
                }
                else {
                    $filed1 = 'last_arrive';
                    $operator1 = '=';
                    $value1 = $text_search;
                }
            }
            else if ($radio_search == 'shift') {
                if ($text_search == '') {
                    $filed1 = 'id';
                    $operator1 = '>';
                    $value1 = 0;
                }
                else {
                    $filed1 = 'shift_code';
                    $operator1 = '=';
                    $value1 = $text_search;
                }
            }

            if ($account_number_search == 'all') {
                $filed2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            }
            else {
                $filed2 = 'account_number';
                $operator2 = 'LIKE';
                $value2 = '%'.$account_number_search.'%';
            }

            if ($move_type_search == 'all') {
                $filed3 = 'id';
                $operator3 = '>';
                $value3 = 0;
            }
            else {
                $filed3 = 'move_type';
                $operator3 = '=';
                $value3 = $move_type_search;
            }

            if ($treasuries_search == 'all') {
                $filed4 = 'id';
                $operator4 = '>';
                $value4 = 0;
            }
            else {
                $filed4 = 'treasuries_id';
                $operator4 = '=';
                $value4 = $treasuries_search;
            }

            if ($admin_search == 'all') {
                $filed5 = 'id';
                $operator5 = '>';
                $value5 = 0;
            }
            else {
                $filed5 = 'admin_id';
                $operator5 = '=';
                $value5 = $admin_search;
            }


            $com_code = auth()->user()->com_code;


            $shift_in = AdminShift::where($filed5, $operator5, $value5)->where(['com_code' => $com_code])->get(['id']);

            $data = TreasuryTransaction::whereIn('shift_code', $shift_in)->where($filed1, $operator1, $value1)->where($filed2, $operator2, $value2)->where($filed3, $operator3, $value3)->where($filed4, $operator4, $value4)->where(['com_code' => $com_code, 'transaction_type' => 3])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);
            if (!empty($data)) {
                foreach($data as $d) {
                    $d['move_type_name'] = MoveType::where('id', $d['move_type'])->value('name');
                    $admin_id = AdminShift::where(['id' => $d['shift_code'],'com_code' => $com_code])->value('admin_id');
                    $d['admin_name'] = Admin::where(['id' => $admin_id, 'com_code' => $com_code])->value('name');
                    $d['treasuries_name'] = Treasury::where(['id' => $d['treasuries_id'], 'com_code' => $com_code])->value('name');
                    $d['shift_code'] = AdminShift::where(['id' => $d['shift_code']])->value('shift_code');

                    $acc = Account::where(['account_number' => $d['account_number'],'com_code' => $com_code])->get(['account_type', 'notes'])->first();
                    $d['account_type'] = AccountType::where(['id' => $acc['account_type']])->value('name');

                    if ($acc['account_type'] == 14) {
                        $d['account_name'] = Treasury::where(['account_number' => $d['account_number']])->value('name');
                    }
                    else if (in_array($acc['account_type'], [2, 3, 4, 5])) {
                        $first_name = Person::where(['account_number' => $d['account_number'], 'com_code' => $com_code])->value('first_name');
                        $last_name = Person::where(['account_number' => $d['account_number'], 'com_code' => $com_code])->value('last_name');
                        $d['account_name'] = $first_name . ' ' . $last_name;
                    }
                    else {
                        $d['account_name'] = $acc['notes'];
                    }
                }
            }

            return view('admin.unpaid_transactions.ajax_search', ['data' => $data]);

        }
    }
}
