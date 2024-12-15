<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
            'train_id' => 'required|exists:trains,id', // Kiểm tra ID tàu hợp lệ
            'car_index' => 'required|integer',
            'car_name' => 'required|string|max:255',
            'car_code' => 'required|string|max:50',
            'car_layout' => 'required|integer',
            'car_description' => 'nullable|string|max:255',
            'num_of_seats' => 'required|integer',
            'num_of_available_seats' => 'required|integer',
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
