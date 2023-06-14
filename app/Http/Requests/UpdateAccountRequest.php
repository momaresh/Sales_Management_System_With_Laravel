<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
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
            'is_parent' => 'required',
            'account_type' => 'required',
            'parent_account_number' => 'required_if:is_parent,0',
            'notes' => 'required_if:account_type,1,6,7,8,9',
            'person_id' => 'required_if:account_type,2,3,4,5',
            'active' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'account_type.required' => 'نوع الحساب مطلوب',
            'is_parent.required' => ' هل الحساب اب مطلوب',
            'parent_account_number.required_if' => '  الحساب الاب مطلوب',
            'person_id.required_if' => 'اسم صاحب الحساب مطلوب',
            'active.required' => 'حالة تفعيل الصنف مطلوب',
            'notes.required_if' => 'ادخل الملاحظة حول الحساب مثل اسمه',
        ];
    }
}
