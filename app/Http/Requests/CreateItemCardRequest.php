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
         'price_per_one_in_master_unit' => 'required',
         'price_per_half_group_in_master_unit' => 'required',
         'price_per_group_in_master_unit' => 'required',
         'cost_price_in_master' => 'required',
         'price_per_one_in_retail_unit' => 'required_if:does_has_retailunit,1',
         'price_per_half_group_in_retail_unit' => 'required_if:does_has_retailunit,1',
         'price_per_group_in_retail_unit' => 'required_if:does_has_retailunit,1',
         'cost_price_in_retail' => 'required_if:does_has_retailunit,1',
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
        'price_per_one_in_master_unit.required' => 'سعر القطاعي للوحدة الاب مطلوب',
        'price_per_half_group_in_master_unit.required' => 'سعر النص جملة لوحدة الاب مطلوب',
        'price_per_group_in_master_unit.required' => 'سعر الجملة لوحده الاب مطلوب  ',
        'cost_price_in_master.required' => '  تكلفة الشراء لوحدة الاب مطلوب',
        'price_per_one_in_retail_unit.required_if' => 'سعر القطاعي لوحده التجزئة مطلوب ',
        'price_per_half_group_in_retail_unit.required_if' => 'سعر النص جملة لوحده التجزئة مطلوب ',
        'price_per_group_in_retail_unit.required_if' => 'سعر الجملة لوحده التجزئة مطلوب ',
        'cost_price_in_retail.required_if' => 'سعر الشراء لوحده التجزئة مطلوب ',
        'has_fixed_price.required' => 'هل للنصف سعر ثابت مطلوب',
        'active.required' => 'حالة تفعيل الصنف مطلوب',

        ];
    }
}
