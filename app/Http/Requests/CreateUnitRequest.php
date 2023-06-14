<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUnitRequest extends FormRequest
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
            'name' => 'required|unique:inv_units',
            'master' => 'required',
            'active' => 'required',
        ];
    }


    public function messages()
    {
        return [
            //
            'name.required' => 'يجب ادخال اسم المخزن',
            'name.unique' => 'اسم المخزن مضاف من قبل',
            'master.required' => 'يجب ادخال نوع الوحدة',
            'active.required' => 'يجب ادخال حالة التفعيل',
        ];
    }
}
