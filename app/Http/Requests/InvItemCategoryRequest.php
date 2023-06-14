<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvItemCategoryRequest extends FormRequest
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
            'name' => 'required|unique:inv_item_categories',
            'active' => 'required',
        ];
    }


    public function messages()
    {
        return [
            //
            'name.required' => 'يجب ادخال اسم الصنف',
            'name.unique' => 'اسم الصنف مضاف من قبل',
            'active.required' => 'يجب ادخال حالة التفعيل',
        ];
    }
}
