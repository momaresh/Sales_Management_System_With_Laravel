<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePanelSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'system_name' => 'required|unique:admin_panel_settings,system_name,'.$this->id,
            'active' => 'required',
            'customer_parent_account' => 'required',
            'supplier_parent_account' => 'required',
            'customer_first_code' => 'required',
            'supplier_first_code' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'photo' => 'mimes:png,jpg,jpeg',
        ];
    }
}
