<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TreasuryTransaction;
use App\Models\Person;
use App\Models\Treasury;
use App\Models\Account;
use App\Models\Admin;
use App\Models\AdminShift;
use App\Models\MoveType;
use App\Models\AccountType;
use Exception;

class CollectTransactionController extends Controller
{
    //
    public function index()
    {
        # code...
        $com_code = auth()->user()->com_code;
        $data = TreasuryTransaction::where(['com_code' => $com_code, 'transaction_type' => 2])->orderBy('id', 'Desc')->get();
        foreach($data as $d) {
            $d['move_type_name'] = MoveType::where('id', $d['move_type'])->value('name');
            $admin_id = AdminShift::where(['shift_code' => $d['shift_code'],'com_code' => $com_code])->value('admin_id');
            $d['admin_name'] = Admin::where(['id' => $admin_id, 'com_code' => $com_code])->value('name');
            $d['treasuries_name'] = Treasury::where(['id' => $d['treasuries_id'], 'com_code' => $com_code])->value('name');

            $acc = Account::where(['account_number' => $d['account_number'],'com_code' => $com_code])->get(['account_type', 'notes'])->first();
            $d['account_type'] = AccountType::where(['id' => $acc['account_type']])->value('name');
            if (in_array($acc['account_type'], [2, 3, 4, 5])) {
                $first_name = Person::where(['account_number' => $d['account_number'], 'com_code' => $com_code])->value('first_name');
                $last_name = Person::where(['account_number' => $d['account_number'], 'com_code' => $com_code])->value('last_name');
                $d['account_name'] = $first_name . ' ' . $last_name;
            }
            else {
                $d['account_name'] = $acc['notes'];
            }
        }



        $check_has_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'is_finished' => 0, 'com_code' => $com_code])->get(['treasuries_id', 'shift_code'])->first();

        if (!empty($check_has_shift)) {
            $check_has_shift['treasuries_name'] = Treasury::where(['id' => $check_has_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
            $check_has_shift['shift_code'] = AdminShift::where(['admin_id' => auth()->user()->id, 'is_finished' => 0, 'com_code' => $com_code, 'treasuries_id' => $check_has_shift['treasuries_id']])->value('shift_code');
            $check_has_shift['money_in_treasury'] = TreasuryTransaction::where(['shift_code' =>  $check_has_shift['shift_code'], 'com_code' => $com_code])->sum('money');
        }

        $moves_types = MoveType::where(['active' => 1, 'in_screen' => 2, 'is_private_internal' => 0])->get(['id', 'name']);
        $treasuries = Treasury::where(['active' => 1, 'com_code' => $com_code])->get(['id', 'name']);
        $admins = Admin::where(['active' => 1, 'com_code' => $com_code])->get(['id', 'name']);

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


        return view('admin.treasuries_transactions.index', ['data' => $data,
                                                        'check_has_shift' => $check_has_shift,
                                                        'moves_types' => $moves_types,
                                                        'accounts' => $accounts,
                                                        'treasuries' => $treasuries,
                                                        'admins' => $admins
                                                    ]);
    }

    public function store(Request $request)
    {
        # code...
        $request->validate([
            'move_date' => 'required',
            'move_type' => 'required',
            'treasuries_id' => 'required',
            'account_number' => 'required',
            'money' => 'required'
        ],
        [
            'move_date.required' => 'تاريخ الحركة مطلوب',
            'move_type.required' => 'نوع الحركة مطلوب',
            'treasuries_id.required' => 'اسم الخزنة مطلوب',
            'account_number.required' => 'رقم الحساب مطلوب',
            'money.required' => 'مبلغ التحصيل مطلوب',
        ]);

        try {
            $com_code = auth()->user()->com_code;
            $inserted = array();

            $max_transaction_code = TreasuryTransaction::where('com_code', $com_code)->max('transaction_code');
            if (empty($max_transaction_code)) {
                $inserted['transaction_code'] = 1;
            }
            else {
                $inserted['transaction_code'] = $max_transaction_code + 1;
            }


            $check_shift = AdminShift::where(['shift_code' => $request->shift_code, 'com_code' => $com_code, 'is_finished' => 0])->first();
            if (empty($check_shift)) {
                return redirect()->back()->with('error', 'تم اغلاق الشفت الحالي')->withInput();
            }
            else {
                $inserted['shift_code'] = $request->shift_code;
            }

            $last_collect_arrive  =Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->value('last_collection_arrive');

            if (empty($last_collect_arrive)) {
                return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
            }
            else {
                $inserted['last_arrive'] = $last_collect_arrive + 1;
            }

            $inserted['move_type'] = $request->move_type;
            $inserted['account_number'] = $request->account_number;
            $inserted['transaction_type'] = 2;
            $inserted['is_account'] = 1;
            $inserted['is_approved'] = 1;
            $inserted['treasuries_id'] = $request->treasuries_id;
            $inserted['money'] = $request->money;
            $inserted['money_for_account'] = $request->money * (-1);
            $inserted['move_date'] = $request->move_date;
            $inserted['byan'] = $request->byan;
            $inserted['added_by'] = auth()->user()->id;
            $inserted['com_code'] = $com_code;
            $inserted['created_at'] = date('Y-m-d H:i:s');

            $flag = TreasuryTransaction::create($inserted);

            if($flag) {
                $update_treasuries['last_collection_arrive'] = $last_collect_arrive + 1;
                Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->update($update_treasuries);

                $get_current = Account::where(['account_number' => $request->account_number, 'com_code' => $com_code])->value('current_balance');
                $update_account['current_balance'] = $get_current - $request->money;
                Account::where(['account_number' => $request->account_number, 'com_code' => $com_code])->update($update_account);

                return redirect()->back()->with('success', 'تم تحصيل النقدية');
            }
            else {
                return redirect()->back()->with('error', 'حدث خطأ ما');
            }

        }
        catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function get_status(Request $request)
    {
        # code...
        $account_balance = Account::where(['account_number' => $request->account_number, 'com_code' => auth()->user()->com_code])->value('current_balance');
        $account_status = '';
        if ($account_balance < 0) {
            $account_status = 'دائن';
        }
        else if ($account_balance == 0) {
            $account_status = 'متزن';
        }
        else {
            $account_status = 'مدين';
        }

        return view('admin.treasuries_transactions.get_status', ['account_status' => $account_status, 'account_balance' => $account_balance]);
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


            $shift_in = AdminShift::where($filed5, $operator5, $value5)->where(['com_code' => $com_code])->get(['shift_code']);

            $data = TreasuryTransaction::whereIn('shift_code', $shift_in)->where($filed1, $operator1, $value1)->where($filed2, $operator2, $value2)->where($filed3, $operator3, $value3)->where($filed4, $operator4, $value4)->where(['com_code' => $com_code, 'transaction_type' => 2])->orderBy('id', 'Desc')->get();
            foreach($data as $d) {
                $d['move_type_name'] = MoveType::where('id', $d['move_type'])->value('name');
                $admin_id = AdminShift::where(['shift_code' => $d['shift_code'],'com_code' => $com_code])->value('admin_id');
                $d['admin_name'] = Admin::where(['id' => $admin_id, 'com_code' => $com_code])->value('name');
                $d['treasuries_name'] = Treasury::where(['id' => $d['treasuries_id'], 'com_code' => $com_code])->value('name');

                $acc = Account::where(['account_number' => $d['account_number'],'com_code' => $com_code])->get(['account_type', 'notes'])->first();
                $d['account_type'] = AccountType::where(['id' => $acc['account_type']])->value('name');
                if (in_array($acc['account_type'], [2, 3, 4, 5])) {
                    $first_name = Person::where(['account_number' => $d['account_number'], 'com_code' => $com_code])->value('first_name');
                    $last_name = Person::where(['account_number' => $d['account_number'], 'com_code' => $com_code])->value('last_name');
                    $d['account_name'] = $first_name . ' ' . $last_name;
                }
                else {
                    $d['account_name'] = $acc['notes'];
                }
            }

            return view('admin.treasuries_transactions.ajax_search', ['data' => $data]);

        }
    }
}
