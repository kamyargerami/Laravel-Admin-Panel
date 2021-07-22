<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddLicenceRequest extends FormRequest
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
            'user_id' => 'nullable|numeric|exists:users,id',
            'quantity' => 'required|numeric|min:1|max:2000',
            'character_length' => 'required|numeric|min:10|max:150',
            'max_use' => 'required|numeric|min:1|max:2000',
        ];
    }
}
