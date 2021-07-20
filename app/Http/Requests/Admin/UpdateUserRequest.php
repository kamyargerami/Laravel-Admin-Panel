<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $user_id = array_last(explode('/', $this->path()));

        return [
            'name' => 'required|string|min:3|max:120',
            'email' => 'nullable|required|email|unique:users,email,' . $user_id,
            'password' => 'nullable|min:5',
            'roles' => 'nullable|exists:roles,id'
        ];
    }
}
