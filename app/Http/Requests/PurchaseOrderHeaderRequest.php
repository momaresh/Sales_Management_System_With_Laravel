<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderHeaderRequest extends FormRequest
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
            'supplier_code' => 'required',
            'pill_type' => 'required',
            'order_date' => 'required',
            'store_id' => 'required'
        ];
    }

    public function messages()
    {
        # code...
        return [
            'supplier_code.required' => 'اسم المورد مطلوب',
            'pill_type.required' => 'نوع الفاتورة مطلوب',
            'order_date.required' => 'تاريخ الفاتورة مطلوب',
            'store_id.required' => 'اسم المخزن مطلوب'
        ];
    }
}
