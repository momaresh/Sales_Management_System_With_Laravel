<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminPanelSetting;
use App\Models\Admin;
use App\Http\Requests\UpdatePanelSettingRequest;
use App\Models\Account;

class AdminPanelSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (check_control_menu_role('الضبط العام', 'الضبط العام' , 'عرض') == true) {
            $com_code = auth()->user()->com_code;
            $data = AdminPanelSetting::where('com_code', auth()->user()->com_code)->first();

            if (!empty($data)) {
                if ($data['updated_by'] > 0 && $data['updated_by'] != null) {
                    $data['updated_by_name'] = Admin::where('id', $data['updated_by'])->value('name');
                }

                $data['customer_parent_account_name'] = Account::where(['account_number' => $data['customer_parent_account'], 'com_code' => $com_code])->value('notes');
                $data['supplier_parent_account_name'] = Account::where(['account_number' => $data['supplier_parent_account'], 'com_code' => $com_code])->value('notes');
                $data['delegate_parent_account_name'] = Account::where(['account_number' => $data['delegate_parent_account'], 'com_code' => $com_code])->value('notes');
                $data['employee_parent_account_name'] = Account::where(['account_number' => $data['employee_parent_account'], 'com_code' => $com_code])->value('notes');
                $data['treasury_parent_account_name'] = Account::where(['account_number' => $data['treasury_parent_account'], 'com_code' => $com_code])->value('notes');

                return view('admin.admin_panel_settings.index', ['data' => $data]);
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (check_control_menu_role('الضبط العام', 'الضبط العام' , 'تعديل') == true) {
            $data = AdminPanelSetting::find($id);

            $accounts = Account::where(['com_code' => auth()->user()->com_code, 'is_parent' => 1, 'active' => 1])->get(['account_number', 'notes']);

            return view('admin.admin_panel_settings.edit', ['data' => $data, 'accounts' => $accounts]);
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
    public function update(UpdatePanelSettingRequest $request, $id)
    {
        //
        if (check_control_menu_role('الضبط العام', 'الضبط العام' , 'تعديل') == true) {
            $updated['system_name'] = $request->system_name;
            $updated['address'] = $request->address;
            $updated['phone'] = $request->phone;
            $updated['customer_parent_account'] = $request->customer_parent_account;
            $updated['supplier_parent_account'] = $request->supplier_parent_account;
            $updated['delegate_parent_account'] = $request->delegate_parent_account;
            $updated['employee_parent_account'] = $request->employee_parent_account;
            $updated['treasury_parent_account'] = $request->treasury_parent_account;
            $updated['customer_first_code'] = $request->customer_first_code;
            $updated['supplier_first_code'] = $request->supplier_first_code;
            $updated['delegate_first_code'] = $request->delegate_first_code;
            $updated['employee_first_code'] = $request->employee_first_code;
            $updated['commission_for_group_sales'] = $request->commission_for_group_sales;
            $updated['commission_for_half_group_sales'] = $request->commission_for_half_group_sales;
            $updated['commission_for_one_sales'] = $request->commission_for_one_sales;
            $updated['tax_percent_for_invoice'] = $request->tax_percent_for_invoice;
            $updated['active'] = $request->active;
            $updated['updated_by'] = auth()->user()->id;
            $updated['updated_at'] = date('Y-m-d H:i:s');

            if (!empty($request->photo)) {
                $old_image = AdminPanelSetting::where('id', $id)->value('photo');
                $image = $request->photo;
                $extension = strtolower($image->extension());
                $file_name = time() . rand(1, 1000) . '.' . $extension;
                $image->move('assets\admin\uploads\images\\', $file_name);
                $updated['photo'] = $file_name;

                if (!empty($old_image)) {
                    // deleting the old image from the folder
                    if(file_exists("assets/admin/uploads/images/".$old_image)) {
                        unlink("assets/admin/uploads/images/".$old_image);
                    }
                }
            }

            AdminPanelSetting::where('id', $id)->update($updated);

            return redirect()->route('admin.panelSetting.index')->with('success', 'تم التعديل بنجاح');
        }
        else {
            return redirect()->back();
        }
    }
}
