<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'active' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'الاسم الاول للمورد مطلوب',
            'last_name.required' => 'الاسم الاخير للمورد مطلوب',
            'address.required' => 'عنوان المورد مطلوب',
            'phone.required' => 'هاتف المورد مطلوب',
            'active.required' => 'حالة تفعيل الصنف مطلوب',
        ];
    }
}
