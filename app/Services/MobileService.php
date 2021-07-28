<?php

namespace App\Services;


use App\Rules\IranMobile;
use Illuminate\Support\Facades\Validator;

class MobileService
{
    public static function validate($mobile, $check_unique = true, $except_user_id = null)
    {
        $mobile = self::generate($mobile);

        if ($check_unique) {
            $validator = Validator::make(['mobile' => $mobile], [
                'mobile' => ['required', 'numeric', 'digits_between:10,13', new IranMobile(), 'unique:users,mobile' . ',' . $except_user_id ?: ''],
            ]);
        } else {
            $validator = Validator::make(['mobile' => $mobile], [
                'mobile' => ['required', 'numeric', 'digits_between:10,13', new IranMobile()],
            ]);
        }

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors()->messages()['mobile']
            ];
        }

        return [
            'status' => true
        ];
    }

    public static function generate($mobile)
    {
        $new_mobile = ltrim(preg_replace('/\D/', '', str_replace(["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"], ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"], $mobile)), '0');

        if (substr($new_mobile, 0, 2) == '98') {
            $new_mobile = substr($new_mobile, 2, 10);
        }

        return $new_mobile;
    }
}
