<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
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
            'date' => 'required|date',
            'hour' => 'required|numeric|min:0|max:24',
            'minute' => 'required|numeric|min:0|max:59',
            'text' => 'required|string|min:5|max:1000',
            'methods' => 'required|array',
            'methods.*' => 'required|in:sms,email',
            'subject' => [function ($attribute, $value, $fail) {
                if (in_array('email', $this->methods) and !$value) {
                    $fail('موضوع برای ایمیل الزامی است');
                }

                return true;
            }],
            'button_text' => [function ($attribute, $value, $fail) {
                if (in_array('email', $this->methods) and !$value) {
                    $fail('متن دکمه برای ایمیل الزامی است');
                }

                return true;
            }],
            'button_link' => [function ($attribute, $value, $fail) {
                if (in_array('email', $this->methods) and !$value) {
                    $fail('لینک دکمه برای ایمیل الزامی است');
                }

                return true;
            }],
        ];
    }
}
