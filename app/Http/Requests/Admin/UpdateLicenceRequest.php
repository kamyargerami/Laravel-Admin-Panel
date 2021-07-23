<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLicenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|numeric|exists:products,id',
            'user_id' => 'required|numeric|exists:users,id',
            'max_use' => 'required|numeric|min:1|max:2000',
            'status' => 'required|boolean',
            'expires_at' => 'nullable|date'
        ];
    }
}
