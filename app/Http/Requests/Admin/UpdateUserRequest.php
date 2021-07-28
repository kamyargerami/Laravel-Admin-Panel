<?php

namespace App\Http\Requests\Admin;

use App\Services\MobileService;
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
        return [
            'name' => 'required|string|min:3|max:120',
            'email' => 'required|required|email|unique:users,email,' . $this->route()->parameter('user')->id,
            'password' => 'nullable|min:5',
            'roles' => 'nullable|exists:roles,id',
            'mobile' => ['nullable', function ($attribute, $value, $fail) {
                if (!MobileService::validate($value, true, $this->route()->parameter('user')->id)['status']) {
                    foreach (MobileService::validate($value, true, $this->route()->parameter('user')->id)['status']['errors'] as $error) {
                        $fail($error);
                    }
                }

                return true;
            }]
        ];
    }
}
