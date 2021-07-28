<?php

namespace App\Http\Requests\Api;

use App\Services\MobileService;
use Illuminate\Foundation\Http\FormRequest;

class ActivateLicenceRequest extends FormRequest
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
            'key' => 'required|string|max:250',
            'first_name' => 'required|string|max:250',
            'last_name' => 'required|string|max:250',
            'country' => 'required|string|max:250',
            'company' => 'nullable|string|max:250',
            'email' => 'required|email|max:250',
            'phone' => ['required', function ($attribute, $value, $fail) {
                if (!MobileService::validate($value, false)['status']) {
                    foreach (MobileService::validate($value, false)['errors'] as $error) {
                        $fail($error);
                    }
                }

                return true;
            }],
            'city' => 'required|string|max:250',
            'version' => 'required|string|max:250',
            'fingerprint' => 'required|string|max:250',
            'device_name' => 'required|string|max:250',
            'product_name' => 'required|string|max:250|exists:products,name'
        ];
    }
}
