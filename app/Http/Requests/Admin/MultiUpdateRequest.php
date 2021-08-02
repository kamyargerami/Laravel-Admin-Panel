<?php

namespace App\Http\Requests\Admin;

use App\Models\License;
use Illuminate\Foundation\Http\FormRequest;

class MultiUpdateRequest extends FormRequest
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
            'new_product_id' => 'nullable|numeric|exists:products,id',
            'new_user_id' => 'nullable|numeric|exists:users,id',
            'new_max_use' => 'nullable|numeric|min:1|max:2000',
            'new_status' => 'nullable|boolean',
            'new_expires_at' => 'nullable|date',
            'new_type' => 'nullable|string|in:' . implode(',', License::Types)
        ];
    }
}
