<?php


namespace App\Services;


use App\Models\License;

class LicenseService
{
    public static function create($type, $max_use, $user_id, $status, $product_id, $key_length)
    {
        do {
            $key = HashService::rand($key_length);
        } while (License::where('key', $key)->count());

        return License::create([
            'type' => $type,
            'max_use' => $max_use,
            'user_id' => $user_id,
            'status' => $status,
            'product_id' => $product_id,
            'key' => $key,
        ]);
    }

    public static function getExpireDate($type)
    {
        $result = null;

        switch ($type) {
            case '1_month':
            case 'trial':
                $result = now()->addMonth()->toDateString();
                break;
            case '2_month':
                $result = now()->addMonths(2)->toDateString();
                break;
            case '3_month':
                $result = now()->addMonths(3)->toDateString();
                break;
            case '6_month':
                $result = now()->addMonths(6)->toDateString();
                break;
            case '1_year':
                $result = now()->addYear()->toDateString();
                break;
            case '2_year':
                $result = now()->addYears(2)->toDateString();
                break;
        }

        return $result;
    }
}
