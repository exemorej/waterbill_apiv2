<?php

namespace App\Http\Requests\Bill;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBillRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required',
            'period_start' => 'required|date',
            'period_end' => 'required|date',
            'due_date' => 'required|date',
            // 'image' => 'nullable',
            'amount' => 'required|numeric',
            'paid' => 'required|numeric',
        ];
    }
}
