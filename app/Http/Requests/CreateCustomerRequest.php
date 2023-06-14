<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
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
            'start_balance_status' => 'required',
            'start_balance' => 'required|min:0',
            'active' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'الاسم الاول للعميل مطلوب',
            'last_name.required' => 'الاسم الاخير للعميل مطلوب',
            'address.required' => 'عنوان العميل مطلوب',
            'phone.required' => 'هاتف العميل مطلوب',
            'start_balance_status.required' => 'حالة الحساب اول المدة مطلوب',
            'start_balance.required' => 'رصيد اول المدة مطلوب',
            'active.required' => 'حالة تفعيل الصنف مطلوب',
        ];
    }
}
