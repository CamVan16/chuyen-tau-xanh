<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string|max:50|unique:vouchers,code',
            'name' => 'required|string|max:255',
            'min_price_order' => 'required|numeric|min:0',
            'percent' => 'required|integer|min:0|max:100',
            'max_price_discount' => 'required|numeric|min:0',
            'type' => 'required|integer|in:0,1,2',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
