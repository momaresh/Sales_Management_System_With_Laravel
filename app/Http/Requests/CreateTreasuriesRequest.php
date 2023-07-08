<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTreasuriesRequest extends FormRequest
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
            'name' => 'required',
            'master' => 'required',
            'active' => 'required',
            'last_exchange_arrive' => 'required',
            'last_collection_arrive' => 'required',
        ];
    }


    public function messages()
    {
        return [
            //
            'name.required' => 'يجب ادخال اسم الخزينة',
            'master.required' => 'يجب ادخال هل هي رئيسية',
            'active.required' => 'يجب ادخال حالة التفعيل',
            'last_exchange_arrive.required' => 'يجب ادخال اخر ايصال صرف',
            'last_collection_arrive.required' => 'يجب ادخال اخر ايصال تحصيل',
        ];
    }
}
