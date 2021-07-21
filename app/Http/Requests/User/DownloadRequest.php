<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
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
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:100',
            'file' => 'required|string|max:200',
            'product' => 'required|string|exists:products,name'
        ];
    }
}
