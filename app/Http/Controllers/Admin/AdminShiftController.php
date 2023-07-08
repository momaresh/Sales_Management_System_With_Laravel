<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AdminShift;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\AdminPanelSetting;
use App\Models\Treasury;
use App\Models\AdminTreasury;
use App\Models\MoveType;
use App\Models\TreasuryDelivery;
use App\Models\TreasuryTransaction;
use Exception;


class AdminShiftController extends Controller
{
    public function index()
    {
        if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'عرض') == true) {
            //
            try {
                $data = AdminShift::where(['com_code' => auth()->user()->com_code])->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);

                $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'is_finished' => 0, 'com_code' => auth()->user()->com_code])->get(['treasuries_id', 'id'])->first();

                if (!empty($data)) {
                    foreach ($data as $d) {
                        $d['admin_name'] = Admin::where(['id' => $d['admin_id']])->value('name');
                        $d['treasuries_name'] = Treasury::where(['id' => $d['treasuries_id']])->value('name');
                        $d['allowed_review'] = false;
                        if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'مراجعة شفت') == true) {
                            if ($d['is_finished'] == 1 && empty($d['delivered_to_shift_id']) && !empty($check_shift)) {
                                $allowed_tre = TreasuryDelivery::where(['treasuries_id' => $check_shift['treasuries_id']])->get(['treasuries_receive_from_id']);

                                if (!empty($allowed_tre)) {
                                    foreach ($allowed_tre as $tre) {
                                        if ($d['treasuries_id'] == $tre['treasuries_receive_from_id']) {
                                            $d['allowed_review'] = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $com_code = auth()->user()->com_code;
                $admins = Admin::where(['com_code' => $com_code, 'active' => 1])->get(['id', 'name']);
                $treasuries = Treasury::where(['com_code' => $com_code, 'active' => 1])->get(['id', 'name']);

                return view('admin.admin_shifts.index', ['data' => $data, 'check_shift' => $check_shift, 'admins' => $admins, 'treasuries' => $treasuries]);
            }
            catch (Exception $e) {

            }
        }
        else {
            return redirect()->back();
        }
    }

    public function create()
    {
        if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'اضافة') == true) {
           // we need to get all the treasuries that the admin has privilege to and no other user still work on it
           $has_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => auth()->user()->com_code, 'is_finished' => 0])->first();
           if (!empty($has_shift)) {
               return redirect()->back()->with('error', 'انت تملك شفت ما زال مستخدما')->withInput();
           }

           $treasuries = AdminTreasury::where(['admin_id' => auth()->user()->id, 'com_code' => auth()->user()->com_code, 'active' => 1])->get('treasuries_id');
           $not_available_treasuries_array = array();

           if (!empty($treasuries)) {
               $i = 0;
               foreach ($treasuries as $tr) {
                   $id = AdminShift::where(['treasuries_id' => $tr['treasuries_id'], 'com_code' => auth()->user()->com_code, 'is_finished' => 0])->value('treasuries_id');
                   if (!empty($id)) {
                       $not_available_treasuries_array[$i] = $tr['treasuries_id'];
                       $i++;
                   }
               }
           }


           $available_treasuries = AdminTreasury::where(['admin_id' => auth()->user()->id, 'com_code' => auth()->user()->com_code, 'active' => 1])->whereNotIn('treasuries_id', $not_available_treasuries_array)->get('treasuries_id');

           if (!empty($available_treasuries)) {
               foreach ($available_treasuries as $tr) {
                   $tr['treasuries_name'] = Treasury::where(['id' => $tr['treasuries_id'], 'com_code' => auth()->user()->com_code])->value('name');
               }
           }

           return view('admin.admin_shifts.create', ['treasuries' => $available_treasuries]);

        }
        else {
            return redirect()->back();
        }

    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'treasuries_id' => 'required'
        ],
        [
            'treasuries_id.required' => 'اسم الخزنة مطلوب'
        ]);

        if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'اضافة') == true) {
            try {
                // we check that the user not has other shift work on and still not finish
                $has_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => auth()->user()->com_code, 'is_finished' => 0])->first();
                if (!empty($has_shift)) {
                    return redirect()->back()->with('error', 'انت تملك شفت ما زال مستخدما')->withInput();
                }

                $max_code = AdminShift::where(['com_code' => auth()->user()->com_code])->max('shift_code');

                if (empty($max_code)) {
                    $inserted['shift_code'] = 1;
                }
                else {
                    $inserted['shift_code'] = $max_code + 1;
                }

                $inserted['admin_id'] = auth()->user()->id;
                $inserted['treasuries_id'] = $request->treasuries_id;
                $inserted['start_date'] = date('Y-m-d H:i:s');
                $inserted['added_by'] = auth()->user()->id;
                $inserted['created_at'] = date('Y-m-d H:i:s');
                $inserted['com_code'] = auth()->user()->com_code;

                AdminShift::create($inserted);
                return redirect()->route('admin.admin_shifts.index')->with('success', 'تم استلام الشفت بنجاح');
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
         }
         else {
             return redirect()->back();
         }
    }

    public function end_shift($id)
    {
        if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'انهاء شفت') == true) {
            try {
                // we check that the user not has other shift work on and still not finish
                $check = AdminShift::where(['admin_id' => auth()->user()->id, 'com_code' => auth()->user()->com_code, 'id' => $id])->first();
                if (empty($check)) {
                    if (auth()->user()->roles_id == 1) {
                        $check = AdminShift::where(['com_code' => auth()->user()->com_code, 'id' => $id])->first();
                        if (empty($check)) {
                            return redirect()->back()->with('error', 'لا يمكن الوصول للبيانات المطلوبة');
                        }
                    }
                    else {
                        return redirect()->back()->with('error', 'لا يمكن الوصول للبيانات المطلوبة');
                    }
                }

                $money = TreasuryTransaction::where(['shift_code' => $check['id'], 'com_code' => auth()->user()->com_code])->sum('money');
                $updated['is_finished'] = 1;
                $updated['money_should_delivered'] = $money;
                $updated['end_date'] = date('Y-m-d H:i:s');
                $updated['finished_by'] = auth()->user()->id;
                $updated['updated_by'] = auth()->user()->id;
                $updated['updated_at'] = date('Y-m-d H:i:s');

                AdminShift::where('id', $id)->update($updated);
                return redirect()->route('admin.admin_shifts.index')->with('success', 'تم انهاء الشفت بنجاح');
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
         }
         else {
             return redirect()->back();
        }
    }

    public function printA4($id)
    {
        if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'طباعة') == true) {
            //
            try {
                $com_code = auth()->user()->com_code;

                $data = AdminShift::where(['id' => $id, 'com_code' => $com_code])->get()->first();
                if (empty($data)) {
                    return redirect()->back()->with('error', 'لا يمكن الوصول للبيانات المطلوبة');
                }


                $data = AdminShift::where(['id' => $id, 'com_code' => $com_code])->get()->first();
                if (!empty($data)) {
                    $data['admin_name'] = Admin::where(['id' => $data['admin_id']])->value('name');
                    $data['finished_by_name'] = Admin::where(['id' => $data['finished_by']])->value('name');
                    $data['treasuries_name'] = Treasury::where(['id' => $data['treasuries_id']])->value('name');

                    if (!empty($data['delivered_to_shift_id'])) {
                        $shift_review_data = AdminShift::where(['id' => $id, 'com_code' => $com_code])->get(['admin_id', 'treasuries_id'])->first();
                        $data['admin_review_name'] = Admin::where(['id' => $shift_review_data['admin_id']])->value('name');
                        $data['treasuries_review_name'] = Treasury::where(['id' => $shift_review_data['treasuries_id']])->value('name');
                    }
                }

                $data['all_exchange'] = TreasuryTransaction::where(['shift_code' => $data['id'], 'com_code' => $com_code, 'transaction_type' => 1])->sum('money');
                $data['all_collection'] = TreasuryTransaction::where(['shift_code' => $data['id'], 'com_code' => $com_code, 'transaction_type' => 2])->sum('money');

                $exchange_transactions = TreasuryTransaction::where(['shift_code' => $data['id'], 'transaction_type' => 1, 'com_code' => $com_code])->get();
                if (!empty($exchange_transactions)) {
                    foreach ($exchange_transactions as $tran) {
                        $tran['type_name'] = MoveType::where('id', $tran['move_type'])->value('name');
                    }
                }

                $collection_transactions = TreasuryTransaction::where(['shift_code' => $data['id'], 'transaction_type' => 2, 'com_code' => $com_code])->get();
                if (!empty($collection_transactions)) {
                    foreach ($collection_transactions as $tran) {
                        $tran['type_name'] = MoveType::where('id', $tran['move_type'])->value('name');
                    }
                }

                $systemData = AdminPanelSetting::where(['com_code' => $com_code])->get()->first();

                return view('admin.admin_shifts.printA4', ['data' => $data, 'systemData' => $systemData, 'exchange_transactions' => $exchange_transactions, 'collection_transactions' => $collection_transactions]);
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function review_shift(Request $request)
    {
        if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'مراجعة شفت') == true) {
            try {
                // we check that the user not has other shift work on and still not finish
                $com_code = auth()->user()->com_code;
                $do_review_shift = AdminShift::where(['id' => $request->do_review_shift_id, 'com_code' => $com_code])->get(['id', 'shift_code', 'treasuries_id'])->first();
                if (empty($do_review_shift)) {
                    return redirect()->back()->with('error', 'انت لا تملك شفت حالي');
                }

                $was_review_shift = AdminShift::where(['id' => $request->was_review_shift_id, 'com_code' => $com_code])->first();
                if (empty($was_review_shift)) {
                    return redirect()->back()->with('error', 'لا يوجد شفت كهذا للمراجعة');
                }

                if (!empty($was_review_shift['delivered_to_shift_id'])) {
                    return redirect()->back()->with('error', 'لقد تم مراجعة هذا الشفت');
                }

                if ($request->what_paid > 0) {
                    $max_transaction_code = TreasuryTransaction::where(['transaction_type' => 2,'com_code' => $com_code])->max('transaction_code');
                    if (empty($max_transaction_code)) {
                        $inserted['transaction_code'] = 1;
                    }
                    else {
                        $inserted['transaction_code'] = $max_transaction_code + 1;
                    }

                    $inserted['shift_code'] = $do_review_shift['id'];
                    $last_collection_arrive = Treasury::where(['id' => $do_review_shift['treasuries_id'], 'com_code' => $com_code])->value('last_collection_arrive');
                    if (empty($last_collection_arrive)) {
                        return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                    }
                    else {
                        $inserted['last_arrive'] = $last_collection_arrive + 1;
                    }

                    $treasury_name = Treasury::where(['id' => $was_review_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                    $admin_name = Admin::where(['id' => $was_review_shift['admin_id'], 'com_code' => $com_code])->value('name');
                    $account_number = Treasury::where(['id' => $was_review_shift['treasuries_id']])->value('account_number');


                    $inserted['move_type'] = 1;
                    $inserted['transaction_type'] = 2;
                    $inserted['is_account'] = 1;
                    $inserted['account_number'] = $account_number;
                    $inserted['is_approved'] = 1;
                    $inserted['treasuries_id'] = $do_review_shift['treasuries_id'];
                    $inserted['money'] = $request->what_paid;
                    $inserted['money_for_account'] = $request->what_paid * (-1);
                    $inserted['move_date'] = date('Y-m-d');
                    $inserted['byan'] = 'مراجعة واستلام الخزنة ' . $treasury_name . ' من المستخدم ' . $admin_name . ' رقم الشفت الذي تم مراجعته ' . $request->was_review_shift_id;
                    $inserted['added_by'] = auth()->user()->id;
                    $inserted['com_code'] = $com_code;
                    $inserted['created_at'] = date('Y-m-d H:i:s');
                    $flag = TreasuryTransaction::create($inserted);

                    if($flag) {
                        $update_treasuries['last_collection_arrive'] = $last_collection_arrive + 1;
                        Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->update($update_treasuries);

                        $money = $request->what_paid - $was_review_shift['money_should_delivered'];
                        if ($money == 0) {
                            $update_shift['money_state'] = 0;
                        }
                        else if ($money < 0) {
                            $update_shift['money_state'] = 1;

                            $max_transaction_code = TreasuryTransaction::where(['transaction_type' => 3,'com_code' => $com_code])->max('transaction_code');
                            if (empty($max_transaction_code)) {
                                $inserted['transaction_code'] = 1;
                            }
                            else {
                                $inserted['transaction_code'] = $max_transaction_code + 1;
                            }

                            $inserted['shift_code'] = $do_review_shift['id'];
                            $last_unpaid_arrive = Treasury::where(['id' => $do_review_shift['treasuries_id'], 'com_code' => $com_code])->value('last_unpaid_arrive');
                            if (empty($last_unpaid_arrive)) {
                                return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                            }
                            else {
                                $inserted['last_arrive'] = $last_unpaid_arrive + 1;
                            }

                            $treasury_name = Treasury::where(['id' => $was_review_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                            $admin_name = Admin::where(['id' => $was_review_shift['admin_id'], 'com_code' => $com_code])->value('name');
                            $account_number = Treasury::where(['id' => $was_review_shift['treasuries_id']])->value('account_number');


                            $inserted['move_type'] = 1;
                            $inserted['transaction_type'] = 3;
                            $inserted['is_account'] = 1;
                            $inserted['account_number'] = $account_number;
                            $inserted['is_approved'] = 1;
                            $inserted['treasuries_id'] = $was_review_shift['treasuries_id'];
                            $inserted['money'] = 0;
                            $inserted['money_for_account'] = $money * (-1);
                            $inserted['move_date'] = date('Y-m-d');
                            $inserted['byan'] = 'مراجعة واستلام الخزنة ' . $treasury_name . ' من المستخدم ' . $admin_name . ' رقم الشفت الذي تم مراجعته ' . $request->was_review_shift_id;
                            $inserted['added_by'] = auth()->user()->id;
                            $inserted['com_code'] = $com_code;
                            $inserted['created_at'] = date('Y-m-d H:i:s');
                            TreasuryTransaction::create($inserted);

                            $update_treasuries['last_unpaid_arrive'] = $last_unpaid_arrive + 1;
                            Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->update($update_treasuries);

                            $get_current = Account::where(['account_number' => $account_number, 'com_code' => $com_code])->value('current_balance');
                            $update_account['current_balance'] = $get_current + ($money * (-1));
                            Account::where(['account_number' => $account_number, 'com_code' => $com_code])->update($update_account);
                        }
                        else if ($money > 0) {
                            $update_shift['money_state'] = 2;

                            $update_shift['money_state'] = 1;

                            $max_transaction_code = TreasuryTransaction::where(['transaction_type' => 3,'com_code' => $com_code])->max('transaction_code');
                            if (empty($max_transaction_code)) {
                                $inserted['transaction_code'] = 1;
                            }
                            else {
                                $inserted['transaction_code'] = $max_transaction_code + 1;
                            }

                            $inserted['shift_code'] = $do_review_shift['id'];
                            $last_unpaid_arrive = Treasury::where(['id' => $do_review_shift['treasuries_id'], 'com_code' => $com_code])->value('last_unpaid_arrive');
                            if (empty($last_unpaid_arrive)) {
                                return redirect()->back()->with('error', 'الخزنة ليست صحيحة')->withInput();
                            }
                            else {
                                $inserted['last_arrive'] = $last_unpaid_arrive + 1;
                            }

                            $treasury_name = Treasury::where(['id' => $was_review_shift['treasuries_id'], 'com_code' => $com_code])->value('name');
                            $admin_name = Admin::where(['id' => $was_review_shift['admin_id'], 'com_code' => $com_code])->value('name');
                            $account_number = Treasury::where(['id' => $was_review_shift['treasuries_id']])->value('account_number');

                            $inserted['move_type'] = 1;
                            $inserted['transaction_type'] = 3;
                            $inserted['is_account'] = 1;
                            $inserted['account_number'] = $account_number;
                            $inserted['is_approved'] = 1;
                            $inserted['treasuries_id'] = $was_review_shift['treasuries_id'];
                            $inserted['money'] = 0;
                            $inserted['money_for_account'] = $money * (-1);
                            $inserted['move_date'] = date('Y-m-d');
                            $inserted['byan'] = 'مراجعة واستلام الخزنة ' . $treasury_name . ' من المستخدم ' . $admin_name . ' رقم الشفت الذي تم مراجعته ' . $request->was_review_shift_id;
                            $inserted['added_by'] = auth()->user()->id;
                            $inserted['com_code'] = $com_code;
                            $inserted['created_at'] = date('Y-m-d H:i:s');
                            TreasuryTransaction::create($inserted);

                            $update_treasuries['last_unpaid_arrive'] = $last_unpaid_arrive + 1;
                            Treasury::where(['id' => $request->treasuries_id, 'com_code' => $com_code])->update($update_treasuries);

                            $get_current = Account::where(['account_number' => $account_number, 'com_code' => $com_code])->value('current_balance');
                            $update_account['current_balance'] = $get_current - ($money);
                            Account::where(['account_number' => $account_number, 'com_code' => $com_code])->update($update_account);
                        }

                        $update_shift['delivered_to_shift_id'] = $do_review_shift['id'];
                        $update_shift['money_state_value'] = $money;
                        $update_shift['what_really_delivered'] = $request->what_paid;
                        $update_shift['review_receive_date'] = date('Y-m-d H:i:s');
                        $update_shift['updated_by'] = auth()->user()->id;
                        $update_shift['updated_at'] = date('Y-m-d H:i:s');
                        AdminShift::where('id', $request->was_review_shift_id)->update($update_shift);

                        return redirect()->back()->with('success', 'تم تحصيل النقدية ومراجعة الشفت بنجاح');
                    }
                    else {
                        return redirect()->back()->with('error', 'حدث خطأ ما');
                    }
                }
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
         }
         else {
             return redirect()->back();
         }
    }

    public function ajax_search(Request $request) {
        if ($request->ajax()) {

            $shift_code_search = $request->shift_code_search;
            $admin_id_search = $request->admin_id_search;
            $treasury_id_search = $request->treasury_id_search;
            $is_finished_search = $request->is_finished_search;
            $is_reviewed_search = $request->is_reviewed_search;

            if ($shift_code_search == '') {
                $filed1 = 'id';
                $operator1 = '>';
                $value1 = 0;
            }
            else {
                $filed1 = 'shift_code';
                $operator1 = '=';
                $value1 = $shift_code_search;
            }



            if ($admin_id_search == 'all') {
                $filed2 = 'id';
                $operator2 = '>';
                $value2 = 0;
            }
            else {
                $filed2 = 'admin_id';
                $operator2 = 'LIKE';
                $value2 = '%'. $admin_id_search . '%';
            }

            if ($treasury_id_search == 'all') {
                $filed3 = 'id';
                $operator3 = '>';
                $value3 = 0;
            }
            else {
                $filed3 = 'treasuries_id';
                $operator3 = '=';
                $value3 = $treasury_id_search;
            }

            if ($is_finished_search == 'all') {
                $filed4 = 'id';
                $operator4 = '>';
                $value4 = 0;
            }
            else {
                $filed4 = 'is_finished';
                $operator4 = '=';
                $value4 = $is_finished_search;
            }

            if ($is_reviewed_search == 'all') {
                $filed5 = 'id';
                $operator5 = '>';
                $value5 = 0;
            }
            else {
                if ($is_reviewed_search == 0) {
                    $filed5 = 'delivered_to_shift_id';
                    $operator5 = '=';
                    $value5 = null;
                }
                else if ($is_reviewed_search == 1) {
                    $filed5 = 'delivered_to_shift_id';
                    $operator5 = '!=';
                    $value5 = null;
                }
            }


            $check_shift = AdminShift::where(['admin_id' => auth()->user()->id, 'is_finished' => 0, 'com_code' => auth()->user()->com_code])->get(['treasuries_id', 'id'])->first();

            $data = AdminShift::where($filed1, $operator1, $value1)->where($filed2, $operator2, $value2)->where($filed3, $operator3, $value3)->where($filed4, $operator4, $value4)->where($filed5, $operator5, $value5)->where(['com_code' => auth()->user()->com_code])->orderBy('id', 'Desc')->paginate(PAGINATION_COUNT);

            if (!empty($data)) {
                foreach ($data as $d) {
                    $d['admin_name'] = Admin::where(['id' => $d['admin_id']])->value('name');
                    $d['treasuries_name'] = Treasury::where(['id' => $d['treasuries_id']])->value('name');
                    $d['allowed_review'] = false;
                    if (check_control_menu_role('حركة شفتات الخزن', 'شفتات الخزن', 'مراجعة شفت') == true) {
                        if ($d['is_finished'] == 1 && empty($d['delivered_to_shift_id']) && !empty($check_shift)) {
                            $allowed_tre = TreasuryDelivery::where(['treasuries_id' => $check_shift['treasuries_id']])->get(['treasuries_receive_from_id']);

                            if (!empty($allowed_tre)) {
                                foreach ($allowed_tre as $tre) {
                                    if ($d['treasuries_id'] == $tre['treasuries_receive_from_id']) {
                                        $d['allowed_review'] = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return view('admin.admin_shifts.ajax_search', ['data' => $data, 'check_shift' => $check_shift]);
        }
    }
}
