<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemCardRequest extends FormRequest
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
    public function rules()
    {
        return [
         'name' => 'required',
         'item_type' => 'required',
         'inv_itemcard_categories_id' => 'required',
         'unit_id' => 'required',
         'does_has_retailunit' => 'required',
         'retail_unit_id' => 'required_if:does_has_retailunit,1',
         'retail_uom_quntToParent' => 'required_if:does_has_retailunit,1',
         'has_fixed_price' => 'required',
         'active' => 'required',
         'item_img' => 'mimes:png,jpg,jpeg',
        ];
    }

    public function messages()
    {
        return [
        'name.required' => 'اسم الصنف مطلوب',
        'item_type.required' => 'نوع الصنف مطلوب',
        'inv_itemcard_categories_id.required' => 'فئة الصنف مطلوب',
        'unit_id.required' => 'الوحدة الاساسية للصنف مطلوب',
        'does_has_retailunit.required' => 'حالة هل للصنف وحدة تجزئة مطلوب',
        'retail_unit_id.required_if' => 'وحدة التجزئة مطلوبة',
        'retail_uom_quntToParent.required_if' => 'عدد وحدات التجزئة مطلوبة',
        'has_fixed_price.required' => 'هل للنصف سعر ثابت مطلوب',
        'active.required' => 'حالة تفعيل الصنف مطلوب',

        ];
    }
}
