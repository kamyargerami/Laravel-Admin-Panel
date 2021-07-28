<?php

namespace App\Rules;

use App\Services\MobileService;
use Illuminate\Contracts\Validation\Rule;

class IranMobile implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $mobile = MobileService::generate($value);

        return (bool)preg_match('/^(((98)|(\+98)|(0098)|0)(9){1}[0-9]{9})+$/', $mobile) || (bool)preg_match('/^(9){1}[0-9]{9}+$/', $mobile);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'فرمت موبایل صحیح نیست';
    }
}
